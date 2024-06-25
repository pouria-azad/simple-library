<?php
global $db_hostname, $db_port, $db_database, $db_username, $db_password;
include ('config.php');

try {
    $conn = new PDO("mysql:host=$db_hostname;port=$db_port;dbname=$db_database;charset=utf8", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // حذف فایل از دیتابیس
    $sql = "DELETE FROM upload_images WHERE filename =?";
    $statement = $conn->prepare($sql);
    $statement->execute(array($_GET["filename"]));
    // حذف فایل از پوشه upload
    $file_path = 'uploads/'.$_GET["filename"];
    if (file_exists($file_path)) {
        unlink($file_path);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        echo "فایل ali.jpg در پوشه upload پیدا نشد.";
    }
} catch(PDOException $e) {
    echo "خطا: " . $e->getMessage();
}