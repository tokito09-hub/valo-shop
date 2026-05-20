<?php
require_once '../backend/auth.php';
require_once '../backend/database.php';

if (isLoggedIn()) {
    header("Location: ../resources/dashboard.php");
    exit();
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($full_name))
        $errors[] = "Full name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Valid email is required.";
    if (empty($username) || strlen($username) < 3)
        $errors[] = "Username must be at least 3 characters.";
    if (strlen($password) < 6)
        $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm)
        $errors[] = "Passwords do not match.";

    if (empty($errors)) {
        $conn = getDBConnection();

        // Check uniqueness
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Email or username is already taken.";
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $ins = $conn->prepare("INSERT INTO users (full_name, email, username, password) VALUES (?, ?, ?, ?)");
            $ins->bind_param("ssss", $full_name, $email, $username, $hashed);
            if ($ins->execute()) {
                $success = "Registration successful. You may now login.";
            } else {
                $errors[] = "Something went wrong. Please try again.";
            }
            $ins->close();
        }
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — ValoShop</title>
</head>

<body>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            padding: 0 120px;
            background: url("../assets/background 2.png") no-repeat center center/cover;
            overflow: hidden;
        }

        .left-text {
            color: white;
        }

        .left-text h1 {
            margin-top: 325px;
            margin-left: 50px;
            font-size: 90px;
            line-height: 0.9;
            font-weight: 900;
            text-transform: uppercase;
        }


        body>div>div {
            margin-left: 325px;
            width: 500px;
            min-height: 650px;
            background: white;
            border-radius: 25px;
            padding: 25px 40px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        h2 {
            text-align: center;
            font-size: 45px;
            margin-bottom: 15px;
            color: #07152d;
            font-weight: 800;
        }

        p {
            text-align: center;
            color: #666;
            margin-bottom: 35px;
        }

        form div {
            margin-bottom: 22px;
        }

        label {
            display: none;
        }

        input {
            width: 100%;
            padding: 18px;
            border: none;
            border-radius: 12px;
            background: #e7e7e7;
            font-size: 18px;
            color: #444;
        }

        input:focus {
            outline: none;
        }

        button {
            width: 100%;
            padding: 18px;
            border: none;
            border-radius: 12px;
            background: #ff4655;
            color: white;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button:hover {
            background-color: #c73440;
            color: #ffffff;
        }

        a {
            color: #030303;
            text-decoration: none;
            font-weight: bold;
        }

        body div div div {
            margin-bottom: 15px;
            color: red;
            font-size: 14px;
        }

        p {
            margin-top: 20px;
        }

        .success-box {
            text-align: center;
            margin-top: 20px;
            animation: fadeIn .5s ease;
        }

        .success-text {
            color: #ff4655;
            font-size: 15px;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .login-btn {
            display: inline-block;
            padding: 14px 30px;
            background: linear-gradient(135deg, #ff4655, #c73440);
            color: white !important;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 8px 20px rgba(255, 70, 85, .35);
            transition: .3s;
        }

        .login-btn:hover {
            background-color: #07152d;
        }

        .p2 a {
            display: inline-block;
            margin-top: 20px;
            color: #07152d;
            transition: .3s;
        }

        .p2 a:hover {
            color: #ff4655;
        }
    </style>

    <div class="left-text">
        <h1>REGISTER AN<br>ACCOUNT</h1>
    </div>

    <div>
        <div>

            <h2>Create Account</h2>
            <p>Join to access the ValoShop dashboard</p>

            <?php if ($success): ?>
                <div class="success-box">
                    <p class="success-text">
                        <?= htmlspecialchars($success) ?>
                    </p>

                    <a href="login.php" class="login-btn">
                        Login Now →
                    </a>
                </div>
            <?php endif; ?>

            <?php foreach ($errors as $e): ?>
                <div><?= htmlspecialchars($e) ?></div>
            <?php endforeach; ?>

            <?php if (!$success): ?>
                <form method="POST">
                    <div>
                        <label>Full Name</label>
                        <input type="text" name="full_name" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>"
                            placeholder="Mang Kanor Dela Cruz" required>
                    </div>
                    <div>
                        <label>Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                            placeholder="Email" required>
                    </div>
                    <div>
                        <label>Username</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                            placeholder="Username" required>
                    </div>
                    <div>
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div>
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" placeholder="Confirm password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            <?php endif; ?>

            <p class="p2">
                <a href="index.php">Back to Home</a>
            </p>
        </div>
    </div>
</body>

</html>