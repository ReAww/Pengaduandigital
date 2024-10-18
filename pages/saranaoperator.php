<div class="page">
<style>
    .page {
        display:block;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    h2 {
        text-align: center;
        color: #333;
    }
</style>

<h2>Daftar Siswa</h2>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pengaduandigital";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT nis, nama, username, password, telp FROM siswa";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>NIS</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Password</th>
                <th>Telepon</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row["nis"]."</td>
                <td>".$row["nama"]."</td>
                <td>".$row["username"]."</td>
                <td>".$row["password"]."</td>
                <td>".$row["telp"]."</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "Tidak ada data siswa.";
}

$conn->close();
?>

</div>