<?php
$serverName = "localhost";
$connectionOptions = [
    "Database" => "baloonDBv7", 
    "Uid" => "",
    "PWD" => ""
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}
?>
