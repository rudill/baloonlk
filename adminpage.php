<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<?php
include 'db_connect.php';

if (isset($_GET['get_customer'])) {
    $customerID = $_GET['get_customer'];
    $sql = "SELECT * FROM dbo.CustomerDetails(?)";
    $params = array($customerID);
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
        echo "<strong>Customer ID:</strong> {$row['customerID']}<br>";
        echo "<strong>Name:</strong> {$row['customerName']}<br>";
        echo "<strong>Email:</strong> {$row['email']}<br>";
        echo "<strong>Address:</strong> {$row['address']}<br>";
        echo "<strong>Contact No:</strong> {$row['customercontactNo']}<br>";
    } else {
        echo "<div class='alert alert-warning'>Customer details not found.</div>";
    }
    exit;
}

?>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
	<meta name="generator" content="Astro v5.7.10">
	<title>Dashboard Template · Bootstrap v5.3</title>
	<link rel="stylesheet" href="dashboard.css" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
		crossorigin="anonymous"></script>

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



	</style>
</head>

<body>



	<header class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow" data-bs-theme="dark">
		<a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="#">Baloon.LK</a>
		<ul class="navbar-nav flex-row d-md-none">
			<li class="nav-item text-nowrap"> <button class="nav-link px-3 text-white" type="button"
					data-bs-toggle="collapse" data-bs-target="#navbarSearch" aria-controls="navbarSearch"
					aria-expanded="false" aria-label="Toggle search"> <svg class="bi" aria-hidden="true">
						<use xlink:href="#search"></use>
					</svg> </button> </li>
			<li class="nav-item text-nowrap"> <button class="nav-link px-3 text-white" type="button"
					data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
					aria-expanded="false" aria-label="Toggle navigation"> <svg class="bi" aria-hidden="true">
						<use xlink:href="#list"></use>
					</svg> </button> </li>
		</ul>
		<div id="navbarSearch" class="navbar-search w-100 collapse">
			<input class="form-control w-100 rounded-0 border-0" type="text" placeholder="Search" aria-label="Search">
		</div>

		<div class="btn-toolbar mb-2 mb-md-0">
			
			<!-- <button
				type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1">
				<svg class="bi" aria-hidden="true">
					<use xlink:href="#calendar3"></use>
				</svg>
				This week
			</button> -->
		</div>
	</header>

	<div class="container-fluid">
		<div class="row">
			<div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
				<div class="offcanvas-md  bg-body-tertiary" tabindex="-1" id="sidebarMenu"
					aria-labelledby="sidebarMenuLabel">
					<div class="offcanvas-header">
						<h5 class="offcanvas-title" id="sidebarMenuLabel">Company name</h5> <button type="button"
							class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"
							aria-label="Close"></button>
					</div>
					<div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
						<ul class="nav flex-column">
							<li class="nav-item">
								<a class="nav-link d-flex align-items-center gap-2 active" href="#dashboard">
									<svg class="bi" aria-hidden="true">
										<use xlink:href="#house-fill"></use>
									</svg>
									Dashboard
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link d-flex align-items-center gap-2" href="#orders">
									<svg class="bi" aria-hidden="true">
										<use xlink:href="#file-earmark"></use>
									</svg>
									Orders
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link d-flex align-items-center gap-2" href="#products">
									<svg class="bi" aria-hidden="true">
										<use xlink:href="#cart"></use>
									</svg>
									Products
								</a>
							</li>
							
							<!-- Add more as needed -->
						</ul>
					</div>

				</div>


			</div>


			<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">



				<div id="dashboard" class="content-section">
					<!-- <h2>Dashboard</h2> -->
					<!-- Dashboard content here -->




					<?php
					// Total sales this month
					$salesMonth = 0;
					$sql = "SELECT SUM(OI.itemQuantity * P.productPrice) AS salesMonth
					FROM Bill B
					JOIN Orders O ON B.orderID = O.orderID
					JOIN OrderItem OI ON O.orderID = OI.orderID
					JOIN Product P ON OI.productID = P.productID
					WHERE FORMAT(O.orderDate, 'yyyy-MM') = FORMAT(GETDATE(), 'yyyy-MM')";
					$stmt = sqlsrv_query($conn, $sql);
					if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$salesMonth = $row['salesMonth'] ?? 0;
					}

					// Number of orders
					$orderCount = 0;
					$sql = "SELECT COUNT(*) AS orderCount FROM Orders";
					$stmt = sqlsrv_query($conn, $sql);
					if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$orderCount = $row['orderCount'] ?? 0;
					}

					// Number of customers
					$customerCount = 0;
					$sql = "SELECT COUNT(*) AS customerCount FROM Customer";
					$stmt = sqlsrv_query($conn, $sql);
					if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$customerCount = $row['customerCount'] ?? 0;
					}

					// Products low in stock (e.g., stock <= 5)
					$lowStock = 0;
					$sql = "SELECT COUNT(*) AS lowStock FROM Product WHERE productStock <= 5";
					$stmt = sqlsrv_query($conn, $sql);
					if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$lowStock = $row['lowStock'] ?? 0;
					}
					?>

					<div class="row my-4">
						<div class="col-md-3 mb-3">
							<div class="card text-bg-primary h-100">
								<div class="card-body">
									<h6 class="card-title">Total Sales This Month</h6>
									<h3 class="card-text">LKR <?php echo number_format($salesMonth, 2); ?></h3>
								</div>
							</div>
						</div>
						<div class="col-md-3 mb-3">
							<div class="card text-bg-success h-100">
								<div class="card-body">
									<h6 class="card-title">Number of Orders</h6>
									<h3 class="card-text"><?php echo $orderCount; ?></h3>
								</div>
							</div>
						</div>
						<div class="col-md-3 mb-3">
							<div class="card text-bg-info h-100">
								<div class="card-body">
									<h6 class="card-title">Number of Customers</h6>
									<h3 class="card-text"><?php echo $customerCount; ?></h3>
								</div>
							</div>
						</div>
						<div class="col-md-3 mb-3">
							<div class="card text-bg-danger h-100">
								<div class="card-body">
									<h6 class="card-title">Products Low in Stock</h6>
									<h3 class="card-text"><?php echo $lowStock; ?></h3>
								</div>
							</div>
						</div>
					</div>







					<?php
					$sql = "SELECT 
							P.productName, 
							SUM(OI.itemQuantity * P.productPrice) AS totalSales
						FROM OrderItem OI
						JOIN Product P ON OI.productID = P.productID
						GROUP BY P.productName
						ORDER BY totalSales DESC";





					$stmt = sqlsrv_query($conn, $sql);

					//$stmtMonthlyRevenue = sqlsrv_query($conn, $monthlyRevenueSql);
					
					$productNames = [];
					$totalSales = [];

					while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$productNames[] = $row['productName'];
						$totalSales[] = $row['totalSales'];
					}
					?>

					<?php
					$monthlyRevenueSql = "SELECT 
											FORMAT(O.orderDate, 'yyyy-MM') AS Month,
											SUM(OI.itemQuantity * P.productPrice) AS MonthlyRevenue
										FROM Bill B
										JOIN Orders O ON B.orderID = O.orderID
										JOIN OrderItem OI ON O.orderID = OI.orderID
										JOIN Product P ON OI.productID = P.productID
										GROUP BY FORMAT(O.orderDate, 'yyyy-MM')
										ORDER BY Month;;";

					$stmt = sqlsrv_query($conn, $monthlyRevenueSql);

					$monthlyRevenue = [];
					$months = [];
					while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$months[] = $row["Month"];
						$monthlyRevenue[] = $row["MonthlyRevenue"];
					}
					?>

					<h2>Product Sales Chart</h2>
					<canvas id="salesChart" width="400" height="150"></canvas>
					<h2>Monthly Revenue</h2>
					<canvas id="monthlyRevenueChart" width="400" height="200"></canvas>

					<script>
						const ctx = document.getElementById('salesChart').getContext('2d');

						new Chart(ctx, {
							type: 'bar',
							data: {
								labels: <?php echo json_encode($productNames); ?>,
								datasets: [{
									label: 'Total Sales (LKR)',
									data: <?php echo json_encode($totalSales); ?>,
									backgroundColor: 'rgba(54, 162, 235, 0.6)',
									borderColor: 'rgba(54, 162, 235, 1)',
									borderWidth: 1
								}]
							},
							options: {
								scales: {
									y: {
										beginAtZero: true
									}
								}
							}
						});
					</script>

					<script>
						const ctx2 = document.getElementById('monthlyRevenueChart').getContext('2d');

						new Chart(ctx2, {
							type: 'line',
							data: {
								labels: <?php echo json_encode($months); ?>,
								datasets: [{
									label: 'Monthly Revenue (LKR)',
									data: <?php echo json_encode($monthlyRevenue); ?>,
									backgroundColor: 'rgba(75, 192, 192, 0.6)',
									borderColor: 'rgba(75, 192, 192, 1)',
									borderWidth: 1
								}]
							},
							options: {
								scales: {
									y: {
										beginAtZero: true
									}
								}
							}
						});
					</script>




				</div>

				<!-- Orders Section with Tabs -->
				<div id="orders" class="content-section" style="display:none;">
					<ul class="nav nav-tabs mb-3" id="orderTabs" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active" id="pending-tab" data-bs-toggle="tab"
								data-bs-target="#pending-orders" type="button" role="tab">Pending</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="accepted-tab" data-bs-toggle="tab"
								data-bs-target="#accepted-orders" type="button" role="tab">Accepted</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="completed-tab" data-bs-toggle="tab"
								data-bs-target="#completed-orders" type="button" role="tab">Completed</button>
						</li>
					</ul>
					<div class="tab-content">
						<!-- Pending Orders Table -->
						<div class="tab-pane fade show active" id="pending-orders" role="tabpanel">
							<?php
							$sql = "SELECT * FROM GetPendingOrders;";
							$stmt = sqlsrv_query($conn, $sql);
							echo "<h2>Pending Orders</h2>";
							echo "<div class='table-responsive'>";
							echo "<table class='table table-striped table-bordered align-middle'>";
							echo "<thead class='table-dark'><tr>
								<th>Order ID</th>
								<th>Date</th>
								<th>Product</th>
								<th>Unit Price</th>
								<th>Quantity</th>
								<th>Total</th>
								<th>Total Order Price</th>
								<th>Customer</th>
								
								<th>Payment</th>
								<th>Status</th>
								
							</tr></thead><tbody>";
							$hasRows = false;
							while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$hasRows = true;
								echo "<tr>
									<td>{$row['orderID']}</td>
									<td>" . ($row['orderDate'] ? $row['orderDate']->format('Y-m-d') : '') . "</td>
									<td>{$row['productName']}</td>
									<td>{$row['productPrice']}</td>
									<td>{$row['itemQuantity']}</td>
									<td>{$row['TotalPrice']}</td>
									<td>{$row['OrderTotalPrice']}</td>
									<td>{$row['customerName']}</td>
									
									<td>{$row['paymentMethod']}</td>
									<td>{$row['status']}</td>
									<td>
										<form method='post' style='display:inline;'>
											<input type='hidden' name='accept_order_id' value='{$row['orderID']}'>
											<button type='submit' class='btn btn-success btn-sm'>Accept</button>
										</form>
									</td>
									
										     </form>
									</td>
									<td>
										<button 
											type=\"button\" 
											class=\"btn btn-info btn-sm\" 
											onclick=\"showCustomerModal('{$row['customerID']}')\">
											View Customer
										</button>
									</td>
									</tr>";
							}
							if (!$hasRows) {
								echo "<tr><td colspan='11' class='text-center'>No pending orders found.</td></tr>";
							}
							echo "</tbody></table></div>";
							?>
							<?php
							if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accept_order_id'])) {
								$orderId = $_POST['accept_order_id'];
								$adminId = 'ADM02'; // Replace with the logged-in admin's ID if available
								$sqlAccept = "EXEC AcceptedOrder @adminid = ?, @orderid = ?";
								$params = array($adminId, $orderId);
								$stmtAccept = sqlsrv_query($conn, $sqlAccept, $params);
								if ($stmtAccept === false) {
									echo "<div class='alert alert-danger'>Failed to accept order: " . print_r(sqlsrv_errors(), true) . "</div>";
								} else {
									echo "<div class='alert alert-success'>Order $orderId accepted!</div>";
								}
							}
							?>


							<!-- Customer Details Modal -->
							<div class="modal fade" id="customerModal" tabindex="-1"
								aria-labelledby="customerModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="customerModalLabel">Customer Details</h5>
											<button type="button" class="btn-close" data-bs-dismiss="modal"
												aria-label="Close"></button>
										</div>
										<div class="modal-body" id="customerModalBody">
											<!-- Customer details will be loaded here -->
										</div>
									</div>
								</div>
							</div>

						</div>
						<!-- Accepted Orders Table -->
						<div class="tab-pane fade" id="accepted-orders" role="tabpanel">
							<?php
							$sql = "SELECT * FROM GetAcceptedOrders;"; // Create this view or procedure for accepted orders
							$stmt = sqlsrv_query($conn, $sql);
							echo "<h2>Accepted Orders</h2>";
							echo "<div class='table-responsive'>";
							echo "<table class='table table-striped table-bordered align-middle'>";
							echo "<thead class='table-dark'><tr>
								<th>Order ID</th>
								<th>Date</th>
								<th>Product</th>
								<th>Unit Price</th>
								<th>Quantity</th>
								<th>Total</th>
								<th>Total Order Price</th>
								<th>Customer</th>
							
								<th>Payment</th>
								<th>Status</th>
								<th>Admin Name</th>
								
							</tr></thead><tbody>";
							$hasRows = false;
							while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$hasRows = true;
								echo "<tr>
									<td>{$row['orderID']}</td>
									<td>" . ($row['orderDate'] ? $row['orderDate']->format('Y-m-d') : '') . "</td>
									<td>{$row['productName']}</td>
									<td>{$row['productPrice']}</td>
									<td>{$row['itemQuantity']}</td>
									<td>{$row['TotalPrice']}</td>
									<td>{$row['OrderTotalPrice']}</td>
								
									<td>{$row['customerName']}</td>
									
									<td>{$row['paymentMethod']}</td>
									<td>{$row['status']}</td>
									<td>{$row['AcceptedBy']}</td>
									<td>
										<form method='post' style='display:inline;'>
											<input type='hidden' name='complete_order_id' value='{$row['orderID']}'>
											<button type='submit' class='btn btn-success btn-sm'>Complete</button>
										</form>
									</td>
								</tr>";
							}
							if (!$hasRows) {
								echo "<tr><td colspan='11' class='text-center'>No accepted orders found.</td></tr>";
							}
							echo "</tbody></table></div>";
							?>

							<?php
							if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_order_id'])) {
								$orderId = $_POST['complete_order_id'];
								$adminId = 'ADM02'; // Replace with the logged-in admin's ID if available
								$sqlComplete = "EXEC CompletedOrder @adminid = ?, @orderid = ?";
								$params = array($adminId, $orderId);
								$stmtAccept = sqlsrv_query($conn, $sqlComplete, $params);
								if ($stmtAccept === false) {
									echo "<div class='alert alert-danger'>Failed to complete order: " . print_r(sqlsrv_errors(), true) . "</div>";
								} else {
									echo "<div class='alert alert-success'>Order $orderId completed!</div>";
								}
							}
							?>


						</div>
						<!-- Completed Orders Table -->
						<div class="tab-pane fade" id="completed-orders" role="tabpanel">
							<?php
							$sql = "SELECT * FROM GetCompletedOrders;"; // Create this view or procedure for completed orders
							$stmt = sqlsrv_query($conn, $sql);
							echo "<h2>Completed Orders</h2>";
							echo "<div class='table-responsive'>";
							echo "<table class='table table-striped table-bordered align-middle'>";
							echo "<thead class='table-dark'><tr>
								<th>Order ID</th>
								<th>Date</th>
								<th>Product</th>
								<th>Unit Price</th>
								<th>Quantity</th>
								<th>Total</th>
								<th>Total Order Price</th>
							
								<th>Customer</th>
								
								<th>Payment</th>
								<th>Status</th>
							</tr></thead><tbody>";
							$hasRows = false;
							while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$hasRows = true;
								echo "<tr>
									<td>{$row['orderID']}</td>
									<td>" . ($row['orderDate'] ? $row['orderDate']->format('Y-m-d') : '') . "</td>
									<td>{$row['productName']}</td>
									<td>{$row['productPrice']}</td>
									<td>{$row['itemQuantity']}</td>
									<td>{$row['TotalPrice']}</td>
									<td>{$row['OrderTotalPrice']}</td>
									<td>{$row['customerName']}</td>
									
									<td>{$row['paymentMethod']}</td>
									<td>{$row['status']}</td>
								</tr>";
							}
							if (!$hasRows) {
								echo "<tr><td colspan='11' class='text-center'>No completed orders found.</td></tr>";
							}
							echo "</tbody></table></div>";
							?>
						</div>
					</div>
				</div>
				<div id="products" class="content-section" style="display:none;">
					<!-- <h2>Products</h2> -->
					<!-- Products table or content here -->



					<div class="card my-4">
						<div class="card-header bg-success text-white">Add Supplier Supply</div>
						<div class="card-body">
							<form method="post">
								<div class="row">
									<div class="col-md-3 mb-2">
										<label for="supply_supplier_id" class="form-label">Supplier ID</label>
										<select class="form-control" name="supply_supplier_id" id="supply_supplier_id"
											required>
											<option value="">Select Supplier</option>
											<option value="101">101 - Kamal Perera</option>
											<option value="102">102 - Dilshan Cooray</option>
											<option value="103">103 - Tech Lanka Pvt Ltd</option>
										</select>
									</div>
									<div class="col-md-3 mb-2">
										<label for="supply_product_id" class="form-label">Product ID</label>
										<input type="number" class="form-control" name="supply_product_id"
											id="supply_product_id" required>
									</div>
									<div class="col-md-3 mb-2">
										<label for="supply_date" class="form-label">Supply Date</label>
										<input type="date" class="form-control" name="supply_date" id="supply_date"
											required>
									</div>
									<div class="col-md-3 mb-2">
										<label for="supply_quantity" class="form-label">Quantity</label>
										<input type="number" class="form-control" name="supply_quantity"
											id="supply_quantity" min="1" required>
									</div>
								</div>
								<button type="submit" name="add_supply" class="btn btn-success mt-2">Add
									Supply</button>
							</form>
							<?php
							if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_supply'])) {
								$supplierID = intval($_POST['supply_supplier_id']);
								$productID = intval($_POST['supply_product_id']);
								$supplyDate = $_POST['supply_date'];
								$quantity = intval($_POST['supply_quantity']);
								$sql = "INSERT INTO supplier_product (supplierID, productID, supplyDate, productQuantity) VALUES (?, ?, ?, ?)";
								$params = array($supplierID, $productID, $supplyDate, $quantity);
								$stmt = sqlsrv_query($conn, $sql, $params);
								if ($stmt) {
									echo "<div class='alert alert-success mt-2'>Supply added and product stock increased by trigger.</div>";
								} else {
									echo "<div class='alert alert-danger mt-2'>Error: " . htmlspecialchars(print_r(sqlsrv_errors(), true)) . "</div>";
								}
							}
							?>
						</div>
					</div>
				


					<?php
					// Handle stock update POST
					$stockMsg = '';
					if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modal_product_id'], $_POST['modal_new_stock'])) {
						$productID = intval($_POST['modal_product_id']);
						$newStock = intval($_POST['modal_new_stock']);
						$sql = "{CALL UpdateProductStock(?, ?)}";
						$params = array($productID, $newStock);
						$stmt = sqlsrv_query($conn, $sql, $params);
						if ($stmt === false) {
							$stockMsg = "<div class='alert alert-danger'>Error: " . htmlspecialchars(print_r(sqlsrv_errors(), true)) . "</div>";
						} else {
							$stockMsg = "<div class='alert alert-success'>Stock updated for Product ID $productID.</div>";
						}
					}

					$sql = "SELECT * FROM ProductStock";
					$stmt = sqlsrv_query($conn, $sql);

					if ($stmt === false) {
						die("<div class='alert alert-danger'>Database error: " . print_r(sqlsrv_errors(), true) . "</div>");
					}

					echo "<h2>Product Stock</h2>";
					if (!empty($stockMsg))
						echo $stockMsg;
					echo "<div class='table-responsive'>";
					echo "<table class='table table-striped table-bordered align-middle'>";
					echo "<thead class='table-dark'><tr>
								<th>Product ID</th>
								<th>Product Name</th>
								<th>Category Type</th>
								<th>Product Stock</th>
								<th>Status</th>
								<th>Action</th>
							</tr></thead><tbody>";

					$hasRows = false;
					while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$hasRows = true;
						$pid = $row['ProductID'];
						echo "<tr>
								<td>{$pid}</td>
								<td>{$row['ProductName']}</td>
								<td>{$row['categoryType']}</td>
								<td>{$row['productStock']}</td>
								<td>{$row['AvailabilityStatus']}</td>
								<td>
									<button class='btn btn-sm btn-primary' onclick='showStockModal($pid)'>Update Stock</button>
								</td>
							</tr>";
					}
					if (!$hasRows) {
						echo "<tr><td colspan='5' class='text-center'>No products found.</td></tr>";
					}
					echo "</tbody></table></div>";
					?>


					<div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel"
						aria-hidden="true">
						<div class="modal-dialog">
							<form method="post" class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="stockModalLabel">Update Product Stock</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal"
										aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<input type="hidden" name="modal_product_id" id="modal_product_id">
									<div class="mb-3">
										<label for="modal_new_stock" class="form-label">Stock Change (use negative
											to
											decrease)</label>
										<input type="number" class="form-control" name="modal_new_stock"
											id="modal_new_stock" required>
									</div>
								</div>
								<div class="modal-footer">
									<button type="submit" class="btn btn-primary">Update</button>
									<button type="button" class="btn btn-secondary"
										data-bs-dismiss="modal">Cancel</button>
								</div>
							</form>
						</div>
					</div>




				</div>
				

			</main>




		</div>
	</div>

	<script>
		document.querySelectorAll('.sidebar .nav-link').forEach(link => {
			link.addEventListener('click', function (e) {
				e.preventDefault();
				// Remove 'active' from all sidebar links
				document.querySelectorAll('.sidebar .nav-link').forEach(l => l.classList.remove('active'));
				this.classList.add('active');
				// Hide all main sections
				document.querySelectorAll('.content-section').forEach(div => div.style.display = 'none');
				// Show the selected section
				const section = document.querySelector(this.getAttribute('href'));
				if (section) section.style.display = 'block';
			});
		});
		// Show the first section by default
		document.querySelector('.content-section').style.display = 'block';
	</script>

	<script>
		// Show modal and set product ID
		function showStockModal(productId) {
			var modal = new bootstrap.Modal(document.getElementById('stockModal'));
			document.getElementById('modal_product_id').value = productId;
			document.getElementById('modal_new_stock').value = '';
			modal.show();
		}
	</script>

	<script>
			function showCustomerModal(customerID) {
				fetch(window.location.pathname + '?get_customer=' + encodeURIComponent(customerID))
					.then(response => response.text())
					.then(html => {
						document.getElementById('customerModalBody').innerHTML = html;
						var modal = new bootstrap.Modal(document.getElementById('customerModal'));
						modal.show();
					});
			}
</script>