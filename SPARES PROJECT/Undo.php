<?php 
session_start();
if (isset($_SESSION['email'])) {
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Undo Transactions | AIISH</title>
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
						<li><a href="ViewStock.php">View Stock</a></li>
						<li><a href="Issue.php">Issue</a></li>
						<li><a href="Reports.php">Reports</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="Undo.php" style="background-color: #bec1c6;">Undo Transactions</a></li>
						<li><a href="Bulk.php">Bulk Entry</a></li>
						<li><a href="Logout.php">Logout</a></li>
					</ul>	
				</div>
			</div>
		</nav>
		<div class="page-header container">
			<h4 style="float: right;">Welcome! <?php echo $_SESSION['email'] ?></h4>
			<h1>Undo Transactions</h1>
		</div>
		<div class="container">
			<form method="post">
				<label for="serial">Serial No. :</label>
				<input type="text" name="serial" id="serial" class="form-control" style="width: 20%;"><br>
				<input type="submit" name="submit" value="Undo!" class="form-control" style="width: 15%;">
			</form>
			<hr>
		</div>	

		<?php 
		if (isset($_POST['submit'])) {
			$conn = mysqli_connect("localhost", "root", "", "spares_project");
			$serial = $_POST['serial'];

			$query = "SELECT Device,Company,Model,Price,Quantity FROM patient WHERE SerialNo = '$serial'";
			$result = mysqli_query($conn, $query);

			while ($row = mysqli_fetch_array($result)) {
				$device = $row['Device'];
				$company = $row['Company'];
				$model = $row['Model'];
				$price = $row['Price'];
				$quan = $row['Quantity'];

				$selectQuantity = "SELECT Quantity FROM spares WHERE Device ='$device' AND Company = '$company' AND Model = '$model' AND Price='$price'";
				$res = mysqli_query($conn, $selectQuantity)or die("Fail");
				$quanty = mysqli_fetch_array($res);

				$quantity = $quanty['Quantity'];

				$quantity = $quantity + $quan;

				$updateQuantityQuery = "UPDATE spares SET Quantity='$quantity' WHERE Device ='$device' AND Company = '$company' AND Model = '$model' AND Price='$price'";
				$updateResult = mysqli_query($conn, $updateQuantityQuery) or die("Failed");

			}

			$deleteSellQuery = "DELETE FROM sell WHERE SerialNo = '$serial'";
			$deleteSellResult = mysqli_query($conn, $deleteSellQuery) or die("Fa");

			$deletePatientQuery = "DELETE FROM patient WHERE SerialNo = '$serial'";
			$deletePatientResult = mysqli_query($conn, $deletePatientQuery)or die("failll");
			echo "Deleted Successfully!";
		}
		
		?>
	</body>
	</html>
<?php }else{
	header("Location:login.html");
} 
?>