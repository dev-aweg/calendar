<?php

session_start();

if (!isset($_SESSION['zalogowany'])) {

    header('Location: index.php');
    exit();
}

$who = $_SESSION['id'];
$events_to_confirm = $_POST['events'];
foreach ($events_to_confirm as $key => $value) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=wozek;charset=utf8', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('INSERT INTO `events` (`date`, `hour`, `user_id`, `column`, `confirmed`)	VALUES(
				:date,
				:hour,
				:user_id,
				:column,
				:confirmed)'); // 1

        $stmt->bindValue(':date', $events_to_confirm[$key]['date']); // 2
        $stmt->bindValue(':hour', $events_to_confirm[$key]['hour']);
        $stmt->bindValue(':user_id', $who);
        $stmt->bindValue(':column', $events_to_confirm[$key]['column']);
        $stmt->bindValue(':confirmed', 0);
        $stmt->execute();
    } catch (PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
}
?>

