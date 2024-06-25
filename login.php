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
    <h1>Login Form</h1>
    <?php
    if (isset($_SESSION['username'])){
       header('location:index.php');
    }
    global $db_hostname, $db_port, $db_database, $db_username, $db_password;
    include ('config.php');

?>


    <form method="post" action="index.php">
        <label for="username">UserName:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Login">
    </form>
</div>
</body>
</html>