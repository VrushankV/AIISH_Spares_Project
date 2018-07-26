<?php
session_start();
$email = $_POST['email'];
$password = $_POST['password'];

$conn = mysqli_connect("localhost","root","", "spares_project") or die("Failed");

$Equery = "SELECT ID FROM login WHERE Email='$email' and Password='$password'";
$res = mysqli_query($conn,$Equery);

$rows = mysqli_num_rows($res);

if ($rows == 1) {
	$_SESSION['email'] = $email;
	header('Location: main.php');
}
else{
	$message = "Username and/or Password incorrect.\\nTry again.";
	echo "<script type='text/javascript'>alert('$message');
	window.location='login.html';</script>";
}
?>