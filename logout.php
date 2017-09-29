<?php

session_start();

if (isset($_SESSION['zalogowany'])) {
    header('Location: index.php');
    session_destroy();

    exit();
}
?>