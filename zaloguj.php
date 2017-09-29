<?php

session_start();

if ((!isset($_POST['username'])) || (!isset($_POST['password']))) {
    header('Location: index.php');
    exit();
}

require_once "connect.php";

$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
} else {
    $login = $_POST['username'];
    $haslo = $_POST['password'];
    $login = htmlentities($login, ENT_QUOTES, "UTF-8");

    if ($rezultat = $polaczenie->query(
        sprintf
        ("SELECT * FROM uzytkownicy WHERE email='%s'",
            mysqli_real_escape_string($polaczenie, $login)))) {
        $ilu_userow = $rezultat->num_rows;
        if ($ilu_userow > 0) {
            $wiersz = $rezultat->fetch_assoc();
            if (password_verify($haslo, $wiersz['haslo'])) {
                $_SESSION['zalogowany'] = true;


                $_SESSION['user'] = $wiersz['user'];
                $_SESSION['nazwa'] = $wiersz['nazwa'];


                unset($_SESSION['blad']);
                $rezultat->free_result();

                /*	$zapytanie = $polaczenie->query("SELECT * FROM uzytkownicy WHERE godzina='16'");
            $wiersz = $zapytanie->fetch_assoc();
            $_SESSION['pierwsza'] = $wiersz['pierwsza'];
            $_SESSION['druga'] = $wiersz['druga'];
            $_SESSION['trzecia'] = $wiersz['trzecia'];
            $_SESSION['czwarta'] = $wiersz['czwarta'];
            $_SESSION['data'] = $wiersz['data'];
                    $zapytanie->free_result();
            */


                header('Location: stronagl.php');
            }

        } else {

            $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
            header('Location: index.php');

        }
    }
        $polaczenie->close();
}