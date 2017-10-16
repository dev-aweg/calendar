<?php
session_start();

if (!isset($_SESSION['zalogowany'])) {

    header('Location: index.php');
    exit();
}
$tb = $_POST;
//print_r($tb);
foreach ($tb['chosen'] as $key => $cotammasz) {
   echo $cotammasz['id']."</br>";
   if ($cotammasz['id'] == 0){
       unset($tb['chosen']["$key"]);
   }
   //foreach ($cotammasz as $cell)
    ///{
      //  echo $cell."</br>";
    //}
}
print_r($tb);
foreach ($tb['chosen'] as $key => $exac_tb) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=wozek;charset=utf8', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('INSERT INTO `events` (`date`, `hour`, `user_id`, `column`, `confirmed`)	VALUES(
				:date,
				:hour,
				:user_id,
				:column,
				:confirmed)');

        $stmt->bindValue(':date', $exac_tb['date']);
        $stmt->bindValue(':hour', $exac_tb['hour']);
        $stmt->bindValue(':user_id', $exac_tb['id']);
        $stmt->bindValue(':column', $exac_tb['column']);
        $stmt->bindValue(':confirmed', 1);
        $stmt->execute();
    } catch (PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
}
?>
