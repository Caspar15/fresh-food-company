<?php
$correctUsername = "admin";
$correctPassword = "password123";

session_start();

$error = ""; // 初始化錯誤信息變量

// 檢查是否已提交表單
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // 驗證用戶名和密碼
    if ($username === $correctUsername && $password === $correctPassword) {
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;
        // 重定向到儀錶板
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "無效的用戶名或密碼"; // 設置錯誤信息
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>生鮮食品公司管理系統 - 登入</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #6dd5ed, #2193b0);
            color: #333;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Noto Sans TC', sans-serif;
        }

        .login-container {
            padding: 40px;
            border-radius: 15px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        .form-group label {
            font-weight: 700;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
        
        .forgot-password {
            text-align: center;
            display: block;
            margin-top: 15px;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-container">
                    <h1 class="text-center mb-4">生鮮食品公司管理系統</h1>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <form id="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="username">用戶名：</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="輸入用戶名">
                        </div>
                        <div class="form-group">
                            <label for="password">密碼：</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="輸入密碼">
                        </div>
                        <div class="form-group text-center">
                            <input type="submit" class="btn btn-primary btn-lg" value="登入">
                        </div>
                        <a href="forgot_password.php" class="forgot-password">忘記密碼？</a>
                    </form>
                    <p class="text-center mt-3">當前時間：<?php echo date('Y-m-d H:i:s'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
