<?php
session_start();
if ($_SESSION["isLogged"] == true) {
	include 'Connection.php';
	$sql = "SELECT * FROM convicts ORDER BY Full_name, Begin_date ASC";
	$result = $conn->query($sql);
	$ok=false;
} else {
	$newURL = "http://localhost/deton/login.php";
	header('Location: '.$newURL);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Release</title>
	<link rel="stylesheet" type="text/css" href="table.css">
	<meta charset="UTF-8">
</head>
<body>

<div class="container2">
	<h2 class="release-header">Release convicts</h2>
	<form action="" method="post">
	<!-- TABLE -->
	<table class="table table-action">
	  
	  <thead>
		<tr>
		  <th class="t-small"></th>
		  <th class="t-small">ID</th>
		  <th>Full Name</th>
		  <th class="t-medium">Jail Date</th>
		  <th class="t-medium">Release Date</th>
		</tr>
	  </thead>
	  
	  <tbody>
		
		 <?php if ($result && $result->num_rows > 0) {
					while($row = $result->fetch_assoc()) { ?>
					  <tr>
						 <td><label><input name="num[]" type="checkbox" value="<?php echo $row['Id'];?>"></form></label></td>
						 <td id= "sper"><?php echo $row["Id"] ?></td>
						 <td><?php echo $row["Full_name"] ?></td>
						 <td><?php echo $row["Begin_date"] ?></td>
						 <td><?php echo $row["End_date"] ?></td>
					  </tr>
				 <?php
						}
					}
					
				 ?>
		
	  </tbody>
	</table>
	<!-- END TABLE -->
	<input type="submit" value="Release" name="del" id="delBtn" />
	</form>
	<?php
		if(isset($_POST["del"])) {
			$box=$_POST["num"];
			while(list($key,$val) = @each ($box)) {
				$sql="DELETE FROM convicts WHERE Id = '" . $val . "'";
				if ($conn->query($sql) === TRUE) {
				$ok=true;
			} else {
				echo "Error deleting record: " . $conn->error;
			}
			}
		?>
		<script type="text/javascript">
		window.location.href=window.location.href;
		</script>
		<?php
		}
		?>
	

</div>
</body>
</html>