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

    // Inisialisasi variabel pencarian, filter, dan urutan
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $status_filter = isset($_GET['status']) ? $_GET['status'] : '';
    $date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
    $date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';
    $order = isset($_GET['order']) ? $_GET['order'] : 'DESC'; // Default urutan adalah DESC

    // Query dasar dengan join untuk mendapatkan nama pelapor dan jenis pengaduan
    $query = "
        SELECT p.*, s.nama AS nama_pelapor, p.jenis_pengaduan 
        FROM pengaduan p
        JOIN siswa s ON p.nis = s.nis
        WHERE 1=1
    ";

    // Tambahkan kondisi pencarian
    if (!empty($search)) {
        $query .= " AND (p.nis LIKE '%$search%' 
                    OR p.isi_laporan LIKE '%$search%' 
                    OR p.status LIKE '%$search%')";
    }

    // Tambahkan filter status
    if (!empty($status_filter)) {
        $query .= " AND p.status = '$status_filter'";
    }

    // Tambahkan filter tanggal
    if (!empty($date_from) && !empty($date_to)) {
        $query .= " AND p.tgl_pengaduan BETWEEN '$date_from' AND '$date_to'";
    }

    // Tambahkan pengurutan berdasarkan id_pengaduan
    $query .= " ORDER BY p.id_pengaduan " . ($order === 'ASC' ? 'ASC' : 'DESC');

    // Eksekusi query
    $result = mysqli_query($conn, $query);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sistem Pengaduan Digital</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    </head>
    <body class="bg-gray-100">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="bg-sky-500 rounded-lg shadow-md p-6 mb-6">
                <h1 class="text-2xl font-bold text-white mb-2">
                    <i class="fas fa-tools mr-2"></i>
                    Sistem Pengaduan Sarana
                </h1>
            </div>

            <!-- Filter Form -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Pencarian -->
                    <div class="relative">
                        <input type="text" 
                            name="search" 
                            value="<?php echo htmlspecialchars($search); ?>"
                            placeholder="Cari pengaduan..." 
                            class="w-full px-4 py-2 border rounded-lg pl-10">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>

                    <!-- Filter Status -->
                    <select name="status" class="px-4 py-2 border rounded-lg">
                        <option value="">Semua Status</option>
                        <option value="Pending" <?php if($status_filter == 'Pending') echo 'selected'; ?>>Pending</option>
                        <option value="Proses" <?php if($status_filter == 'Proses') echo 'selected'; ?>>Proses</option>
                        <option value="Selesai" <?php if($status_filter == 'Selesai') echo 'selected'; ?>>Selesai</option>
                    </select>

                    <!-- Filter Tanggal -->
                    <div class="grid grid-cols-2 gap-2">
                        <input type="date" 
                            name="date_from" 
                            value="<?php echo $date_from; ?>"
                            class="px-4 py-2 border rounded-lg"
                            placeholder="Dari Tanggal">
                        <input type="date" 
                            name="date_to" 
                            value="<?php echo $date_to; ?>"
                            class="px-4 py-2 border rounded-lg"
                            placeholder="Sampai Tanggal">
                    </div>

                    <!-- Tombol Filter -->
                    <div class="md:col-span-2 lg:col-span-4 flex justify-end gap-2">
                        <a href="indexoperator.php?page=sarana" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            <i class="fas fa-sync-alt mr-2"></i>Reset
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                            <i class="fas fa-filter mr-2"></i>Terapkan Filter
                        </button>
                    </div>
                </form>
    </div>
            <!-- Tombol Toggle Urutan -->
            <div class="mb-4">
                <a href="?page=sarana&search=<?php echo urlencode($search); ?>&status=<?php echo $status_filter; ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-sort-amount-<?php echo $order === 'ASC' ? 'down' : 'up'; ?>"></i> Urutkan <?php echo $order === 'ASC' ? 'Terlama' : 'Terbaru'; ?>
                </a>
            </div>

            <!-- Tabel Data -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Id</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-user mr-2"></i>Pelapor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-calendar mr-2"></i>Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-file-alt mr-2"></i>Isi Laporan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-info-circle mr-2"></i>Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-info-circle mr-2"></i>Jenis Pengaduan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <i class="fas fa-info-circle mr-2"></i>Respon
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php 
                        while($row = mysqli_fetch_assoc($result)): 
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors cursor-pointer">
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['id_pengaduan']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['nama_pelapor']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo date('d/m/Y', strtotime($row['tgl_pengaduan'])); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($row['isi_laporan']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $statusClass = '';
                                $statusIcon = '';
                                switch($row['status']) {
                                    case 'Pending':
                                        $statusClass = 'text-yellow-600 bg-yellow-100';
                                        $statusIcon = 'clock';
                                        break;
                                    case 'Proses':
                                        $statusClass = 'text-blue-600 bg-blue-100';
                                        $statusIcon = 'spinner';
                                        break;
                                    case 'Selesai':
                                        $statusClass = 'text-green-600 bg-green-100';
                                        $statusIcon = 'check-circle';
                                        break;
                                }
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
                                    <i class="fas fa-<?php echo $statusIcon; ?> mr-1"></i>
                                    <?php echo htmlspecialchars($row['status']); ?>                                
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['jenis_pengaduan']); ?></td> <!-- Jenis Pengaduan -->
                            <td class="px-6 py-4 whitespace-nowrap">
    <?php if ($row['status'] == 'Selesai'): ?>
        <span class="px-11 inline-flex text-xs leading-5 fo ">
            <i class="fas fa-check-circle mr-1 text-2xl"></i>        
        </span>
    <?php elseif ($row['status'] == 'Proses'): ?>
        <a href="indexoperator.php?page=selesaitanggapan&id=<?php echo $row['id_pengaduan']; ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring focus:ring-green-300 transition-all duration-200 transform hover:scale-105">
            <i class="fas fa-check-circle mr-2"></i>
            Selesaikan
        </a>
    <?php else: ?>
        <a href="indexoperator.php?page=respon&id=<?php echo $row['id_pengaduan']; ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 transition-all duration-200 transform hover:scale-105">
            <i class="fas fa-reply mr-2"></i>
            Respon
        </a>
    <?php endif; ?>
</td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
    </html>