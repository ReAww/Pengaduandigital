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

// Proses pengiriman tanggapan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggapan = "Tanggapan diproses"; // Tanggapan default
    $tanggal = date('Y-m-d H:i:s'); // Capture the current date and time
    
    // Ambil id_petugas dari session
    $id_petugas = $_SESSION['id_petugas']; // Assuming this is set during login

    // Simpan tanggapan ke tabel tanggapan
    $query = "INSERT INTO tanggapan (id_pengaduan, tanggapan, tgl_tanggapan, id_petugas) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'issi', $id_pengaduan, $tanggapan, $tanggal, $id_petugas);
    
    if (mysqli_stmt_execute($stmt)) {
        // Update status pengaduan menjadi Proses
        $query = "UPDATE pengaduan SET status = 'Proses' WHERE id_pengaduan = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id_pengaduan);
        mysqli_stmt_execute($stmt); 
        
        // Redirect to the main page after successful submission        
        echo "<script>window.location.href='indexoperator.php?page=sarana'</script>";
        exit; // Ensure no further code is executed after the redirect
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Ambil data pengaduan untuk ditampilkan, termasuk nama pelapor dan email
$query = "
    SELECT p.*, s.nama AS nama_pelapor, s.email AS email_pelapor 
    FROM pengaduan p 
    JOIN siswa s ON p.nis = s.nis 
    WHERE p.id_pengaduan = ?
";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id_pengaduan);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pengaduan = mysqli_fetch_assoc($result);

// Check if the complaint data was retrieved
if (!$pengaduan) {
    die("Pengaduan tidak ditemukan."); // Handle case where no data is found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanggapan Pengaduan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">Tanggapan untuk Pengaduan</h1>
        
        <div class ="bg-white p-4 rounded-lg shadow-md mb-4">
            <h2 class="font-bold">Detail Pengaduan</h2>
            <p><strong>Pelapor:</strong> <?php echo htmlspecialchars($pengaduan['nama_pelapor']); ?></p>
            <p><strong>NIS:</strong> <?php echo htmlspecialchars($pengaduan['nis']); ?></p>
            <p><strong>Tanggal:</strong> <?php echo date('Y-m-d', strtotime($pengaduan['tgl_pengaduan']            )); ?></p>
            <p><strong>Isi Laporan:</strong> <?php echo htmlspecialchars($pengaduan['isi_laporan']); ?></p>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow-md mb-4">
            <h2 class="font-bold">Foto Laporan</h2>
            <?php if (!empty($pengaduan['foto'])): ?>
                <p class="mb-2"><strong>Nama File:</strong> <?php echo htmlspecialchars($pengaduan['foto']); ?></p>
                
                <?php
                // Create the structured filename
                $structured_name = "images-uploaded".htmlspecialchars($pengaduan['foto']);        
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
    
    <form method="post">            
        <button type="submit" class="bg-blue-500 mt-5 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Proses</button>
    </form>
    
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>