<?php
include 'db_connect.php';

$sql = "SELECT productID, productName, productPrice, productStock FROM Product";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

echo "<h2>Products</h2><table border='1'>
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
