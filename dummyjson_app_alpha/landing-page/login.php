<?php
require_once '../backend/auth.php';
require_once '../backend/database.php';

if (isLoggedIn()) {
    header("Location: ../resources/dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($login) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id, full_name, username, password FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $login, $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                header("Location: ../resources/dashboard.php");
                exit();
            } else {
                $error = "Invalid username/email or password.";
            }
        } else {
            $error = "Invalid username/email or password.";
        }
        $stmt->close();
        $conn->close();
    }
}

$redirect_msg = htmlspecialchars($_GET['error'] ?? '');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — ValoShop</title>
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
            justify-content: center;
            align-items: center;
            padding: 0 120px;

            background: url("../assets/background 2.png") no-repeat center center/cover;

            overflow: hidden;
        }

        body>div:last-child>div {
            align-items: center;
            width: 500px;

            min-height: 550px;

            background: #f1f1f1;

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
            margin-top: 25px;
            text-align: center;
            color: #666;
            margin-bottom: 25px;
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

            transition: 0.3s;
        }

        button:hover {
            background: #e63b4a;
        }

        a {
            color: #000000;
            text-decoration: none;
            font-weight: bold;
        }

        body div div div {
            margin-bottom: 15px;
            color: red;
            font-size: 14px;
            text-align: center;
        }
    </style>

    <div>
        <div>
            <h2>LOGIN</h2>
            <p>Sign in to access your dashboard</p>

            <?php if ($redirect_msg): ?>
                <div><?= $redirect_msg ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div>
                    <label>Username or Email</label>
                    <input type="text" name="login" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>"
                        placeholder="Username or Email" required autofocus>
                </div>
                <div>
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter password" required>
                </div>
                <button type="submit" id="loginBtn">LOGIN</button>
            </form>

            <p>
                No account yet? <a href="register.php">Register</a>
            </p>

            <p>
                <a href="index.php">Back to home</a>
            </p>
        </div>
    </div>
</body>

</html>