<?php 
session_start();
if (isset($_SESSION['email'])) {
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Stock Entry| AIISH</title>
		<link rel="stylesheet" type="text/css" href="bootstrap.css">
	</head>
	<script>
		function showCompany(str) {
			console.log(str);
			if (str == "-- Select an option --") {
				var data ="<option value ='-- Select an option --'>-- Select an option --</option>";
				document.getElementById("company").innerHTML = data;
				return;
			} else {
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById("company").innerHTML = this.responseText;
					}
				};

				xmlhttp.open("GET", "FetchCompany.php?q=" + str, true);
				xmlhttp.send();
			}
		}
		function showModel(str) {

			var device = document.getElementById('device').value;
			if (str == "-- Select an option --") {
				var data ="<option value ='-- Select an option --'>-- Select an option --</option>";
				document.getElementById("model").innerHTML = data;
				return;
			} else {
				var string = str+'|'+device;

				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById("model").innerHTML = this.responseText;
					}
				};
				xmlhttp.open("GET", "FetchModel.php?q=" + string, true);
				xmlhttp.send();
			}
		}
	</script>
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
						<li><a href="StockEntry.php" style="background-color: #bec1c6;">Stock Entry</a></li>
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
			<h1>Stock Entry</h1>
			<h3>Select the device to enter a stock</h3>
		</div>
		<div class="container">
			<!-- Fill Device Drop down -->
			<form method="post" style="width: 20%">
				<label for="Dev">Select a Device :</label>
				<select name="Dev" id="device" onchange="showCompany(this.value)" class="form-control" id="sel">
					<option selected="selected" value="-- Select an option --">-- Select an option --</option>
					<?php 
					$conn = mysqli_connect("localhost", "root", "", "spares_project");
					$deviceQuery = "SELECT DISTINCT Device FROM spares ORDER BY Device";
					$deviceSql = mysqli_query($conn, $deviceQuery);
					while ($row = mysqli_fetch_array($deviceSql)) {
						$dev = str_replace("'", "^",$row['Device']);
						echo "<option value ='".$dev."'>".$row['Device']."</option>";
					}
					?>
				</select>
			<!-- END Dev Dropdown -->


			<!-- Company Drop Down -->

				<label for="Company">Select a Company:</label>
				<select name="Company" id="company" onchange="showModel(this.value)" class="form-control">
					<option value="-- Select an option --">-- Select an option --</option>
				</select>
				<input type="text" name="dev" style="display:none;" value="'.$selected.'">

				<label for="Model">Select a Model!:</label>
				<select name="Model" id="model" value="model" class="form-control">
					<option value="-- Select an option --">-- Select an option --</option>
				</select>


				<label for="Quantity">Enter The Indent Quantity</label>
				<input type="text" id="Quantity" name="quantity" class="form-control" style="width:75%;" required>
				<label for="Price">Enter The Price</label>
				<input type="text" id="Price" name="price" class="form-control" style="width:75%;" required>
				<input type="text" name="selectedDev" style="display:none;" value="'.$selectedDev.'">
				<input type="text" name="selectedCom" style="display:none;" value="'.$selectedCom.'">
				<input type="submit" name="model" value="Submit!" style="width:50%;" class="form-control">
			</form>
				<?php
				if (isset($_POST["model"]) ) {
				$sModel = $_POST["Model"];
				$sModel = str_replace("^", "''", $sModel);
				$sDev = $_POST["Dev"];
				$sDev = str_replace("^", "''", $sDev);
				$sCom = $_POST["Company"];
				$sCom = str_replace("^", "''", $sCom);
				$sQuantity = $_POST["quantity"];
				$sPrice = $_POST["price"];
				$fQuantity = 0.3*$sQuantity;
				$sQty = $sQuantity;

				$conn = mysqli_connect("localhost", "root", "", "spares_project");
				$rQuantityQuery = "SELECT Quantity FROM spares WHERE Model ='$sModel' AND Company='$sCom' AND Device='$sDev' AND Price='$sPrice'";
				$rQuantityResult = mysqli_query($conn,$rQuantityQuery);
				$dataRemaining = mysqli_fetch_array($rQuantityResult);
				$sQuantity = $sQuantity + $dataRemaining['Quantity'];
				$alertQuantity = 0.3*$sQuantity;


				$indentQuery= "INSERT INTO spares (Company,Model,Device,Quantity,Price,IndentDate,MinQty) VALUES ('$sCom','$sModel','$sDev','$sQty','$sPrice',CURDATE(),'$fQuantity') ON DUPLICATE KEY UPDATE Quantity='$sQuantity',IndentDate=CURDATE(),MinQty='$alertQuantity'";

				if(mysqli_query($conn,$indentQuery)){
				echo "Quantity Updated in Database";
			}
			else{
			echo "Failed";
		}

		$updateEntryQuery = "INSERT INTO entry (Device,Company,Model,Price,Quantity,TransactionQuantity,TransactionDate) VALUES('$sDev','$sCom','$sModel','$sPrice','$sQuantity','$sQty',CURDATE())";
		$updateEntrySql = mysqli_query($conn, $updateEntryQuery) or die("Failed");

	}

	?>

	<!-- Model Drop Down ends -->

</form>
</div>
<br><br><br>
</body>
</html>
<?php }else{
	header("Location:login.html");
} 
?>