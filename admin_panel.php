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

$current_unix_time = time();
$how_many_days_current = date('t', $current_unix_time);//ilosc dni aktualnego miesiaca
$how_many_days_previous = date('t', mktime(0, 0, 0, date("m", $current_unix_time), 0, date("Y", $current_unix_time))); //sprawdz czy poprzedni miesiac mial 30 czy 31 dni
$first_day_of_month = strftime("%w", mktime(0, 0, 0, date("m", $current_unix_time), 1, date("Y", $current_unix_time)));
$ileodjac = $first_day_of_month - 1;
$first_day_of_calendar = new DateTime(date('Y-m-01'));
$first_day_of_next_month = new DateTime(date('Y-m-01'));
$first_day_of_next_month->modify("+1 month");
$calendar = range(1, $how_many_days_current);
$calendar = array_map(function ($day) {
    return [
        'day' => $day,
        'date' => date(sprintf('Y-m-%02d', $day)),
        'status_color' => '#445261',
    ];
}, $calendar);


for ($i = 0; $i < $ileodjac; $i++) {
    $first_day_of_calendar->modify("-1 day");
    array_unshift($calendar, [
        'day' => $first_day_of_calendar->format('d'),
        'date' => $first_day_of_calendar->format('Y-m-d'),
        'status_color' => '#6f7f94',
    ]);
}

$iledodac = 42 - count($calendar);
for ($i = 1; $i <= $iledodac; $i++) {
    array_push($calendar, [
        'day' => $i,
        'date' => $first_day_of_next_month->format('Y-m-d'),
        'status_color' => '#6f7f94',
    ]);
    $first_day_of_next_month->modify("+1 day");

}

$matrix = array_chunk($calendar, 7);
$current_id = $_SESSION['id'];
try {
    $pdo = new PDO('mysql:host=localhost;dbname=wozek;charset=utf8', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT *, e.id AS event_id FROM events AS e LEFT JOIN uzytkownicy AS u ON e.user_id = u.id WHERE `confirmed`=0");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        list($name, $surname) = explode(" ", $row['name'], 2);
        $row['shortname'] = $name[0] . ". " . $surname;
        $pending_events[] = $row;
    }

    sort($pending_events);
    //print_r($pending_events);
    // die();
    $stmt->closeCursor();
} catch (PDOException $e) {
    echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
}
$remove_icon = "<i class=\"fa fa-minus-square\"></i>";
$add_icon = "<i class=\"fa fa-plus-square\"></i>";
$checked_icon = "<i class=\"fa fa-check-square\"></i>";
$wait_icon = "<i class=\"fa fa-hourglass-o\"></i>";
?>

<!DOCTYPE HTML>
<HTML LANG="PL">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE-edge,chrome=1"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
            integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"
            integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1"
            crossorigin="anonymous"></script>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css"
          integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <style type="text/css">
        .button-icon {
            color: #313e39;
            cursor: pointer;
            width: 16px;
            height: 14px;
        }
        .myeventstatus {
            position: relative;
            display: inline-block;
            width: 90%;
            height: 85%;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ffffff;
            border: 2px solid #d0dae0;
            border-radius: 20px;
        }

        .myeventheader {
            position: relative;
            display: inline-block;
            width: 90%;
            height: 85%;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #a0aeb7;
            border-style: solid;
            border-color: #ffffff;
            border-radius: 20px;
        }

        .myeventtable {
            position: relative;
            display: inline-block;
            width: 90%;
            height: 85%;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #d0dae0;
            border-style: solid;
            border-color: #ffffff;
            border-radius: 20px;
        }

        .waiting {
            position: relative;
            display: inline-block;
            width: 100%;
            height: 85%;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e0d195;
            border: inherit;
            border-radius: 34px;
        }

        .activated {
            position: relative;
            display: inline-block;
            width: 100%;
            height: 85%;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #a9d0ab;
            border: inherit;
            border-radius: 34px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 100%;
            height: 85%;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            color: #000000;
            background-color: #e3e5e8;
            border: 1px solid #ffffff;
            border-radius: 34px;
        }

        .dropdown {
            position: relative;
            display: inline-block;
            width: 100%;
            height: 85%;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            color: #000000;
            background-color: #e3e5e8;
            border: 0px solid #ffffff;
            border-radius: 34px;
        }

        .guttersmall [class*="col-"] {
            padding-left: 2.5px;
            padding-right: 2.5px;
        }

        .guttersmall {
            margin-left: -2.5px;
            margin-right: -2.5px;
        }

        #tabela {
            border-collapse: separate;
            table-layout: fixed;
            width: 100%;
            height: 100%;
            position: center;
        }

        .TheFirstLine td /*naglowek tabeli */
        {
            color: #dedee2;
            font-size: large;
            text-align: center;
            height: 50px;
            border-collapse: collapse;
            background-color: #343a40;
            border-radius: 20px 20px 20px 20px;
        }

        .przycisk {
            width: 100%;
            height: 100%;
            position: relative;

        }

        .cellBtn {
            width: 100%;
            height: 100%;
            border: inherit;
            border-collapse: collapse;
            border-radius: 20px 20px 20px 20px;
            font-weight: 600;
            color: #dedee2;
            font-size: xx-large;
            text-align: center;
        }

        .cellBtn:hover,
        .cellBtn:focus {
            cursor: pointer;
        }

        .komorka td /*pozostale pola tabeli*/
        {
            width: auto;
            height: 150px;
            background-color: #9ea9b2;
            border-radius: 20px 20px 20px 20px;

        }

        .navcollapse {
            border-collapse: collapse;
            border-radius: 20px 20px 20px 20px;
            width: 100%;
        }

        .hours {
            margin: 0 0 2% 0;
            color: #C3D1DE;
            background-color: #343a40;
            font-weight: 600;
            text-align: center;
            border-radius: 20px;
            width: 100%;
        }
    </style>

</head>
<body>

<div style="margin: 0 2% 1% 2%">
    <!-- Just an image -->
    <nav style="background-color: #343a40" class="navbar navbar-light navcollapse">
        <div class="row navcollapse">
            <div class="col-sm-6 text-left" style="color:#dedee2">
                <h3 data-toggle="modal" data-target="#nav" style="cursor: pointer"><i class="fa fa-user-circle"
                                                                                      aria-hidden="true">&nbsp</i><?php echo $_SESSION['name'] ?>
                </h3>
            </div>
            <div class="col-sm-6 text-right">
                <form action="logout.php">
                    <button type="submit" class="btn btn-link btn-sm" style="color:#C3D1DE; cursor: pointer">

                        <i class="fa fa-power-off" aria-hidden="true"></i> Wyloguj
                    </button>
                </form>
            </div>
    </nav>

    <table id="tabela">
        <tr class="TheFirstLine"> <!--naglowek tabeli-->
            <td>PONIEDZIAŁEK</td>
            <td>WTOREK</td>
            <td>ŚRODA</td>
            <td>CZWARTEK</td>
            <td>PIĄTEK</td>
            <td>SOBOTA</td>
            <td>NIEDZIELA</td>
        </tr>

        <?php foreach ($matrix as $row): ?>
            <tr class="komorka">
                <?php foreach ($row as $cell): ?>
                    <td>
                        <div class="przycisk">
                            <button class="cellBtn btn btn-primary" data-toggle="modal" data-target="#exampleModal"
                                    data-date="<?php echo $cell['date']; ?>"
                                    style="background-color: <?php echo $cell['status_color'] ?>"><?php echo $cell['day']; ?></button>
                        </div>
                    </td>
                <?php endforeach ?>
            </tr>
        <?php endforeach ?>
    </table>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="max-width: 600px" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="header" class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div id="modal" class="modal-body">

            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="nav" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="max-width: 450px" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="header" class="modal-title centered" style="text-align: center"><?php if (empty($pending_events)){
                    echo "Brak oczekujących eventów";

                    }else{
                    echo "Lista wszystkich oczekujacych";
                    } ?></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="pending-list" style="visibility: <?php if (empty($pending_events)) echo "hidden" ?? ""?>">
                <div class="row">
                    <div class="col-sm-12" style="text-align: center" id="list-header-container">
                        <div class="row guttersmall">
                            <div class="col-sm-4 myeventheader">Oczekujacy</div>
                            <div class="col-sm-3 myeventheader">Data</div>
                            <div class="col-sm-3 myeventheader">Godzina</div>
                            <div class="col-sm-2"></div>
                        </div>
                        <?php foreach ($pending_events as $row => $this_event): ?>
                            <div class="row guttersmall" id="list-container" style="text-align: center">
                                <div class="col-sm-4 myeventtable"><?php echo $this_event['shortname'] ?></div>
                                <div class="col-sm-3 myeventtable"><?php echo $this_event['date'] ?></div>
                                <div class="col-sm-3 myeventstatus"><?php echo $this_event['hour'] ?></div>
                                <div class="col-sm-2">
                                    <button type="submit"
                                            class="btn btn-link btn-sm  float-left add-pending button-icon"
                                            data-id="<?php echo $this_event['event_id'] ?>">
                                        <?php echo $add_icon ?>
                                    </button>
                                    <button type="submit"
                                            class="btn btn-link btn-sm  float-left remove-pending button-icon"
                                            data-id="<?php echo $this_event['event_id'] ?>">
                                        <?php echo $remove_icon ?>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<script>
    $('.remove-pending').click(function () {
        var $ele = $(this).parent().parent();
        var data = $(this).data();
        console.log(data);
        $.ajax({
            type: "POST",
            url: '/remRequest.php',
            data: data,
            success: function () {
                $.ajax({
                    url: '/admin_panel.php',
                    success: function () {
                        $ele.fadeOut().remove();
                        var cont = document.getElementById('list-container');
                        if (cont == null)  document.getElementById('list-header-container').innerHTML = "Brak oczekujących eventów!";{

                        }
                    }
                });
            }
        })
    });
    $('.add-pending').click(function () {
        var $ele = $(this).parent().parent();
        var data = $(this).data();
        console.log(data);
        $.ajax({
            type: "POST",
            url: '/addRequest.php',
            data: data,
            success: function () {
                $.ajax({
                    url: '/admin_panel.php',
                    success: function () {
                        $ele.fadeOut().remove();
                    }
                });
            }
        })
    });
    $('.cellBtn').click(function () {
        var date = $(this).data('date');
        document.getElementById("header").innerHTML = date;
        $.ajax({
            type: "GET",
            url: '/komorka_admin.php',
            data: {
                date: date
            },
            success: function (response) {
                $('#exampleModal .modal-body').html(response);
            }
        });
    })

</script>
</body>
</html>
