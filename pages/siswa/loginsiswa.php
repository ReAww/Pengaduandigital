<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/7d4bff396b.js" crossorigin="anonymous"></script>
    <title>Login Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;            
        }
    </style>
</head>
<container-login-box class="flex items-center justify-center min-h-full ">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md animate__animated animate__fadeIn">
        <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Login Siswa</h2>
        <form class="space-y-4" action="" method="post">
            <div class="relative">
                <i class="fa fa-user absolute left-3 top-3 text-gray-400"></i>
                <input type="text" id="username" name="username" placeholder="Username" required class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="relative">
                <i class="fa fa-lock absolute left-3 top-3 text-gray-400"></i>
                <input type="password" id="password" name="password" placeholder="Password" required class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="showPassword" onclick="togglePassword()" class="mr-2">
                <label for="showPassword" class="text-sm text-gray-600">Tampilkan password</label>
            </div>

            <input type="submit" value="Login" class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 ease-in-out transform hover:scale-105">
        </form>

        <div class="mt-4 text-center">
            <p class="text-gray-600">Belum punya akun? <a href="index.php?page=registersiswa" class="text-blue-600 hover:underline">Daftar di sini</a></p>
        </div>

        <?php
        // Koneksi ke database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "pengaduandigital";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $conn->real_escape_string($_POST['username']);
            $password = $conn->real_escape_string($_POST['password']);

            $sql = "SELECT * FROM siswa WHERE username = '$username' AND password = '$password'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc(); // Ambil data siswa
                $_SESSION['nis'] = $row['nis']; // Simpan NIS ke dalam session
                echo "<p class='text-green-600 text-center mt-4'>Login berhasil</p>";
                echo "<script>
                window.location.href = 'indexsiswa.php';
                </script>";
            } else {
                echo "<p class='text-red-600 text-center mt-4'>Username atau password salah</p>";
            }
        }

        $conn->close();
        ?>
    </div>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</>
</html>