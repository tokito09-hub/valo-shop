<?php
require_once '../backend/auth.php';
if (isLoggedIn()) {
    header("Location: ../resources/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>VALO SHOP</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;

            background: #000000;
            overflow: hidden;

        }

        .welcome {
            font-family: 'Valorant Font.ttf';
            position: absolute;
            top: 300px;
            left: 50%;
            transform: translateX(-50%);

            margin-top: 50px;
            color: white;
            font-size: 50px;
            font-weight: bold;
            letter-spacing: 5px;

            text-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
        }

        .welcome2 {
            position: absolute;
            top: 370px;
            left: 50%;
            transform: translateX(-50%);

            margin-top: 50px;
            color: white;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;

            text-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
        }

        .welcome3 {
            position: absolute;
            top: 400px;
            left: 50%;
            transform: translateX(-50%);

            margin-top: 50px;
            color: white;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;

            text-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
        }

        .container {
            display: flex;
            gap: 80px;
        }

        .btn1 {
            margin-top: 240px;
            text-decoration: none;
            color: white;
            font-size: 22px;
            letter-spacing: 2px;

            background: #ff4f5e;

            padding: 18px 65px;

            outline: 2px solid rgba(255, 255, 255, 0.5);
            outline-offset: 3px;
            transition: 0.3s ease;

        }

        .btn2 {
            margin-top: 240px;
            text-decoration: none;
            color: white;
            font-size: 22px;
            letter-spacing: 2px;

            background: #ff4f5e;

            padding: 18px 40px;

            outline: 2px solid rgba(255, 255, 255, 0.5);
            outline-offset: 3px;
            transition: 0.3s ease;

        }

        .btn1:hover {
            background-color: white;
            color: black;
        }

        .btn2:hover {
            background-color: white;
            color: black;
        }

        .bg-video {
            position: fixed;
            top: 0;
            left: 0;

            width: 100%;
            height: 100%;

            object-fit: cover;

            z-index: -1;
        }

        @font-face {
            font-family: 'Valorant Font.ttf';
            src: url('../assets/Valorant Font.ttf') format('truetype');
        }

        @media(max-width:768px) {

            body {
                padding: 30px 20px;
            }

            body>div>div:first-child h1 {
                font-size: 42px;
            }

            #postSearch {
                width: 100%;
            }

            .post-card {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <video autoplay muted loop playsinline class="bg-video">
        <source src="../assets/valo.mp4" type="video/mp4">
    </video>

    <h1 class="welcome">WELCOME TO VALOSHOP</h1>
    <h3 class="welcome2">A web-based application that integrates DummyJSON API</h3>
    <h3 class="welcome3">to display and manage e-commerce related data</h3>

    <div class="container">
        <a href="login.php" class="btn1">LOGIN</a>
        <a href="register.php" class="btn2">REGISTER</a>
    </div>

</body>

</html>