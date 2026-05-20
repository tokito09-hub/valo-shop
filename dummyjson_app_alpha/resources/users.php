<?php
require_once '../backend/auth.php';
require_once '../backend/database.php';
requireLogin();
$active = 'users';

$userData = fetchAPI('https://dummyjson.com/users?limit=100');
$users = $userData['users'] ?? [];

$selected_user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : null;
$selected_user = null;
$user_carts = [];

if ($selected_user_id) {
    foreach ($users as $u) {
        if ($u['id'] === $selected_user_id) {
            $selected_user = $u;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users — DummyJSON App</title>
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
            padding: 40px 80px;
            color: white;

            background:
                linear-gradient(rgba(0, 0, 0, 0.78), rgba(0, 0, 0, 0.85)),
                url("../assets/bg valo.png") no-repeat center center/cover;
        }

        body>div>div:first-child {
            text-align: center;
            margin-bottom: 40px;
        }

        body>div>div:first-child h1,
        body>div>div:first-child h2 {
            font-family: 'Valorant Font.ttf';
            font-size: 60px;
            color: #ff4655;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }

        body>div>div:first-child p {
            color: #ddd;
            font-size: 18px;
        }

        a {
            text-decoration: none;
        }

        .top-bar {
            margin-right: 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            gap: 20px;
        }

        .back-btn {
            background: #ff4655;
            color: white;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: bold;
            display: inline-block;
            transition: 0.3s ease;
            white-space: nowrap;
        }

        .back-btn:hover {
            color: black;
        }

        .btn {
            background: #ff4655;
            color: white;
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: bold;
            display: inline-block;
        }

        body>div>div:nth-child(3) {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }

        #userSearch {
            margin-left: 55px;
            width: 500px;
            max-width: 100%;
            padding: 16px 20px;
            border: none;
            outline: solid 2px #ff4655;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }

        #userSearch::placeholder {
            color: #ccc;
        }

        .grid-2 {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }

        .card {
            width: 400px;
            background: rgba(255, 70, 85, 0.9);
            border-radius: 25px;
            padding: 25px;
            border: 1px solid transparent;
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
        }

        .user-info h4 {
            font-size: 24px;
            margin-bottom: 10px;
            color: white;
        }

        .user-info p {
            margin-bottom: 8px;
            color: #ffe5e8;
            transition: 0.3s;

            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 220px;
        }

        .btn-sm {
            padding: 10px 18px;
            font-size: 14px;
            outline: solid 2px #030035;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            overflow: hidden;
            border-radius: 15px;
        }

        thead {
            background: #ff4655;
        }

        thead th {
            padding: 16px;
            text-align: left;
        }

        tbody {
            background: rgba(255, 255, 255, 0.08);
        }

        tbody td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        body>div>div>div {
            margin-bottom: 25px;
        }

        body>div>div>div>div:nth-child(2) {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        body>div>div>div>div:nth-child(2) div {
            background: #ff4655;
            padding: 15px 20px;
            border-radius: 15px;
            min-width: 180px;
            text-align: center;
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        #noUserResults {
            text-align: center;
            padding: 40px;
            color: #ff4655;
            font-size: 20px;
        }

        @font-face {
            font-family: 'Valorant Font.ttf';
            src: url('fonts/Valorant Font.ttf') format('truetype');
        }
    </style>

</head>

<body>
    <div>
        <?php if ($selected_user && $selected_user_id): ?>
            <div>
                <div class="cart-header">
                    <div>
                        <h2>
                            <i class="fa-solid fa-cart-shopping"></i> Cart for
                            <?= htmlspecialchars($selected_user['firstName'] . ' ' . $selected_user['lastName']) ?>
                        </h2>

                        <p><?= htmlspecialchars($selected_user['email']) ?></p>
                    </div>

                    <a href="users.php" class="back-btn">
                        Back to Users
                    </a>
                </div>
            </div>

        <?php else: ?>
            <div>
                <h1>USERS</h1>
                <p>Showing <?= count($users) ?> users — click "View Cart" to see a user's cart</p>
            </div>

            <div class="top-bar">
                <input type="text" id="userSearch" placeholder="Search by name or email…" oninput="filterUsers()">

                <a href="dashboard.php" class="back-btn">Go back</a>
            </div>

            <?php if (empty($users)): ?>
                <div class="alert alert-error">Could not fetch users. Check your internet connection.</div>
            <?php else: ?>
                <div class="grid-2" id="userGrid">
                    <?php foreach ($users as $u): ?>
                        <?php
                        $fname = htmlspecialchars($u['firstName'] ?? '');
                        $lname = htmlspecialchars($u['lastName'] ?? '');
                        $email = htmlspecialchars($u['email'] ?? '');
                        $phone = htmlspecialchars($u['phone'] ?? '');
                        $age = (int) ($u['age'] ?? 0);
                        $img = htmlspecialchars($u['image'] ?? '');
                        $uid = (int) $u['id'];
                        ?>
                        <div class="card" data-search="<?= strtolower("$fname $lname $email") ?>">
                            <div class="user-card">
                                <img src="<?= $img ?>" alt="<?= $fname ?>" class="user-avatar" loading="lazy">
                                <div class="user-info">
                                    <h4><?= $fname ?>             <?= $lname ?></h4>
                                    <p><i class="fa-regular fa-envelope"></i> <?= $email ?></p>
                                    <p><i class="fa-solid fa-phone"></i> <?= $phone ?></p>
                                    <p>Age: <?= $age ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p id="noUserResults" style="display:none; text-align:center; color:var(--text-muted); padding:2rem;">No users
                    match your search.</p>
            <?php endif; ?>
        <?php endif; ?>

    </div>

    <script>
        function filterUsers() {
            const q = document.getElementById('userSearch').value.toLowerCase();
            const cards = document.querySelectorAll('#userGrid .card');
            let visible = 0;
            cards.forEach(card => {
                const match = card.dataset.search.includes(q);
                card.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            document.getElementById('noUserResults').style.display = visible === 0 ? '' : 'none';
        }
    </script>
</body>

</html>