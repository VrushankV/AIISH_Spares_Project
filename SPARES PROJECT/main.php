<?php 
session_start();
if (isset($_SESSION['email'])) {
	?>

	<!DOCTYPE html>
	<html>
	<head>
		<title>Spares</title>
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
					<a href="main.php"class="nav navbar-brand" style="background-color: #bec1c6;">Home</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li><a href="StockEntry.php">Stock Entry</a></li>
						<li><a href="AddNewSpare.php">Add New Spare</a></li>
						<li><a href="PriceFixing.php">Price Fixing</a></li>
						<li><a href="ViewStock.php">View Stock</a></li>
						<li><a href="Issue.php">Issue</a></li>
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
			<h1 style="color:#ff3333;">Notifications!</h1>
		</div>	
		<div class="container">
			<?php 
			$query = "SELECT Device,Price,MinQty,Quantity,Model FROM spares ORDER BY Model";


			$conn = mysqli_connect("localhost","root","","spares_project");
			$result = mysqli_query($conn, $query);
			$i = 1;
			while ($row = mysqli_fetch_array($result)) {
				if ($row['Model'] != "") {

					$model = $row['Model'];
					$quantity = $row['Quantity'];
					$minQty = $row['MinQty'];
					$price = $row['Price'];
					if ($model =="null") {
						$model=$row['Device'];
					}
					if ($row['Quantity'] <= $row['MinQty']) {
						echo "<div class='alert alert-danger alert-dismissible'>
						<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
						<strong>".$i.".) ".$model." (Rs.".$price.") is running low.</strong> Current Quantity = ".$quantity." And Minimum Quantity = ".$minQty."
						</div>";
						$i++;
					}
				}
			}
			?>
		</div>
		<script  src="https://code.jquery.com/jquery-3.3.1.js"
		integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
		crossorigin="anonymous"></script>
		<script src="bootstrap.js"></script>
	</body>
	</html>
<?php }else{
	header("Location:login.html");
} 
?>