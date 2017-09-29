<?php
$s = [
    1 => "tak",
    2 => "nie",
    3 => "niewiem",
];
$event=[
  "nazwa"=> "Amanda Ściebura",
];
$initials = explode(" ",$event['nazwa']);
echo $initials[0][0].". ".$initials[1];
?>