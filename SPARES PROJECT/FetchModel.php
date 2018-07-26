<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<?php
	$conn = mysqli_connect("localhost", "root", "", "spares_project");
	$mode = $_GET['q'];
	$mode = str_replace("=", " ", $mode);
	$mode = explode("|", $mode);
	$company = $mode[0];
	$device = $mode[1];

	$company=str_replace("^", "''", $company);
	$device=str_replace("^", "''", $device);

	$updateModelQuery = "SELECT DISTINCT Model FROM spares WHERE Device = '$device' AND  Company='$company'";
	$updateModelResult = mysqli_query($conn, $updateModelQuery)or die("failed");
	echo '<option value="-- Select an option --">-- Select an option --</option>';
	while ($row = mysqli_fetch_array($updateModelResult)) {
		$model = $row['Model'];
		$mode = str_replace("'", "^", $model);
		echo '<option value="'.$mode.'">'.$model.'</option>';
	}
	?>
</body>
</html>