<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
<div class="container">
    <h1>Registration Form</h1>
    <?php
        global $db_hostname, $db_port, $db_database, $db_username, $db_password;
        include ('config.php');

    // بررسی آیا فرم ارسال شده است
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // دریافت اطلاعات فرم
        $name = $_POST["username"];
        $password = $_POST["password"];

        // هش کردن پسورد
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $conn = new PDO("mysql:host=$db_hostname;port=$db_port;dbname=$db_database;charset=utf8", $db_username, $db_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // آماده سازی و اجرای دستور SQL برای ذخیره اطلاعات کاربر در دیتابیس
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $name);
            $stmt->bindParam(':password', $hashed_password);

            if ($stmt->execute()) {
                echo "User registered successfully!";
            } else {
                echo "Error: " . $stmt->errorInfo()[2];
            }
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="username">UserName:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <input type="submit" value="Register">
    </form>
</div>
</body>
</html>