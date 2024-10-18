<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak - SMKN Negeri 6 Kota Bekasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            color: #333;
            line-height: 1.6;
        }

        .contact-section {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
        }

        h2 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 2rem;
            text-align: center;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .contact-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            font-size: 1.5rem;
            font-weight: 600;
            color: #3498db;
            margin-bottom: 1rem;
        }

        .principal-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
            text-align: center;
        }

        .principal-photo {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .principal-details {
            margin-top: 1rem;
        }

        .principal-name {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .principal-title {
            font-size: 1rem;
            color: #7f8c8d;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .contact-item i {
            font-size: 1.2rem;
            color: #3498db;
            margin-right: 1rem;
            width: 20px;
            text-align: center;
        }

        .map-container {
            width: 100%;
            height: 300px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="contact-section">
        <h2>Hubungi Kami</h2>
        <div class="contact-grid">
            <div class="contact-card">
                <div class="card-header">Informasi Sekolah</div>
                <div class="principal-info">
                    <img src="images/kepsek.png" alt="Foto Kepala Sekolah" class="principal-photo">
                    <div class="principal-details">
                        <div class="principal-name">R. Prawoto Hari Wibowo, M.Pd</div>
                        <div class="principal-title">Kepala SMK Negeri 6 Kota Bekasi</div>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Jl. Kusuma Utara X No.169, RT.004/RW.016, Duren Jaya, Kec. Bekasi Tim., Kota Bks, Jawa Barat 17111</span>
                    </div>
                    
                    <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>(021) 8801386</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>info@smkn6kotabekasi.sch.id</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-globe"></i>
                    <span><a href="https://smkn6kotabekasi.sch.id" target="_blank">smkn6kotabekasi.sch.id</a></span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <span>Senin - Jumat: 07:00 - 15:00</span>
                </div>
            </div>
            <div class="contact-card">
                <div class="card-header">Lokasi Kami</div>
                <iframe class="map-container" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.2773532352224!2d107.00331731476914!3d-6.230523995493517!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e698c71cf827309%3A0xd63b9944d54dc4e1!2sSMK%20Negeri%206%20Kota%20Bekasi!5e0!3m2!1sen!2sid!4v1635774286693!5m2!1sen!2sid" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</body>
</html>