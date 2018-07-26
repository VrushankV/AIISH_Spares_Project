<!DOCTYPE html>
<html>
<head>
	<title>PayGen</title>
	<link rel="stylesheet" type="text/css" href="bootstrap.css">
</head>
<body>
	<?php
	$conn = mysqli_connect("localhost", "root", "", "spares_project");
	$name = $_POST['name'];
	$caseNo = $_POST['caseNo'];
	
	if (isset($_POST['Others'])) {
		$others = $_POST['Others'];
	}else{
		$others =0;
	}

	$updateSerialQuery = "SELECT MAX(SerialNo) AS max FROM patient";
	$updateSerialResult = mysqli_query($conn, $updateSerialQuery);

	$serialNo = mysqli_fetch_array($updateSerialResult);
	$serial = $serialNo['max'];
	$serial += 1;

	echo '<div class="container">
	<table class="table table-bordered table-condensed" style="width: 80%;">
	<tr>
	<th colspan="4" style="height:80px;text-align:center;font-size:20px;">All India Institute Of Speech And Hearing,Mysore</th>
	</tr>
	<tr>
	<th colspan="2">Name: '.$name.'</th>
	<th colspan="2">Case No: '.$caseNo.'</th>
	</tr>
	<tr>
	<th colspan="2">Date: '.date("d/m/Y").'</th>
	<th colspan="2">Serial No:'.$serial.'</th>
	</tr>
	<tr>
	<th>Particular</th>
	<th>Rate</th>
	<th>Quantity</th>
	<th>Amount</th>
	</tr>';

	$info = $_POST['checkboxes'];
	
	$totalAmt = 0;


	for ($i=0; $i < sizeof($info); $i++) { 
		$x = $info[$i];
		$info[$i] = str_replace("'", "''", $info[$i]);
		$feature = explode("|", $info[$i]);
		$quantityQuery = "SELECT Quantity FROM spares WHERE Device='$feature[0]' AND Company='$feature[1]' AND Model='$feature[2]' AND Price='$feature[3]'";
		$quantityResult = mysqli_query($conn, $quantityQuery) or die("Failed");

		$q= str_replace(" ", "_", $x);
		$quantity = $_POST[$q];

		$qty = mysqli_fetch_array($quantityResult);
		$amount = $feature[3]*$quantity;

		$mod = $feature[2];
		if ($feature[2] == "null") {
			$mod = $feature[0];
		}
		$totalAmt += $amount;
		echo '<tr>
		<td>'.$mod.'</td>
		<td>'.$feature[3].'</td>
		<td>'.$quantity.'</td>
		<td>'.$amount.'</td>
		</tr>';

		$updateQty = $qty["Quantity"] - $quantity;

		

		$updateQuery = "UPDATE spares SET Quantity='$updateQty' WHERE Device='$feature[0]' AND Company='$feature[1]' AND Model='$feature[2]' AND Price='$feature[3]'";
		$updateResult = mysqli_query($conn, $updateQuery) or die("Failed");
		
		$updateSellQuery = "INSERT INTO sell (SerialNo,Device,Company,Model,Price,Quantity,TransactionQuantity,TransactionDate) VALUES('$serial','$feature[0]','$feature[1]','$feature[2]','$feature[3]','$updateQty','$quantity',CURDATE())";
		$updateSellSql = mysqli_query($conn, $updateSellQuery) or die("Faileddd");

		$updatePatientQuery = "INSERT INTO patient (SerialNo,CaseNo,Device,Company,Model,Price,TransactionDate,Quantity) VALUES ('$serial','$caseNo','$feature[0]','$feature[1]','$feature[2]','$feature[3]',CURDATE(),'$quantity')";
		$updatePatientResult = mysqli_query($conn, $updatePatientQuery)or die("Faileddd");
	}
	$totalAmt +=$others;
	echo '
	<tr>
	<td>Others</td>
	<td>'.$others.'</td>
	<td>1</td>
	<td>'.$others.'</td>
	</tr>
	<tr>
	<td></td>
	<td></td>
	<td>Total Amount=</td>
	<td>Rs.	'.$totalAmt.'</td>
	</tr>';	
	?>
</table>
<input type="button" id="button" class="form-control" style="width: 15%;" value="Print" onclick="vanish();window.print()">
</div>
<script type="text/javascript">
	function vanish(){
		document.getElementById('button').style.display = "none";
	}
</script>

</body>
</html>




