<?php
session_start();

if (!isset($_SESSION['zalogowany'])) {

    header('Location: index.php');
    exit();
}
$tb = $_POST;
$updating_record = $tb['id'];
try {
    $pdo = new PDO('mysql:host=localhost;dbname=wozek;charset=utf8', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("UPDATE `events` SET `confirmed`= 1	WHERE `id`=$updating_record");
    $stmt->execute();
} catch (PDOException $e) {
    echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
}

?>

