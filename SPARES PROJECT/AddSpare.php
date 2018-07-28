<?php 
if(isset($_POST['submit'])){
	$conn = mysqli_connect("localhost", "root", "", "spares_project");

	$Device = $_POST['Device'];
	$Device = str_replace("'","''", $Device);

	if ($_POST['Model']!="") {
		$Model =$_POST['Model'];
		$Model = str_replace("'","''", $Model);
	}else{
		$Model = "null";
	}
	$Price = $_POST['Price'];


	if ($_POST['Company'] !="") {
		$Company = $_POST['Company'];
		$Company = str_replace("'","''", $Company);
	}else {
		$Company = "null";
	}
	if (isset($_POST['Quantity'])) {
		$Quantity = $_POST['Quantity'];
	}
	else {
		$Quantity = 0;
	}
	$alertQuantity = 0.3*$Quantity;
	$insertQuery = "INSERT INTO spares (Device,Company,Model,Quantity,IndentDate,MinQty,Price) VALUES ('$Device','$Company','$Model','$Quantity',CURDATE(),'$alertQuantity','$Price')";

	$insertResult = mysqli_query($conn,$insertQuery) or die("Failedddddd");

	
	$updateEntryQuery = "INSERT INTO entry (Device,Company,Model,Price,Quantity,TransactionQuantity,TransactionDate) VALUES('$Device','$Company','$Model','$Price','$Quantity','$Quantity',CURDATE())";
	$updateEntrySql = mysqli_query($conn, $updateEntryQuery) or die("Failed");

	echo "Inserted Successfully";
}
?>