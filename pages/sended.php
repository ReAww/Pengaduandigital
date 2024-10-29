<div class="page">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berhasil Dikirim</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: fadeIn 1s ease-in;
        }
        h1 {
            color: #4CAF50;
            margin-bottom: 20px;
        }
        div.container p {
            color: #555;
            font-size: 18px;
            margin: 10px 0;
        }
        .icon {
            font-size: 50px;
            color: #4CAF50;
            margin-bottom: 20px;
            animation: bounce 1s infinite;
        }
        .countdown {
            font-size: 24px;
            color: #FF5733; /* Change color as needed */
            margin-top: 20px;
        }
        button {
            background-color: #4CAF50; /* Green */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        button:hover {
            background-color: #45a049; /* Darker green on hover */
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <i class="fas fa-check-circle icon"></i>
        <h1>Berhasil Dikirim!</h1>
        <p>Mohon Tunggu Responnya 24 Jam</p>
        <p><i class="fas fa-clock"></i> Kami akan segera menghubungi Anda.</p>
        <p style="margin-top:25px;">Close Otomatis dalam <div class="countdown" id="countdown">30</div> detik</p>      
        <button onclick="redirectToSarana()">Tutup</button>  
    </div>

    <script>
        // Countdown timer
        let timeLeft = 30; // Set countdown time in seconds
        const countdownElement = document.getElementById('countdown');

        const countdownInterval = setInterval(function() {
            countdownElement.textContent = timeLeft; // Update countdown display
            timeLeft--; // Decrease time left by 1 second

            if (timeLeft < 0) {
                clearInterval(countdownInterval); // Stop the countdown
                window.location.href = 'indexsiswa.php?page=sarana'; // Redirect to the desired page
            }
        }, 1000); // Update every second

        // Function to redirect to the sarana page
        function redirectToSarana() {
            window.location.href = 'indexsiswa.php?page=sarana'; // Redirect to the desired page
        }
    </script>

</body>
</div>