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

// Ambil data tanggapan
$query = "SELECT * FROM tanggapan WHERE id_pengaduan = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id_pengaduan);
mysqli_stmt_execute($stmt);
$result_tanggapan = mysqli_stmt_get_result($stmt);
$tanggapan = mysqli_fetch_assoc($result_tanggapan);

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
            transform: scale(1.05);
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 fade-in">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold mb-4">
                <i class="fas fa-check-circle mr-2"></i>
                Detail Pengaduan
            </h1>
            <div class="mb-4">
                <h2 class="font-semibold">Detail Pengaduan</h2>
                <p><strong>Id Pengaduan:</strong> <?php echo $id_pengaduan; ?></p>
                <p><strong>Pelapor:</strong> <?php echo htmlspecialchars($pengaduan['nama_pelapor']); ?></p>
                <p><strong>Tanggal:</strong> <?php echo date('d/m/Y', strtotime($pengaduan['tgl_pengaduan'])); ?></p>
                <p><strong>Isi Laporan:</strong> <?php echo nl2br(htmlspecialchars($pengaduan['isi_laporan'])); ?></p>
                <div class="bg-white p-4 rounded-lg shadow-xl mb-4">
                    <h2 class="font-bold"><i class="fas fa-image mr-2"></i>Foto Laporan</h2>
                    <?php if (!empty($pengaduan['foto'])): ?>
                        <p class="mb-2"><strong>Nama File:</strong> <?php echo htmlspecialchars($pengaduan['foto']); ?></p>
                        <img src="./assets/images-uploaded/<?php echo htmlspecialchars($pengaduan['foto']); ?>" alt="file foto">
                        <a href="assets/images-uploaded/<?php echo htmlspecialchars($pengaduan['foto']); ?>" download class="inline-flex mt-10 items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-download mr-2"></i>Download Foto Laporan
                        </a>
                    <?php else: ?>
                        <p>Tidak ada foto yang diunggah.</p>
                    <?php endif; ?>
                </div>
 </div>
            <div class="mb-4">
                <h2 class="font-semibold"><i class="fas fa-comment mr-2"></i>Tanggapan</h2>
                <p><strong>Tanggapan:</strong> <?php echo htmlspecialchars($tanggapan['tanggapan']); ?></p>
                <?php if (!empty($tanggapan['file'])): ?>
                    <p class="mb-2"><strong>Nama File:</strong> <?php echo htmlspecialchars($tanggapan['file']); ?></p>
                    <?php
                    $file_type = mime_content_type('./assets/file/' . $tanggapan['file']);
                    if (strpos($file_type, 'pdf') !== false) {
                        ?>
                        <iframe src="./assets/file/<?php echo htmlspecialchars($tanggapan['file']); ?>" frameborder="0" width="100%" height="500"></iframe>
                    <?php } elseif (strpos($file_type, 'msword') !== false || strpos($file_type, 'office') !== false) { ?>
                        <iframe src="https://view.officeapps.live.com/op/view.aspx?src=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . '/assets/file/' . $tanggapan['file']); ?>" frameborder="0" width="100%" height="500"></iframe>
                    <?php } else { ?>
                        <p>File type not supported for preview.</p>
                    <?php } ?>
                    <a href="assets/file/<?php echo htmlspecialchars($tanggapan['file']); ?>" download class="inline-flex mt-10 items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-download mr-2"></i>Download File Tanggapan
                    </a>
                <?php else: ?>
                    <p class="">Tidak ada file yang diunggah.</p>
                <?php endif; ?>
            </div>
            <a href="indexoperator.php?page=sarana" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 transition-all duration-200 transform hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
</body>
</html>