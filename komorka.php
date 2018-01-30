<?php
session_start();

if (!isset($_SESSION['zalogowany'])) {

    header('Location: index.php');
    exit();
}

require_once "connect.php";

$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
$polaczenie->set_charset("utf8");
if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
}
$date = $_GET['date'];
$matrix = [];
foreach (range(12, 16) as $hour) {
    $matrix[$hour] = [
        '1' => [
            'hour' => $hour,
            'column' => "1",
        ],
        '2' => [
            'hour' => $hour,
            'column' => "2",
        ],
    ];
}

if ($rezultat = $polaczenie->query(
        sprintf
                ("SELECT * FROM events AS e LEFT JOIN uzytkownicy AS u ON e.user_id = u.id WHERE `date`='%s'", mysqli_real_escape_string($polaczenie, $date)))) {
    $events = $rezultat->fetch_all(MYSQLI_ASSOC);
    foreach ($events as $event) {
        if ($event['confirmed'] == 1 && ($event['name'])) {
            list($name, $surname) = explode(" ", $event['name'], 2);
            $event['name'] = $name[0] . ". " . $surname;
            $event['button_class'] = "switch";
            $event['button_status'] = "disabled";
        }
        if ($event['confirmed'] == 0 && ($event['name'])) {
            $event['name'] = 'Oczekujący';
            $event['button_class'] = "waiting";
            $event['button_status'] = "disabled";
        }
        $matrix[$event['hour']][$event['column']] = $event;
    }
}
?>
<HTML LANG="PL">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    </head>
</head>
<body>
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
                            <button class="<?php echo $event['button_class'] ?? "switch" ?>" style="color: black"
                                    data-hour="<?php echo $event['hour'] ?>"
                                    data-column="<?php echo $event['column'] ?>"
                                    data-date="<?php echo $date ?>"
                                    <?php echo $event['button_status'] ?>
                                    onclick="setColor(this)">
                                        <?php echo $event['name']; ?>
                            </button>
                        </div>
                    <?php endforeach ?>

                </div>
            <?php endforeach ?>
        </div>
    </div>
    <div id="footer" class="modal-footer">
        <button id="send" type="button" class="btn float-right">Wyślij prośbę</button>
    </div>
    <div id="koncowy">
    </div>
    <script>
        function setColor(e) {
            var status = e.classList.contains('switch');

            e.classList.add(status ? 'activated' : 'switch');
            e.classList.remove(status ? 'switch' : 'activated');
        }
    </script>
    <script>

        $('#send').click(function () {
            var ms = [];
            var i;
            // var coto = document.getElementsByClassName("activated");
            Array.from(document.getElementsByClassName("activated")).forEach(
                    function (element, index, array) {
                        var $element = $(element);
                        ms.push({
                            date: $element.data("date"),
                            hour: $element.data("hour"),
                            column: $element.data("column")
                        });
                    }
            );
            console.log(ms);
            $.ajax({
                type: "POST",
                url: '/askfor.php',
                data: {
                    events: ms
                },
                success: function () {
                    document.getElementById("modal").innerHTML = "Wysłałeś prośbę, dziękujemy!";
                }
            });
        })
    </script>
</body>
</html>