<?php 
session_start();
if (isset($_SESSION['email'])) {
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Reports | AIISH</title>
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
						<li><a href="Issue.php" >Issue</a></li>
						<li><a href="Reports.php" style="background-color: #bec1c6;">Reports</a></li>
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
			<h1>Reports	</h1>
		</div>
		<div class="container">
			<form method="post">
				<div style="width: 80%;display: table;">
					<div style="display: table-cell;">
						<label>Choose report criteria:</label>
						<select id="selector" name="Report" value="report" class="form-control" onchange="filler()">
							<option selected="selected" value="-- Select an option --">-- Select an option --</option>
							<option value="Device-Wise">Device-Wise</option>
							<option value="Model-Wise">Model-Wise</option>
							<option value="All Transactions">All Transactions</option>
						</select>
					</div>	
					<div style="display: table-cell;">
						<label>Start Date:</label>
						<input type="date" name="StartDate" class="form-control">
					</div>

					<div style="display: table-cell;">
						<label>End Date:</label>
						<input type="date" name="EndDate" class="form-control">
					</div>
					<div id="itemName" style="display: table-cell;display: none;">
						<label>Item:</label>
						<input type="text" name="item" class="form-control">
					</div>		
				</div>
				<input type="submit" name="submit" value="Generate!" class="form-control" style="width: 15%">
			</form>

			<?php 
			$conn = mysqli_connect("localhost", "root", "", "spares_project");
			if (isset($_POST['submit'])) {
				$type = $_POST['Report'];
				$startDate = $_POST['StartDate'];
				$endDate = $_POST['EndDate'];
				$item = $_POST['item'];

				if ($type == "All Transactions") {
					echo '<table class="table table-bordered table-condensed">
					<h3>Indent</h3>
					<tr>
					<th>Device</th>
					<th>Company</th>
					<th>Model</th>
					<th>Transaction Quantity</th>
					<th>Date</th>';

					$queryDate = "SELECT Device,Company,Model,TransactionQuantity,TransactionDate FROM entry WHERE TransactionDate BETWEEN '$startDate' AND '$endDate' ORDER BY TransactionDate";
					$resultDate = mysqli_query($conn, $queryDate) or die("failed");

					while ($row = mysqli_fetch_array($resultDate)) {
						echo '<tr>
						<td>'.$row["Device"].'</td>
						<td>'.$row["Company"].'</td>
						<td>'.$row["Model"].'</td>
						<td>'.$row["TransactionQuantity"].'</td>
						<td>'.$row["TransactionDate"].'</td>';
					}
					echo '</table>';

					echo '<table class="table table-bordered table-condensed">
					<h3>Issue</h3>
					<tr>
					<th>Device</th>
					<th>Company</th>
					<th>Model</th>
					<th>Transaction Quantity</th>
					<th>Date</th>';

					$queryModel = "SELECT Device,Company,Model,TransactionQuantity,TransactionDate FROM sell WHERE TransactionDate BETWEEN '$startDate' AND '$endDate' ORDER BY TransactionDate";
					$resultModel = mysqli_query($conn, $queryModel) or die("failed");

					while ($rowM = mysqli_fetch_array($resultModel)) {
						echo '<tr>
						<td>'.$rowM["Device"].'</td>
						<td>'.$rowM["Company"].'</td>
						<td>'.$rowM["Model"].'</td>
						<td>'.$rowM["TransactionQuantity"].'</td>
						<td>'.$rowM["TransactionDate"].'</td>';
					}
					echo '</table>';
				}
				elseif ($type == "Device-Wise") {
					$deviceQuery = "SELECT DISTINCT Device FROM sell  WHERE TransactionDate BETWEEN '$startDate' AND '$endDate' ORDER BY Device";
					$deviceResult = mysqli_query($conn, $deviceQuery);
					echo '<table class="table table-bordered table-condensed">
					<h3>Devices</h3>
					<tr>
					<th>Name of The Device</th>
					<th>Quantity Issued</th>
					</tr>';

					while ($sDevice = mysqli_fetch_array($deviceResult)) {
						$device = $sDevice['Device'];
						$devic = str_replace("'", "''", $device);
						$quantityQuery = "SELECT TransactionQuantity FROM sell WHERE Device = '$devic' AND (TransactionDate BETWEEN '$startDate' AND '$endDate')";
						$quantityResult = mysqli_query($conn, $quantityQuery);
						$total = 0;
						while ($quantity = mysqli_fetch_array($quantityResult)) {
							$total += $quantity["TransactionQuantity"];
						}

						echo '<tr>
						<td>'.$device.'</td>
						<td>'.$total.'</td>
						</tr>';
					}
					echo '</table>';
				}
				elseif ($type == "Model-Wise"){
					if ($item !="") {
						$item = str_replace("'", "''", $item);
						$modelQuery = "SELECT DISTINCT Model,Price FROM sell  WHERE Model ='$item' AND (TransactionDate BETWEEN '$startDate' AND '$endDate') ORDER BY Model";
						$modelResult = mysqli_query($conn, $modelQuery);
						echo '<table class="table table-bordered table-condensed">
						<h3>Models</h3>
						<tr>
						<th>Name of The Model</th>
						<th>Quantity Issued</th>
						</tr>';

						while ($sModel = mysqli_fetch_array($modelResult)) {
							$model= $sModel['Model'];
							$price = $sModel['Price'];
							$mode = str_replace("'", "''", $model);
							$pric = str_replace("'", "''", $price);
							$quantityQuery = "SELECT TransactionQuantity FROM sell WHERE Model='$mode' AND Price ='$pric' AND (TransactionDate BETWEEN '$startDate' AND '$endDate')";
							$quantityResult = mysqli_query($conn, $quantityQuery);
							$total = 0;
							while ($quantity = mysqli_fetch_array($quantityResult)) {
								$total += $quantity["TransactionQuantity"];
							}

							echo '<tr>
							<td>'.$model.' (Rs.'.$price.')</td>
							<td>'.$total.'</td>
							</tr>';
						}
						echo '</table>';
					}
					else{
						$modelQuery = "SELECT DISTINCT Model,Price FROM sell WHERE TransactionDate BETWEEN '$startDate' AND '$endDate' ORDER BY Model";
						$modelResult = mysqli_query($conn, $modelQuery);
						echo '<table class="table table-bordered table-condensed">
						<h3>Models</h3>
						<tr>
						<th>Name of The Model</th>
						<th>Quantity Issued</th>
						</tr>';

						while ($sModel = mysqli_fetch_array($modelResult)) {
							$model= $sModel['Model'];
							$price = $sModel['Price'];
							$mode = str_replace("'", "''", $model);
							$pric = str_replace("'", "''", $price);

							$quantityQuery = "SELECT TransactionQuantity FROM sell WHERE Model='$mode' AND Price ='$pric' AND (TransactionDate BETWEEN '$startDate' AND '$endDate')";
							$quantityResult = mysqli_query($conn, $quantityQuery);
							$total = 0;
							while ($quantity = mysqli_fetch_array($quantityResult)) {
								$total += $quantity["TransactionQuantity"];
							}

							echo '<tr>
							<td>'.$model.' (Rs.'.$price.')</td>
							<td>'.$total.'</td>
							</tr>';
						}
						echo '</table>';
					}

				}
			}
			?>


		</div>
		<script type="text/javascript">
			function filler(){
				var value = document.getElementById('selector').value;
				if (value == "All Transactions" || value == "Device-Wise" || value == "-- Select an option --") {
					document.getElementById('itemName').style.display = "none";
				}
				else {
					document.getElementById('itemName').style.display = "inline";
				}
			}
		</script>
	</body>
	</html>
<?php }else{
	header("Location:login.html");
} 
?>