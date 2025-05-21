<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<?php
include 'db_connect.php';
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
			<div class="btn-group me-2">
				<!-- <button type="button" class="btn btn-sm btn-outline-secondary">Share</button> -->
				<button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
			</div>
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
							<li class="nav-item">
								<a class="nav-link d-flex align-items-center gap-2" href="#customers">
									<svg class="bi" aria-hidden="true">
										<use xlink:href="#people"></use>
									</svg>
									Customers
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
					$sql = "SELECT P.productName, SUM(OI.itemQuantity * OIP.unitPrice) AS totalSales
							FROM OrderItem OI
							JOIN Product P ON OI.productID = P.productID
							JOIN OrderItem_Price OIP ON OI.productID = OIP.productID
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
										FORMAT(B.billDate, 'yyyy-MM') AS Month,
										SUM(OI.itemQuantity * OIP.unitPrice) AS MonthlyRevenue
									FROM Bill B
									JOIN Orders O ON B.orderID = O.orderID
									JOIN OrderItem OI ON O.orderID = OI.orderID
									JOIN OrderItem_Price OIP ON OI.productID = OIP.productID
									GROUP BY FORMAT(B.billDate, 'yyyy-MM')
									ORDER BY Month;";	

									$stmt = sqlsrv_query($conn, $monthlyRevenueSql);

									$monthlyRevenue = [];
									$months = [];
									while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
										$months[] = $row["Month"];
										$monthlyRevenue[] = $row["MonthlyRevenue"];
									}
						?>

					<h2>Product Sales Chart</h2>
					<canvas id="salesChart" width="400" height="200"></canvas>
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
				<div id="orders" class="content-section" style="display:none;">
					<!-- <h2>Orders</h2> -->


					<?php

					$sql = "SELECT * FROM GetPendingOrders;";
					$stmt = sqlsrv_query($conn, $sql);

					if ($stmt === false) {
						die("<div class='alert alert-danger'>Database error: " . print_r(sqlsrv_errors(), true) . "</div>");
					}

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
						<th>Customer</th>
						<th>Email</th>
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
					<td>{$row['unitPrice']}</td>
					<td>{$row['itemQuantity']}</td>
					<td>{$row['TotalPrice']}</td>
					<td>{$row['customerName']}</td>
					<td>{$row['email']}</td>
					<td>{$row['PaymentMethod']}</td>
					<td>{$row['status']}</td>
				</tr>";
					}
					if (!$hasRows) {
						echo "<tr><td colspan='10' class='text-center'>No pending orders found.</td></tr>";
					}
					echo "</tbody></table></div>";
					?>







				</div>
				<div id="products" class="content-section" style="display:none;">
					<!-- <h2>Products</h2> -->
					<!-- Products table or content here -->



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
										<label for="modal_new_stock" class="form-label">Stock Change (use negative to
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
				<div id="customers" class="content-section" style="display:none;">
					<h2>Customers</h2>
					<h1>hello</h1>
				</div>

			</main>




		</div>
	</div>

	<script>
		document.querySelectorAll('.nav-link').forEach(link => {
			link.addEventListener('click', function (e) {
				e.preventDefault();
				// Remove 'active' from all links
				document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
				this.classList.add('active');
				// Hide all sections
				document.querySelectorAll('.content-section').forEach(div => div.style.display = 'none');
				// Show the selected section
				const section = document.querySelector(this.getAttribute('href'));
				if (section) section.style.display = 'block';
			});
		});
		// Optionally, show the first section by default
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