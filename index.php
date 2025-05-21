<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <style>
        table { border-collapse: collapse; width: 60%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: center; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
<?php
include 'db_connect.php';

$sql = "SELECT productID, productName, productPrice, productStock FROM Product";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

echo "<h2 style='text-align:center;'>Products</h2><table>
<tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th></tr>";

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo "<tr>
        <td>{$row['productID']}</td>
        <td>{$row['productName']}</td>
        <td>{$row['productPrice']}</td>
        <td>{$row['productStock']}</td>
    </tr>";
}

echo "</table>";
?>
</body>
</html>