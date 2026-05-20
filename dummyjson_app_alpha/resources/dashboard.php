<?php
require_once '../backend/auth.php';
requireLogin();
$active = 'dashboard';
$username = htmlspecialchars($_SESSION['username']);
$full_name = htmlspecialchars($_SESSION['full_name']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — DummyJSON App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;

            background:
                linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)),
                url("../assets/bg valo.png") no-repeat center center/cover;

            color: white;
            padding: 40px 80px;
        }

        body>div {
            width: 100%;
        }

        body>div>div:first-child {
            margin-bottom: 30px;
        }

        body>div>div:first-child h2 {
            font-size: 40px;
            margin-bottom: 10px;
        }

        body>div>div:first-child span {
            color: #ff4655;
        }

        body>div>div:nth-child(2) {
            position: absolute;
            top: 40px;
            right: 80px;
        }

        body>div>div:nth-child(2) a {
            color: white;
            text-decoration: none;
            background: #ff4655;
            padding: 14px 30px;
            border-radius: 12px;
            font-weight: bold;
            transition: 0.3s;
        }

        body>div>div:nth-child(2) a:hover {
            background: #e63b4a;
        }

        body>div>div:nth-child(3) {
            margin: 60px 0 40px;
            text-align: center;
        }

        body>div>div:nth-child(3) h1 {
            font-family: 'Valorant Font.ttf';
            color: #ff4655;
            font-size: 80px;
            margin-bottom: 10px;
        }

        body>div>div:nth-child(4) {
            display: flex;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            justify-content: center;
            gap: 75px;
        }

        body>div>div:nth-child(4) a {
            text-decoration: none;
            background: #ff4655;
            border-radius: 10px;
            padding: 25px 180px 25px 25px;
            transition: 0.3s ease;
        }

        body>div>div:nth-child(4) a p {
            color: white;
            transition: 0.3s;
        }

        body>div>div:nth-child(4) a:hover p {
            color: #000000;
        }

        body>div>div:nth-child(4) a:hover {
            background: #ffffff;
        }

        body>div>div:nth-child(4) a div {
            font-size: 55px;
            margin-bottom: 20px;
            color: white;
            transition: 0.3s;
        }

        body>div>div:nth-child(4) a:hover div {
            color: #000;
        }

        body>div>div:nth-child(4) a h3 {
            font-size: 28px;
            margin-bottom: 10px;
            color: white;
            transition: 0.3s;
        }

        body>div>div:nth-child(4) a:hover h3 {
            color: #000;
        }

        body>div>p {
            margin-top: 320px;
            text-align: center;
            color: #ddd;
        }

        body>div>p a {
            color: #ff4655;
            text-decoration: none;
        }

        .top-nav {
            position: absolute;
            top: 40px;
            right: 80px;
            display: flex;
            align-items: center;
            width: auto;
            gap: 40px;
        }

        .nav-left {
            display: flex;
            gap: 25px;
        }

        .nav-right {
            margin-left: 20px;
            background: #ff4655;
            color: white;
            padding: 14px 30px;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .nav-right:hover {
            background-color: #ff4655;
        }

        .top-nav a {
            position: relative;
            text-decoration: none;
            color: white;
            font-weight: bold;
            padding: 10px 5px;
            transition: 0.3s;
        }

        .top-nav a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0%;
            height: 2px;
            background: #ff4655;
        }

        .top-nav a:hover::after {
            width: 100%;
        }

        @font-face {
            font-family: 'Valorant Font.ttf';
            src: url('../assets/Valorant Font.ttf') format('truetype');
        }
    </style>

</head>

<body>
    <div>
        <div>
            <h2>Welcome <span><?= $username ?></span></h2>
            <p>You are logged in as <strong><?= $full_name ?></strong>. Explore the sections below.</p>
        </div>

        <nav class="top-nav">
            <div class="nav-left">
                <a href="products.php" class="<?= $active == 'dashboard' ? 'active' : '' ?>">PRODUCTS</a>
                <a href="carts.php" class="<?= $active == 'products' ? 'active' : '' ?>">CARTS</a>
                <a href="posts.php" class="<?= $active == 'carts' ? 'active' : '' ?>">POST</a>
            </div>

            <div class="nav-right">
                <a href="../landing-page/logout.php" class="logout">Logout</a>
            </div>
        </nav>

        <div>
            <h1>DASHBOARD</h1>
            <p>Quick access to all data sections</p>
        </div>

        <div>
            <a href="products.php">
                <div><i class="fa-solid fa-box"></i></div>
                <h3>PRODUCTS</h3>
                <p>Browse product catalog</p>
            </a>
            <a href="carts.php">
                <div><i class="fa-solid fa-user"></i></div>
                <h3>USERS</h3>
                <p>Users page with Cart data</p>
            </a>
            <a href="posts.php">
                <div><i class="fa-solid fa-file-lines"></i></div>
                <h3>POSTS</h3>
                <p>Browse blog posts</p>
            </a>

        </div>

        <p>
            Data is fetched live from <a href="https://dummyjson.com" target="_blank">dummyjson.com</a>
        </p>
    </div>
</body>

</html>