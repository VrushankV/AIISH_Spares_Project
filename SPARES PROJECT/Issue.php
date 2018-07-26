<?php 
session_start();
if (isset($_SESSION['email'])) {
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Issue of Spares | AIISH</title>
		<link rel="stylesheet" type="text/css" href="VerticalTabs.css">
		<script type="text/javascript" src="VerticalTabs.js"></script>
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
						<li><a href="Issue.php" style="background-color: #bec1c6;">Issue</a></li>
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
			<h1>Issue of Spares</h1>
		</div>
		<form method="post" action="paySlipGen.php">

			<div class="container">
				<label for="name">Name:</label>
				<input type="text" name="name" id="name" required><br>

				<label for="caseNo">Case No. :</label>
				<input type="text" name="caseNo" id="caseNo"><br>

				<label for="others">Other Costs:</label>
				<input type="text" name="Others" id="others" value="0">
			</div>


			<?php
			echo '<h2>Select the Devices From the list below:</h3>
			<div class="tab">';

			$conn = mysqli_connect("localhost", "root", "", "spares_project");

			$deviceQuery = "SELECT DISTINCT Device FROM spares ORDER BY Device";

			$deviceResult  = mysqli_query($conn,$deviceQuery);

			while ($device = mysqli_fetch_array($deviceResult)) {
				$dev = str_replace("'", "^", $device['Device']);
				echo '<button type="button" class="tablinks" onclick="openCity(event,\''.$dev.'\')">'.$device["Device"].'</button>';
			}
			echo '</div>
			<div>';

			$conn = mysqli_connect("localhost", "root", "", "spares_project");

			$dQuery = "SELECT DISTINCT Device FROM spares ORDER BY Device";
			$dResult = mysqli_query($conn,$dQuery);

			while ($d = mysqli_fetch_array($dResult)) {

				$device=$d['Device'];

				$dev= str_replace("'","''",$device);
				$devi = str_replace("'","^",$device);;
				echo '<div id="'.$devi.'" class="tabcontent">
				<h2>'.$d['Device'].'</h2>';


				$companyQuery = "SELECT DISTINCT Company FROM spares WHERE Device='$dev'";
				$companyResult = mysqli_query($conn,$companyQuery);

				while ($company = mysqli_fetch_array($companyResult)) {
					$comp = $company['Company'];
					$c = $comp;
					$comp = str_replace("'","''", $comp);
					$checkQuery = "SELECT Quantity FROM spares WHERE Device='$dev' AND Company='$comp'";
					$checkResult = mysqli_query($conn, $checkQuery);
					while ($row = mysqli_fetch_array($checkResult)) {
						if ($row['Quantity']!=0) {
							if ($company['Company'] != "null") {
								echo '<h3>'.$company['Company'].'</h3><br>';
								break;
							}
						}
					}


					

					$modelQuery = "SELECT Model,Price,Quantity FROM spares WHERE Device='$dev' AND Company='$comp'";
					$modelResult = mysqli_query($conn,$modelQuery) or die("Failed");
					while ($model = mysqli_fetch_array($modelResult)) {
						$mod = str_replace("'","''", $model['Model']);
						$modell = $mod;
						if ($model['Model'] == "null") {
							$mod = $device;
						}
						$maxQty = $model['Quantity'];
						$price = $model['Price'];

						$Value = $device.'|'.$c.'|'.$model['Model'].'|'.$price;


						$id = $mod.' (Rs.'.$price.')';
						$id2= $mod.' (Rs.'.$price.'))';
						if ($maxQty!=0) {
							echo '<div style="width:90%;">
							<input type="checkbox" id="'.$id.'" onchange="cart()" class="form-check-input" name="checkboxes[]" value="'.$Value.'">
							<label for="'.$id.'">'.$mod.' -- (Rs.'.$price.')</label>
							</div>
							<div class="input-group number-spinner" style="width:20%;">
							<input type="number" onchange="cart()" id="'.$id2.'" name="'.$Value.'" class="form-control text-center" value="0" min="0" max="'.$maxQty.'">
							</div><br>';
						}

					}
				}
				echo '<input type="submit" name="submit" value="Generate Pay Slip!" style="width: 35%; background-color: #b3b3cc;" class="form-control"><br><br></div>';
			}
			?>

		</div>
	</form>
	<div class="Cart">
		<h2>Cart</h2>

		<ol id="list">

		</ol>
	</div>
	<script type="text/javascript">
		function cart(){
			var cb = document.getElementsByClassName('form-check-input');
			var fList = document.getElementById("list");
			fList.innerHTML='';

			for (var i =0;i< cb.length;i++) {
				if (cb[i].checked == true) {
					var val = cb[i].id;
					var name = document.getElementById(val+')');
					var fVal = 	name.value;

					var node = document.createElement("LI");
					var textnode = document.createTextNode(cb[i].id+' ----> '+fVal);
					node.appendChild(textnode);
					fList.appendChild(node);
				}
			}
		}

	</script>
</body>
</html>
<?php }else{
	header("Location:login.html");
} 
?>