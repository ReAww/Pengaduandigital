<!-- Buat file pengaduan.php -->
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pengaduandigital";

$conn = new mysqli($servername, $username, $password, $dbname);


if(isset($_POST['submit'])) {
    $tgl_pengaduan = date( 'Y-m-d');
    $nis = $_SESSION['nis'];
    $isi_laporan = $_POST['isi_laporan'];
    
    // Upload foto
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $fotobaru = date('dmYHis').$foto;
    $path = "assets/images-uploaded".$foto;
    
    if(move_uploaded_file($tmp, $path)) {
        $sql = "INSERT INTO pengaduan (tgl_pengaduan, nis, isi_laporan, foto, status) 
                VALUES ('$tgl_pengaduan', '$nis', '$isi_laporan', '$foto', 'Pending')";
        $query = mysqli_query($conn, $sql);
        
        if($query) {
            echo "<script>window.location='indexsiswa.php?page=saranasended';</script>";
        } else {
            echo "<script>alert('Gagal mengirim pengaduan!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Sarana - Digital School</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f4f6f9;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 2rem;
            animation: fadeIn 0.5s ease;
        }

        .card-header {
            background: #4e73df;
            color: white;
            padding: 1.5rem;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .card-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4a5568;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 3px rgba(78,115,223,0.1);
            outline: none;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #4e73df;
            color: white;
        }

        .btn-primary:hover {
            background: #2e59d9;
            transform: translateY(-2px);
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background: #f8fafc;
            font-weight: 600;
            color: #4a5568;
        }

        tr:hover {
            background: #f8fafc;
        }

        .status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-pending {
            background: #fed7d7;
            color: #c53030;
        }

        .status-process {
            background: #feebc8;
            color: #c05621;
        }

        .status-done {
            background: #c6f6d5;
            color: #2f855a;
        }

        .animate__animated {
            animation-duration: 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Form Pengaduan -->
        <div class="card animate_animated animate_fadeIn">
            <div class="card-header">
                <i class="fas fa-edit me-2"></i> Form Pengaduan Sarana
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="form-label">Isi Laporan</label>
                        <textarea class="form-control" name="isi_laporan" required placeholder="Deskripsikan masalah yang Anda temukan..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Upload Foto</label>
                        <input type="file" class="form-control" name="foto" required accept="image/*">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Pengaduan
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Riwayat Pengaduan -->
        <div class="card animate_animated animate_fadeIn">
            <div class="card-header">
                <i class="fas fa-history me-2"></i> Riwayat Pengaduan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>NIS</th>
                                <th>Isi Laporan</th>
                                <th>Foto</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $nis = $_SESSION['nis'];
                            $query = mysqli_query($conn, "SELECT * FROM pengaduan WHERE nis='$nis' ORDER BY id_pengaduan DESC");
                            while($data = mysqli_fetch_array($query)) {
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= date('d-m-Y', strtotime($data['tgl_pengaduan'])); ?></td>
                                <td><?= $data['nis']; ?></td>
                                <td><?= $data['isi_laporan']; ?></td>
                                <td>
                                    <img src="assets/images-uploaded/<?= $data['foto']; ?>" width="100">
                                </td>
                                <td>
                                    <?php
                                    if($data['status'] == 'Pending') {
                                        echo "<span class='status status-pending'>Pending</span>";
                                    } elseif($data['status'] == 'Proses') {
                                        echo "<span class='status status-process'>Proses</span>";
                                    } else {
                                        echo "<span class='status status-done'>Selesai</span>";
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
        // Animasi smooth scroll
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