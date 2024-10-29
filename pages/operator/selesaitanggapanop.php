<?php
// Koneksi database
$host = "localhost";
$username = "root";
$password = "";
$database = "pengaduandigital";

$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil ID pengaduan dari URL
$id_pengaduan = isset($_GET['id']) ? $_GET['id'] : 0;

// Ambil data pengaduan
$query = "SELECT p.*, s.nama AS nama_pelapor FROM pengaduan p JOIN siswa s ON p.nis = s.nis WHERE p.id_pengaduan = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id_pengaduan);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pengaduan = mysqli_fetch_assoc($result);

if (!$pengaduan) {
    die("Pengaduan tidak ditemukan.");
}

// Proses penyelesaian tanggapan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil tanggapan dari form
    $tanggapan = isset($_POST['tanggapan']) ? $_POST['tanggapan'] : '';
    
    // Update tanggapan di database
    $query = "UPDATE tanggapan SET tanggapan = ? WHERE id_pengaduan = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'si', $tanggapan, $id_pengaduan);
    
    if (mysqli_stmt_execute($stmt)) {
        // Update status menjadi "Selesai"
        $query = "UPDATE pengaduan SET status = 'Selesai' WHERE id_pengaduan = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id_pengaduan);
        mysqli_stmt_execute($stmt);
        
        // Redirect ke halaman indexoperator
        echo "<script> window.location.href='indexoperator.php?page=sarana'</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Proses unggah file
    if (isset($_FILES['file'])) {
        $target_dir = "assets/file/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Cek ukuran file
        if ($_FILES["file"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Cek jenis file
        if (!in_array($fileType, ['jpg', 'png', 'jpeg', 'gif', 'pdf', 'doc', 'docx'])) {
            echo "Sorry, only JPG, JPEG, PNG, GIF, PDF, DOC, DOCX files are allowed.";
            $uploadOk = 0;
        }

        // Jika semua cek berhasil, unggah file
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                // Simpan nama file ke dalam database
                $query = "UPDATE tanggapan SET file = ? WHERE id_pengaduan = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'si', $_FILES["file"]["name"], $id_pengaduan);
                mysqli_stmt_execute($stmt);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Tanggapan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        .hover\:scale-105:hover {
            transform: scale(1.05 );
        }
        .drop-container {
            position: relative;
            display: flex;
            gap: 10px;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 200px;
            padding: 20px;
            border-radius: 10px;
            border: 2px dashed #555;
            color: #444;
            cursor: pointer;
            transition: background .2s ease-in-out, border .2s ease-in-out;
        }
        .drop-container:hover,
        .drop-container.drag-active {
            background: #eee;
            border-color: #111;
        }
        .drop-container:hover .drop-title,
        .drop-container.drag-active .drop-title {
            color: #222;
        }
        .drop-title {
            color: #444;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            transition: color .2s ease-in-out;
        }
        input[type=file] {
            width: 350px;
            max-width: 100%;
            color: #444;
            padding: 5px;
            background: #fff;
            border-radius: 10px;
            border: 1px solid #555;
        }
        input[type=file]::file-selector-button {
            margin-right: 20px;
            border: none;
            background: #084cdf;
            padding: 10px 20px;
            border-radius: 10px;
            color: #fff;
            cursor: pointer;
            transition: background .2s ease-in-out;
        }
        input[type=file]::file-selector-button:hover {
            background: #0d45a5;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 fade-in">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold mb-4">
                <i class="fas fa-check-circle mr-2"></i>
                Selesaikan Tanggapan
            </h1>
            <div class="mb-4">
                <h2 class="font-semibold">Detail Pengaduan</h2>
                <p><strong>Id Pengaduan:</strong> <?php echo $id_pengaduan; ?></p>
                <p><strong>Pelapor:</strong> <?php echo htmlspecialchars($pengaduan['nama_pelapor']); ?></p>
                <p><strong>Tanggal:</strong> <?php echo date('d/m/Y', strtotime($pengaduan['tgl_pengaduan'])); ?></p>
                <p><strong>Isi Laporan:</strong> <?php echo nl2br(htmlspecialchars($pengaduan['isi_laporan'])); ?></p>
                <div class="bg-white p-4 rounded-lg shadow-xl mb-4">
                    <h2 class="font-bold">Foto Laporan</h2>
                    <?php if (!empty($pengaduan['foto'])): ?>
                        <p class="mb-2"><strong>Nama File:</strong> <?php echo htmlspecialchars($pengaduan['foto']); ?></p>
                        
                        <?php
                        // Create the structured filename
                        $structured_name = "images-uploaded/".htmlspecialchars($pengaduan['foto']);        
                        ?>
                        <img src="./assets/<?php echo $structured_name; ?>" alt="file foto">
                        <a href="assets/<?php echo $structured_name; ?>" download class="inline-flex mt-10 items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-download mr-2"></i>Download Foto Laporan
                        </a>
                    <?php else: ?>
                        <p>Tidak ada foto yang diunggah.</p>
                    <?php endif; ?>
                </div>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="tanggapan" class="block mb-2">Tanggapan:</label>
                    <textarea id="tanggapan" name="tanggapan" class="w-full p-4 border rounded-lg" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="file" class="drop-container" id="dropcontainer">
                        <span class="drop-title">Drop files here</span>
                        or
                        <input type="file" id="images" name="file" accept="image/*" required>
                    </label>
                </div>
                <button type="submit" class=" px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors hover:scale-105">
                    <i class="fas fa-check mr-2"></i> Selesaikan
                </button>
                <a href="indexoperator.php?page=sarana" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors hover:scale-105">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </form>
        </div>
    </div>

    <script>
        const dropContainer = document.getElementById("dropcontainer")
        const fileInput = document.getElementById("images")

        dropContainer.addEventListener("dragover", (e) => {
            // prevent default to allow drop
            e.preventDefault()
        }, false)

        dropContainer.addEventListener("dragenter", () => {
            dropContainer.classList.add("drag-active")
        })

        dropContainer.addEventListener("dragleave", () => {
            dropContainer.classList.remove("drag-active")
        })

        dropContainer.addEventListener("drop", (e) => {
            e.preventDefault()
            dropContainer.classList.remove("drag-active")
            fileInput.files = e.dataTransfer.files
        })
    </script>
</body>
</html>