<?php
session_start();

if (isset($_SESSION['zalogowany'])) {

    header('Location: stronagl.php');
    exit();
}

require_once "connect.php";

$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
}
$new_name = $_POST['new_name'];
$email = $_POST['email'];
$password = $_POST['password'];
$password_confirm = $_POST['password-confirmation'];
$resource = $polaczenie->query("SELECT * FROM uzytkownicy WHERE email='$email'");
$ilu_userow = $resource->num_rows;
if ($ilu_userow > 0) {
    $_SESSION['errors']['email_taken'] = "Ten e-mail jest już przypisany.";
    header('Location:createaccount.php');
    exit();
}

if ($password !== $password_confirm) {
    $_SESSION['errors']['wrong_passes'] = "Hasła nie są identyczne.";
    header('Location:createaccount.php');
    exit();
}
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
function checkEmail(string $email)
{
    return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);

}

if (checkEmail($email) === false) {
    $_SESSION['errors']['wrong_email_format'] = "Zły format adresu e-mail";
    header('Location:createaccount.php');
    exit();
}
$add = $polaczenie->query("INSERT INTO uzytkownicy SET nazwa='$new_name', email='$email', haslo='$hashed_password'");
$_SESSION['after_reg'] = "Dziękujemy za rejestrację, zaloguj się";
header('Location: index.php');
