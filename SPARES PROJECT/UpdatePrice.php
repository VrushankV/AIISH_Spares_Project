<?php
$conn = mysqli_connect("localhost", "root", "", "spares_project");
if (isset($_POST['submit'])) {
	
	$modelQuery = "SELECT Model,Device,Company,Price FROM spares";
	
	$i=1;
	$modelSql = mysqli_query($conn, $modelQuery);

	while ($i<=mysqli_num_rows($modelSql)){
		$rowModel = mysqli_fetch_array($modelSql);
		$mod= $rowModel['Model'];
		$mod = str_replace("'", "''", $mod);

		$comp = $rowModel['Company'];
		$comp = str_replace("'", "''", $comp);

		$dev=$rowModel['Device'];
		$dev = str_replace("'", "''", $dev);

		$p = $rowModel['Price'];

		$devi = str_replace("''", "^", $dev);
		$mode = str_replace("''", "^", $mod);
		$compa = str_replace("''", "^", $comp);
		$name = $devi.'|'.$compa.'|'.$mode.'|'.$p;
		$name = str_replace(" ", "_", $name);
		$price = $_POST[$name];
		$priceQuery = "UPDATE spares SET Price='$price' WHERE Device='$dev' AND Model='$mod' AND Company='$comp' AND Price ='$p'";
		mysqli_query($conn,$priceQuery)or die("Failed");

		$priceQuery1 = "UPDATE sell SET Price='$price' WHERE Device='$dev' AND Model='$mod' AND Company='$comp' AND Price ='$p'";
		mysqli_query($conn,$priceQuery1)or die("Failed");

		$priceQuery2 = "UPDATE entry SET Price='$price' WHERE Device='$dev' AND Model='$mod' AND Company='$comp' AND Price ='$p'";
		mysqli_query($conn,$priceQuery2)or die("Failed");

		$priceQuery3 = "UPDATE patient SET Price='$price' WHERE Device='$dev' AND Model='$mod' AND Company='$comp' AND Price ='$p'";
		mysqli_query($conn,$priceQuery3)or die("Failed");
		$i++;
	}
	echo "Price Updated Successfully!";
}


?>