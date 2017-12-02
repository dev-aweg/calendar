<?php
session_start();
if (isset($_SESSION['zalogowany'])) {

    header('Location: land_page.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

        <title>Rejestracja</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css"
              integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <style>
            @media (min-width: 768px) {
                .field-label-responsive {
                    padding-top: .5rem;
                    text-align: right;
                }
            }

            .window {
                background-color: #eef4ff;
                padding: 3% 3% 3% 3%;
                margin: 5% 30% 5% 30%;
                width: 40%;
                height: 50%;
                border: 1px solid #005f85;
                border-radius: 20px;
            }
            .btn-color {
                color: white;
                background-color: #4f5780;
                border-color: #4f5780;
            }
        </style>
    </head>
    <body>
        <div class="window">
            <div class="container" style="width: auto; margin-left: 0; margin-right: 0">
                <form class="form-horizontal" role="form" method="POST" action="zaloguj.php">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-8">
                            <h2>Zaloguj się</h2>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 field-label-responsive">
                            <label for="name">Adres e-mail</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                                    <div class="input-group-addon" style="width: 2.6rem"><i class="fa fa-at"></i></div>
                                    <input type="text" name="username" class="form-control" id="name"  placeholder="twojmail@example.com" required autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 field-label-responsive">
                            <label for="password">Hasło</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group has-danger">
                                <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                                    <div class="input-group-addon" style="width: 2.6rem"><i class="fa fa-key"></i></div>
                                    <input type="password" name="password" class="form-control" id="password" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">                 
                        <div class="col-md-5">
                            <span class="text-danger align-middle">
                                <?php
                                if (isset($_SESSION['after_reg'])) {
                                    echo $_SESSION['after_reg'];
                                    unset($_SESSION['after_reg']);
                                }
                                if (isset($_SESSION['errors'])) {
                                    echo $_SESSION['errors'];
                                }
                                unset($_SESSION['errors']);
                                ?>
                            </span>
                        </div>
                        <div class="col-md-3">
                            <input type="button" class="btn btn-color float-right" value="Załóż konto" onclick="window.location.href = 'createaccount.php'">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-color float-right">
                                Zaloguj
                            </button>
                        </div>
                    </div>
                </form>
            </div
        </div>
    </body>
</html>