<?php
$serverName = "localhost"; // or "localhost\\SQLEXPRESS"
$connectionOptions = [
    "Database" => "baloonDBv7", // your database name
    "Uid" => "",
    "PWD" => ""
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}
?>
