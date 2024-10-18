<div class="page">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Petugas</title>
    <style>
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 30px;
        }
        form.register {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 15px;
            color: #555;
            font-weight: 600;
        }
        input[type="text"], input[type="password"], input[type="email"] {
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, input[type="password"]:focus, input[type="email"]:focus {
            border-color: #4CAF50;
            outline: none;
        }
        input[type="submit"] {
            margin-top: 30px;
            padding: 14px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        select {
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        select:focus {
            border-color: #4CAF50;
            outline: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pendaftaran Petugas</h2>
        <form class="register" action="" method="POST">
            <label for="id_petugas">ID Admin:</label>
            <input type="text" id="id_petugas" name="id_petugas" required>
            
            <label for="nama_petugas">Nama Lengkap:</label>
            <input type="text" id="nama_petugas" name="nama_petugas" required>
            
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        
            <label for="telp">Nomor Telepon:</label>
            <input type="text" id="telp" name="telp" required>

            <label for="level">Level:</label>
            <select id="level" name="level" required>
                <option value="">Pilih Level</option>
                <option value="admin">Admin</option>
                <option value="petugas">Petugas</option>
            </select>
            
            <input type="submit" value="Daftar">
        </form>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "pengaduandigital";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Koneksi gagal: " . $conn->connect_error);
            }

            $id_petugas = $conn->real_escape_string($_POST['id_petugas']);
            $nama_petugas = $conn->real_escape_string($_POST['nama_petugas']);
            $username = $conn->real_escape_string($_POST['username']);
            $password = $conn->real_escape_string($_POST['password']);
            $telp = $conn->real_escape_string($_POST['telp']);
            $level = $conn->real_escape_string($_POST['level']);

            $sql = "INSERT INTO petugas (id_petugas, nama_petugas, username, password, telp, level) VALUES ('$id_petugas', '$nama_petugas', '$username', '$password', '$telp', '$level')";

            if ($conn->query($sql) === TRUE) {
                    echo "<div class='message success'>Pendaftaran berhasil!</div>";
                    echo "<script>
                    window.location.href = 'index.php?page=loginpetugas';
                    </script>";
            } else {
                echo "<div class='message error'>Error: " . $sql . "<br>" . $conn->error . "</div>";
            }

            $conn->close();
        }
        ?>
    </div>
</body>
</div>
