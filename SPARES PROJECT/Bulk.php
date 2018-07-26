<?php 
session_start();
if (isset($_SESSION['email'])) {
	?>
<!DOCTYPE html>
<html>
<head>
	<title>Bulk Entry | AIISH</title>
	<link rel="stylesheet" type="text/css" href="bootstrap.css">
</head>
<body  style="background-color: #F8F8F8">
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
					<li><a href="Issue.php" >Issue</a></li>
					<li><a href="Reports.php">Reports</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="Undo.php">Undo Transactions</a></li>
					<li><a href="Bulk.php" style="background-color: #bec1c6;">Bulk Entry</a></li>
					<li><a href="Logout.php">Logout</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="page-header container">
		<h4 style="float: right;">Welcome! <?php echo $_SESSION['email'] ?></h4>
		<h1>Bulk Entry</h1>
	</div>
	<div class="container">
		<form method="post">
			<label for="rows">Enter no. of rows: </label>
			<input type="text" name="rows" id="rows" class="form-control" style="width: 15%;">
			<input type="submit" name="submit" value="Submit!" class="form-control" style="width: 15%;">
		</form>
		<br><br>
		<form method="post">
			<?php 
			$conn = mysqli_connect("localhost", "root", "", "spares_project");
			if (isset($_POST['submit'])) {
				$rows=$_POST['rows'];
				echo '<table class="table table-bordered table-condensed">
				<tr>
				<th>No.</th>
				<th>Device</th>
				<th>Company</th>
				<th>Model</th>
				<th>Quantity</th>
				<th>Price</th>
				</tr>';

				for ($i=1; $i <=$rows ; $i++) { 
					echo '<tr>
					<td>'.$i.'</td>
					<td><input type="text" name="device'.$i.'" class="form-control"></td>
					<td><input type="text" name="company'.$i.'" class="form-control"></td>
					<td><input type="text" name="model'.$i.'" class="form-control"></td>
					<td><input type="text" name="quantity'.$i.'" class="form-control"></td>
					<td><input type="text" name="price'.$i.'" class="form-control"></td>
					</tr>';
				}
				echo '</table>
				<input type="text" name="row" value="'.$rows.'" style="display:none;">
				<input type="submit" name="update" value="Insert!" class="form-control" style="width:15%;">';
			}

			?>
		</form>
		<?php 
		$conn = mysqli_connect("localhost", "root", "", "spares_project");
		if (isset($_POST['update'])) {
			$rows = $_POST['row'];
			for ($i=1; $i <= $rows ; $i++) {
				$dname = 'device'.$i;
				$cname = 'company'.$i;
				$mname = 'model'.$i;
				$qname = 'quantity'.$i;
				$pname = 'price'.$i;

				$device = $_POST[$dname];
				$company = $_POST[$cname];
				$model = $_POST[$mname];
				$quantity = $_POST[$qname];
				$price = $_POST[$pname];

				$device = str_replace("'", "''", $device);
				$company = str_replace("'", "''", $company);
				$model = str_replace("'", "''", $model);
				$quantity = str_replace("'", "''", $quantity);
				$price = str_replace("'", "''", $price);

				$minQty = 0.3*$quantity;

				$updateSparesQuery = "INSERT INTO spares (Device,Company,Model,Price,Quantity,IndentDate,MinQty) VALUES ('$device','$company','$model','$price','$quantity',CURDATE(),'$minQty')";
				$updateSparesResult = mysqli_query($conn, $updateSparesQuery)or die("Failed");

				$updateEntryQuery = "INSERT INTO entry (Device,Company,Model,Price,Quantity,TransactionQuantity,TransactionDate) VALUES ('$device','$company','$model','$price','$quantity','$quantity',CURDATE())";
				$updateEntryResult = mysqli_query($conn, $updateEntryQuery)or die("Faileddd");
			}
			echo "Updated Successfully!";
		}
		?>
	</div>
	<br><br>
</body>
</html>
<?php }else{
	header("Location:login.html");
} 
?>