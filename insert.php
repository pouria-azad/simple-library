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
    <h1>Insert Form</h1>
    <?php
    if (isset($_SESSION['username'])){
        header('location:index.php');
    }
    global $db_hostname, $db_port, $db_database, $db_username, $db_password;
    include ('config.php');

    ?>

    <form action='index.php' method='post' enctype='multipart/form-data'>

        <input type='file' id='fileToUpload' name='fileToUpload'  onchange='check_file_type();'>

        <input type='text' id='name' name='name' placeholder='نام کتاب'>

        <input type='text' id='writer' name='writer' placeholder='نام نویسنده'>

        <input type='text' id='age' name='age' placeholder='تاریخ انتشار'>


        <input type='submit' value='درج' id='submit_button' name='submit_button' disabled>
    </form>
</div>
<script src='demo.js'></script>
</body>
</html>