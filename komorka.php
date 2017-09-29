<?php
session_start();

if (!isset($_SESSION['zalogowany'])) {

    header('Location: index.php');
    exit();
}

require_once "connect.php";

$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
}
$matrix = range(12, 16);
$matrix = array_flip($matrix);
$matrix = array_map(function () {
    return [
        '1' => null,
        '2' => null,
    ];
}, $matrix);
if ($rezultat = $polaczenie->query(
    sprintf
    ("SELECT * FROM events AS e LEFT JOIN uzytkownicy AS u ON e.uzytkownik = u.id WHERE `data`='%s'",
        mysqli_real_escape_string($polaczenie, $_GET['date'])))) {
    $events = $rezultat->fetch_all(MYSQLI_ASSOC);
    /*print_r($events);
    exit();*/
    foreach ($events as $event) {
        $matrix[$event['godzina']][$event['kolumna']] = $event;
    }
}

?>

<div class="row">
    <div class="col-sm-12">
        <?php foreach ($matrix as $column => $cells): ?>
            <div class="row guttersmall">
                <div class="col-sm-2">
                    <div class="hours">
                        <?php echo $column ?>
                    </div>
                </div>
                <?php foreach ($cells as $event): ?>
                    <div class="col-sm-5">

                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round text-center">
                                <?php
                                if (!empty($event['nazwa'])) {
                                    list($name, $surname) = explode(" ", $event['nazwa'], 2);
                                    echo $name[0] . ". " . $surname;
                                }
                                ?>
                            </span>
                        </label>
                    </div>
                <?php endforeach ?>

            </div>
        <?php endforeach ?>
    </div>
</div>