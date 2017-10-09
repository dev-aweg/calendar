<?php
session_start();

if (!isset($_SESSION['zalogowany'])) {

    header('Location: index.php');
    exit();
}

$who = $_SESSION['id'];
$tb= $_POST['events'];

try {
    $pdo = new PDO('mysql:host=localhost;dbname=wozek;charset=utf8', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo -> prepare('INSERT INTO `events` (`date`, `hour`, `user_id`, `column`, `confirmed`)	VALUES(
				:date,
				:hour,
				:user_id,
				:column,
				:confirmed)');	// 1

    $stmt -> bindValue(':date', $tb[0]['date']); // 2
    $stmt -> bindValue(':hour', $tb[0]['hour']);
    $stmt -> bindValue(':user_id', $who);
    $stmt -> bindValue(':column', $tb[0]['column']);
    $stmt -> bindValue(':confirmed', 0);
    $stmt -> execute();
} catch (PDOException $e) {
    echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
}

?>

