<?php

$firstname = $_POST["fname"];
$lastname = $_POST["lname"];
$Dob = $_POST["dob"];
$em = $_POST["email"];
$pass = $_POST["password"];

$conn = mysqli_connect("localhost","root","", "spares_project");


$query = "SELECT ID FROM login WHERE Email='$em'";

$res = mysqli_query($conn,$query);
$ans = mysqli_num_rows($res);
if($ans >0) {
	echo "<script>alert('Email already exists.Try a new one!');
	window.location='SignUp.html';</script>";
}
else{
	$sql = "INSERT INTO login (Email,Password,First_Name,Last_Name,DOB) VALUES('$em','$pass','$firstname','$lastname','$Dob')";

	if (mysqli_query($conn, $sql)) {
		echo "New record created successfully";
	}
}	
?>