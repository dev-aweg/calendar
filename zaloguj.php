<?php

session_start();

if ((!isset($_POST['username'])) || (!isset($_POST['password']))) {
    header('Location: index.php');
    exit();
}

$login = $_POST['username'];
$haslo = $_POST['password'];
$login = htmlentities($login, ENT_QUOTES, "UTF-8");
try {
    $pdo = new PDO('mysql:host=localhost;dbname=wozek;charset=utf8', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM `uzytkownicy` WHERE `email`='$login'");
    $stmt->execute();
    $count_users = $stmt->rowCount();
} catch (PDOException $e) {
    echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
}
if ($count_users > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (password_verify($haslo, $row['haslo'])) {
        $_SESSION['zalogowany'] = true;


        $_SESSION['name'] = $row['name'];
        $_SESSION['id'] = $row['id'];

        unset($_SESSION['blad']);
        //$stmt->free_result();

        if ($row['id'] === 20) {
            header('Location: admin_panel.php');
        } else {
            header('Location: land_page.php');
        }
    }
} else {

    $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
    header('Location: index.php');
}
