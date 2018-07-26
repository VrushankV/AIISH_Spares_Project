<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<?php
	$conn = mysqli_connect("localhost", "root", "", "spares_project");
	$device = $_GET['q'];	
	$device = str_replace("^", "'", $device);
	$device = str_replace("'", "''", $device);
	$updateCompanyQuery = "SELECT DISTINCT Company FROM spares WHERE Device = '$device'";
	$updateCompanyResult = mysqli_query($conn, $updateCompanyQuery)or die("failed");
	echo '<option value="-- Select an option --">-- Select an option --</option>';
	while ($row = mysqli_fetch_array($updateCompanyResult)) {
		$company = $row['Company'];
		$com = str_replace("'", "^", $company);
		echo '<option value="'.$com.'">'.$company.'</option>';
	}
	?>
</body>

</html>