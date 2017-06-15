<?php
session_start();
if ($_SESSION["isLogged"] == true) {
	include 'Connection.php';
	$sql = "SELECT * FROM messages ORDER BY Date_reception DESC";
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
	<title>Messages</title>
	<link rel="stylesheet" type="text/css" href="table.css">
	<meta charset="UTF-8">
</head>
<body>
<?php include_once 'NavbarAdm.php' ?>
<div class="container2">
	<h2 class="release-header">All messages</h2>
	<form action="" method="post">
	<!-- TABLE -->
	<table class="table table-action" id="testtable">
	  
	  <thead>
		<tr>
		  <th class="t-small"></th>
		  <th class="t-small">ID</th>
		  <th>Username</th>
		  <th class="t-medium">Subject</th>
		  <th class="t-medium">Content</th>
		  <th class="t-medium">Date</th>
		</tr>
	  </thead>
	  
	  <tbody>
		
		 <?php if ($result && $result->num_rows > 0) {
					while($row = $result->fetch_assoc()) { ?>
					  <tr>
						 <td><label><input name="num[]" type="checkbox" value="<?php echo $row['Message_id'];?>"></form></label></td>
						 <td id= "sper"><?php echo $row["Message_id"] ?></td>
						 <td><?php echo $row["Username"] ?></td>
						 <td><?php echo $row["Subject"] ?></td>
						 <td><?php echo $row["Content"] ?></td>
						 <td><?php echo $row["Date_reception"] ?></td>
					  </tr>
				 <?php
						}
					}
					
				 ?>
		
	  </tbody>
	</table>
	<!-- END TABLE -->
	<input type="submit" value="Delete Messages" name="del" id="delBtn" />
	</form>
	<?php
		if(isset($_POST["del"])) {
			$box=$_POST["num"];
			while(list($key,$val) = @each ($box)) {
				$sql="DELETE FROM messages WHERE Message_id = '" . $val . "'";
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