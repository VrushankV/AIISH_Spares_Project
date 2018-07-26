<?php 
session_start();
if (isset($_SESSION['email'])) {
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Add New Spare | AIISH</title>
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
						<li><a href="AddNewSpare.php" style="background-color: #bec1c6;">Add New Spare</a></li>
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
			<h1>Add New Spare</h1>
			<h3>Enter The Details To Add A New Spare</h3>
		</div>

		<div class="container">
			<form method="post" style="width: 50%">
				<label for="device">Device Name:</label>
				<select name="dev" class="form-control" id="selectDev" onchange="deviceDisplay()" style="width: 50%">
					<option selected="selected" value="-- Select an option --">-- Select an option --</option>
					<?php 
					$conn = mysqli_connect("localhost", "root", "", "spares_project");
					$devQuery = "SELECT DISTINCT Device FROM spares ORDER BY Device";
					$devSql = mysqli_query($conn,$devQuery);
					while ($row = mysqli_fetch_array($devSql)) {
						$dev = str_replace("'", "^", $row['Device']);
						echo "<option value ='".$dev."'>".$row['Device']."</option>";
					}
					?>
				</select><br>
				<input type="text" id="device" name="Device" class="form-control" style="width: 50%;" required>
				<br><br>
				<label for="company">Company Name (If no company leave blank):</label>
				<select name="com" id="selectComp" class="form-control" onchange="companyDisplay()" style="width: 50%;">
					<option selected="selected" value="-- Select an option --">-- Select an option --</option>
					<?php 
					$conn = mysqli_connect("localhost", "root", "", "spares_project");
					$comQuery = "SELECT DISTINCT Company FROM spares ORDER BY Company";
					$comSql = mysqli_query($conn,$comQuery);

					while ($row = mysqli_fetch_array($comSql)) {
						$comp = str_replace("'", "^", $row['Company']);
						echo "<option value ='".$comp."'>".$row['Company']."</option>";
					}
					?>
				</select><br>
				<input type="text" id="company" name="Company" class="form-control" style="width: 50%;">
				<br><br>
				<label for="model">Model Name:</label>
				<input type="text" id="model" name="Model" class="form-control" style="width: 50%;" required>
				<br><br>
				<label for="quantity">Initial Quantity:</label>
				<input type="text" id="quantity" name="Quantity" class="form-control" style="width: 50%;" required=""><br>

				<label for="price">Price:</label>
				<input type="text" id="price" name="Price" class="form-control" style="width: 50%;" required><br>		
				<input type="submit" name="submit" value="Submit!" class="form-control" style="width: 25%;">
			</form>
		</div>

		<?php include 'AddSpare.php';?>
		<br><br><br>
		<script type="text/javascript">
			function deviceDisplay(){
				var x = document.getElementById('selectDev').value;
				x = x.replace("^", "'");
				document.getElementById('device').value =x;

			}

			function companyDisplay(){
				var x = document.getElementById('selectComp').value;
				x = x.replace("^", "'");
				document.getElementById('company').value = x;
			}
		</script>
	</body>
	</html>
<?php }else{
	header("Location:login.html");
} 
?>