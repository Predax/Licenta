<?php
session_start();
if ($_SESSION["isLogged"] == true) {
	include 'Connection.php';
} else {
	$newURL = "http://localhost/deton/login.php";
	header('Location: '.$newURL);
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Management</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta charset="UTF-8">
</head>
<body>
<?php include_once 'NavbarAdm.php' ?>
<div class="container">
	<div id="managementBox" class="management">
		<img src="img/store.png" id="addImage" alt="add" onclick="insert()" />
		<img src="img/remove.png" id="removeImage" alt="remove" onclick="remove()" />
	</div>
</div>

<script>
function insert() {
    window.open("InsertConvict.php", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=400,width=500,height=500");
}

function remove() {
    window.open("deleteConvicts.php", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=80,left=100,width=1200,height=500");
}
</script>

</body>
</html>