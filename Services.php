<?php
session_start();
if ($_SESSION["isLogged"] == true) {
	include 'Connection.php';
	include 'OwnFunctions.php';
	$sql = "SELECT * FROM services";
	$result = $conn->query($sql);
} else {
	$newURL = "http://localhost/deton/login.php";
	header('Location: '.$newURL);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Services</title>
	<link rel="stylesheet" type="text/css" href="table.css">
	<meta charset="UTF-8">
</head>
<body>
<div class="container2">
	<h2 class="release-header">Services Requests</h2>
	<form action="" method="post">
	<!-- TABLE -->
	<table class="table table-action">
	  <thead>
		<tr>
		  <th class="t-medium">Services</th>
		  <th class="t-medium">Requested</th>
		</tr>
	  </thead>
	  <tbody>	
		 <?php if ($result && $result->num_rows > 0) {
					while($row = $result->fetch_assoc()) { 
						if (strcmp($defaultService, $row["Service_name"]) != 0) {
							$sqlTotalServices = "SELECT COUNT(*) AS TOTALS FROM requests where Service = '" . $row["Service_name"] . "'";	
							$resultServices = $conn->query($sqlTotalServices);
							$data=$resultServices->fetch_assoc();	?>
							  <tr>
								 <td><?php echo $row["Service_name"] ?></td>
								 <td><?php echo $data["TOTALS"] ?></td>
							  </tr>
				 <?php
							}
						}
					}
				 ?>	
	  </tbody>
	</table>
</div>
</body>
</html>