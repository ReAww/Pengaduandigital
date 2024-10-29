<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pengaduandigital";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $tgl_pengaduan = date('Y-m-d');
    $nis = $_SESSION['nis'];
    $isi_laporan = $_POST['isi_laporan'];
    
    // Check if a file was uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $foto = $_FILES['file']['name'];
        $tmp = $_FILES['file']['tmp_name'];
        $fotobaru = date('dmYHis') . '_' . $foto; // Add timestamp to prevent duplicate names
        $path = "assets/images-uploaded/" . $fotobaru;

        // Create directory if it doesn't exist
        if (!file_exists("assets/images-uploaded/")) {
            mkdir("assets/images-uploaded/", 0777, true);
        }

        if (move_uploaded_file($tmp, $path)) {
            $sql = "INSERT INTO pengaduan (tgl_pengaduan, nis, isi_laporan, foto, status) 
                    VALUES (?, ?, ?, ?, 'Pending')";
            
            // Use prepared statement to prevent SQL injection
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $tgl_pengaduan, $nis, $isi_laporan, $fotobaru);
            
            if ($stmt->execute()) {
                echo "<script>window.location='indexsiswa.php?page=sended';</script>";
            } else {
                echo "<script>alert('Failed to submit complaint: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Failed to upload file!');</script>";
        }
    } else {
        echo "<script>alert('Please select a file to upload!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
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
        .drop-container {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 200px;
            padding: 20px;
            border-radius: 10px;
            border: 2 px dashed #555;
            color: #444;
            cursor: pointer;
            transition: background .2s ease-in-out, border .2s ease-in-out;
        }
        .drop-container:hover,
        .drop-container.drag-active {
            background: #eee;
            border-color: #111;
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Sarana - Digital School</title>
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto my-8 p-4">
        <!-- Biodata Siswa -->
        <div class="bg-white rounded-lg shadow-lg mb-6 animate__animated animate__fadeIn">
            <div class="bg-blue-600 text-white p-4 rounded-t-lg flex items-center">
                <i class="fas fa-user-circle mr-2 text-2xl"></i>
                <h2 class="text-lg font-semibold">Biodata Siswa</h2>
            </div>
            <div class="p-4">
                <?php
                $nis = $_SESSION['nis'];
                $query = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$nis'");
                $data = mysqli_fetch_array($query);
                ?>
                <table class="w-full">
                    <tr>
                        <td class="font-semibold">NIS</td>
                        <td>:</td>
                        <td><?= $data['nis']; ?></td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Nama</td>
                        <td>:</td>
                        <td><?= $data['nama']; ?></td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Kelas</td>
                        <td>:</td>
                        <td><?= $data['kelas']; ?></td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Jurusan</td>
                        <td>:</td>
                        <td><?= $data['jurusan']; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Form Pengaduan -->
        <div class="bg-white rounded-lg shadow-lg mb-6 animate__animated animate__fadeIn">
            <div class="bg-blue-600 text-white p-4 rounded-t-lg flex items-center">
                <i class="fas fa-edit mr-2 text-2xl"></i>
                <h2 class="text-lg font-semibold">Form Pengaduan Sarana</h2>
            </div>
            <div class="p-4">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block text-gray- 700 font-semibold mb-2">Isi Laporan</label>
                        <textarea class="w-full p-2 text-sm text-gray-700" name="isi_laporan" required placeholder="Deskripsikan masalah yang Anda temukan..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Upload Foto</label>
                        <label for="file" class="drop-container border-2 border-dashed border-gray-600 rounded-lg">
    <span class="drop-title">Drop files here</span>
    or
    <input type="file" id="images" name="file" accept="image/*" required>
</label>
                    </div>
                    <button type="submit" name="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Pengaduan
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Riwayat Pengaduan -->
        <div class="bg-white rounded-lg shadow-lg mb-6 animate__animated animate__fadeIn">
            <div class="bg-blue-600 text-white p-4 rounded-t-lg flex items-center">
                <i class="fas fa-history mr-2 text-2xl"></i>
                <h2 class="text-lg font-semibold">Riwayat Pengaduan</h2>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-gray-700 font-semibold">No</th>
                                <th class="px-4 py-2 text-gray-700 font-semibold">Tanggal</th>
                                <th class="px-4 py-2 text-gray-700 font-semibold">NIS</th>
                                <th class="px-4 py-2 text-gray-700 font-semibold">Isi Laporan</th>
                                <th class="px-4 py-2 text-gray-700 font-semibold">Foto</th>
                                <th class="px-4 py-2 text-gray-700 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $nis = $_SESSION['nis'];
                            $query = mysqli_query($conn, "SELECT * FROM pengaduan WHERE nis='$nis' ORDER BY id_pengaduan DESC");
                            while ($data = mysqli_fetch_array($query)) {
                                // Check if 'foto' key exists in the array
                                $foto = isset($data['foto']) ? $data['foto'] : 'default.jpg'; // Use a default image if 'foto' is not set
                            ?>
                            <tr class="hover:bg-gray-100" onclick="location.href='indexsiswa.php?page=viewsarana&id=<?= $data['id_pengaduan']; ?>'">
                                <td class="px-4 py-2"><?= $no++; ?></td>
                                <td class="px-4 py-2"><?= date('d-m-Y', strtotime($data['tgl_pengaduan'])); ?></td>
                                <td class="px-4 py-2"><?= $data['nis']; ?></td>
                                <td class="px-4 py-2 truncate">
                                    <?php echo htmlspecialchars(truncateText($data['isi_laporan'], 15)); ?>
                                </td>
                                <td class="px-4 py-2">
                                    <img src="assets/images-uploaded/<?= $foto; ?>" width="100" alt="Complaint Photo">
                                </td>
                                <td class="px-4 py-2">
                                    <?php
                                    if ($data['status'] == 'Pending') {
                                        echo "<span class='bg-yellow-100 text-yellow-800 py-1 px-2 rounded'>Pending</span>";
                                    } elseif ($data['status'] == 'Proses') {
                                        echo "<span class='bg-orange-100 text-orange-800 py-1 px-2 rounded'>Proses</span>";
                                    } else {
                                        echo "<span class='bg-green-100 text-green-800 py-1 px-2 rounded'>Selesai</span>";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        // Smooth scroll animation
        $(document).ready(function(){
            $('html, body').animate({
                scrollTop: $('.container').offset().top
            }, 1000);
        });

        // Preview image before upload
        $('input[type="file"]').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(e.target.files[0]);
        });
    </script>
</body>
</html>

<?php
function truncateText($text, $length) {
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length) . "...";
    }
    return $text;
}
?>