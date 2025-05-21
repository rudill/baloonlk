<?php
$serverName = "localhost";
$connectionOptions = [
    "Database" => "BaloonLK", 
    "Uid" => "",
    "PWD" => ""
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}
?>
