<?php 
session_start();
if (isset($_SESSION['email'])) {
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>View Spares Stock | AIISH</title>
		<link rel="stylesheet" type="text/css" href="bootstrap.css">
	</head>
	<body style="background-color: #F8F8F8">
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="main.php"class="nav navbar-brand">Home</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li><a href="StockEntry.php">Stock Entry</a></li>
						<li><a href="AddNewSpare.php">Add New Spare</a></li>
						<li><a href="PriceFixing.php">Price Fixing</a></li>
						<li><a href="ViewStock.php"style="background-color: #bec1c6;">View Stock</a></li>
						<li><a href="Issue.php" >Issue</a></li>
						<li><a href="Reports.php">Reports</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="Undo.php">Undo Transactions</a></li>
						<li><a href="Bulk.php">Bulk Entry</a></li>
						<li><a href="Logout.php">Logout</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="page-header container">
			<h4 style="float: right;">Welcome! <?php echo $_SESSION['email'] ?></h4>
			<h1>View Current Stock</h1>
			<h3 id="date"></h3>
		</div>
		<div class="container">
			<table class="table table-bordered table-condensed">
				<tr>
					<th>Sl No.</th>
					<th>Device</th>
					<th>Company</th>
					<th>Model</th>
					<th>Price</th>
					<th>Quantity</th>
				</tr>
				<?php 
				$conn = mysqli_connect("localhost", "root", "", "spares_project");
				$deviceQuery = "SELECT Device,Model,Price,Company,Quantity FROM spares ORDER BY Device,Company,Model";

				$deviceSql = mysqli_query($conn, $deviceQuery);

				$i=1;

				while ($rowDevice = mysqli_fetch_array($deviceSql)) {
					if ($rowDevice["Device"]!="") {
						$rowCompany = $rowDevice['Company'];
						$rowModel = $rowDevice['Model'];
						$rowPrice = $rowDevice['Price'];
						$rowQuantity =$rowDevice['Quantity'];
						echo '<tr>
						<td>'.$i.'</td>
						<td>'.$rowDevice["Device"].'</td>
						<td>'.$rowCompany.'</td>
						<td>'.$rowModel.'</td>
						<td>'.$rowPrice.'</td>
						<td>'.$rowQuantity.'</td>
						</tr>';
						$i++;
					}

				}
				?>
			</table>
		</div>
		<br><br><br>

		<script type="text/javascript">
			var d = new Date();
			y = d.getFullYear();
			m = d.getMonth() + 1;
			day = d.getDate();
			t = d.getTime();
			document.getElementById('date').innerHTML = "Date: " + day + "/" + m + "/" + y + " Time: " + d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
		</script>
	</body>
	</html>
<?php }else{
header("Location:login.html");
} 
?>