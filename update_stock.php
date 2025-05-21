
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Product Stock</title>
    <style>
        body { font-family: Arial, sans-serif; }
        form { width: 300px; margin: 40px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        label, input { display: block; width: 100%; margin-bottom: 10px; }
        input[type="submit"] { width: auto; }
        .msg { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <form method="post">
        <h2>Update Product Stock</h2>
        <label for="productID">Product ID:</label>
        <input type="number" name="productID" id="productID" required>
        <label for="newStock">Stock Change (use negative to decrease):</label>
        <input type="number" name="newStock" id="newStock" required>
        <input type="submit" value="Update Stock">
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'db_connect.php';
        $productID = intval($_POST['productID']);
        $newStock = intval($_POST['newStock']);

        $sql = "{CALL UpdateProductStock(?, ?)}";
        $params = array($productID, $newStock);

        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            echo "<div class='msg' style='color:red;'>Error: " . print_r(sqlsrv_errors(), true) . "</div>";
        } else {
            echo "<div class='msg' style='color:green;'>Stock updated successfully for Product ID $productID.</div>";
        }
    }
    ?>
</body>
</html>