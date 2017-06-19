<?php
session_start();
include 'OwnFunctions.php';
if ($_SESSION["isLogged"] == true) {
	include 'Connection.php';
	$sql = "SELECT * FROM users WHERE Username = '" . $_SESSION["username"] . "'";
	$sqlAccount = "SELECT * from accounts WHERE Username_id='"  . $_SESSION["id"] . "'";
	$result = $conn->query($sql);
	$resultAccount = $conn->query($sqlAccount);
	$rowAccount = $resultAccount->fetch_assoc();
	$sumAccount = $rowAccount["Money"];
	if (isset($_POST["submit"])) {
		move_uploaded_file($_FILES['fileToUpload']['tmp_name'], "profile/".$_FILES['fileToUpload']['name']);
		$sqlPicture = "UPDATE users SET Image = '" . $_FILES['fileToUpload']['name'] . "' WHERE Username = '" . $_SESSION["username"] . "'";
		$resultPicture = $conn->query($sqlPicture);
		$change = false;
		$deposit= false;
		$first = $_POST["firstname"];
		$sur = $_POST["surname"];
		$phn = $_POST["phone"];
		$pass = $_POST["password"];
		$user = $_SESSION["id"];
		$acc = $_POST["account"];
		if ($_SESSION["role"] == $_roleClient) {
			$mon = $_POST["money"];
		}
		if (strlen($pass) > 4) {
			$passCheck = true;
		} else {
			$passCheck = false;
		}
		if ($acc != $_defaultAccount && strlen($acc) == 16) {
			$acc = $_POST["account"];
			$change = true;
			$deposit = true;
		}
		$stmt = $conn->prepare("UPDATE users SET Firstname = ?, Surname = ?, Phone = ? WHERE Id = ?");
		$stmt->bind_param("ssss", $firstname, $surname, $phone, $username);
		$firstname = $first;
		$surname = $sur;
		$phone = $phn;
		$username = $user;
		if ($stmt->execute() == true) {
			$updateBasic = true;
		} else {
			$updateBasic = false;
		}
		if ($passCheck == true)
		{
			$stmt = $conn->prepare("UPDATE users SET Password = ? WHERE Id = ?");
			$stmt->bind_param("ss", $password, $id);
			$password = $pass;
			$id = $user;
			if ($stmt->execute() == true) {
				$updatePass = true;
			} else {
				$updatePass = false;
			}
		}
		if ($change == true && $deposit == true)
		{
			$stmt = $conn->prepare("UPDATE accounts SET Number = ? WHERE Username_id = ?");
			$stmt->bind_param("ss", $number, $id);
			$number = $acc;
			$id = $user;
			if ($stmt->execute() == true) {
				$updateAccount = true;
			} else {
				$updateAccount = false;
			}
			if ($mon >= 10)
			{
				$total = $sumAccount + $mon;
				$stmt = $conn->prepare("UPDATE accounts SET Money = ? WHERE Username_id = ?");
				$stmt->bind_param("ss", $sum, $id);
				$sum = $total;
				$id = $user;
				if ($stmt->execute() == true) {
					$updateBalance = true;
				} else {
					$updateBalance = false;
				}
			}
		}
	}
} else {
	$newURL = "http://localhost/deton/login.php";
	header('Location: '.$newURL);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
	<meta charset="UTF-8">
</head>
<body>
<?php 
if ($_SESSION["role"] == $_roleClient)
{
	include_once 'Navbar.php';	
} else if ($_SESSION["role"] == $_roleAdmin)
{
	include_once 'NavbarAdm.php';
}
?>
<div class="container">
	<div id="userBox" class="userArea">
		<span id="userDisplay"><?php echo $_SESSION["username"]; ?></span>
	</div>
	<div id="profileBox" class="profile">
		<form id="profileFrm" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" class="profile-container" enctype="multipart/form-data">	
			<?php $row = $result->fetch_assoc(); 
				$currentName = $row["Firstname"];
				$currentSurname = $row["Surname"];
				$currentPhone = $row["Phone"];
				$currentAccount = $rowAccount["Number"];
				if (is_null($row["Image"]) || $row["Image"] == "") {
					echo "<img id= 'profilePicture' src='profile/default.png' alt='No picture'>";
			 } else  { echo "<img id= 'profilePicture' src='profile/" . $row["Image"] . "' alt='No picture'>"; }?>
			<span>Name: </span><input name="firstname" type="text" value="<?php echo $currentName;?>"><br><br>
			<span>Surname: </span><input name="surname" type="text" value="<?php echo $currentSurname;?>"><br><br>
			<span>Phone: </span><input name="phone" type="text" value="<?php echo $currentPhone?>"><br><br>
			<span>Account: </span><input name="account" type="text" maxlength="16" value="<?php echo $currentAccount;?>"><br><br>
			<?php if ($_SESSION["role"] == $_roleClient) { ?>
			<span>Money: </span><input name="money" type="number" min="10" value=""><br><br>
			<?php } ?>
			<span>Password: </span><input name="password" type="password"><br><br>
			<input type="file" name="fileToUpload" id="fileToUploads">
			<input type="submit" value="Save" name="submit">
							<?php
				if ((isset($updateBasic) && $updateBasic == true) || (isset($updatePass) && $updatePass == true) 
					|| (isset($updateAccount) && $updateAccount == true) || (isset($updateBalance) && $updateBalance == true)) { 
				include 'ProfileChange.php'; ?>
					<script>
					  $( function() {
						$( "#dialog-message-profile" ).dialog({
						  modal: true,
						  width: 500,
						  dialogClass: "no-close",
						  buttons: {
							Ok: function() {
							  $( this ).dialog( "close" );
							  window.location.replace("http://localhost/deton/profile.php");
							}
						  }
						});
					  }); 
					</script> 
				<?php } 
				?>
		</form>	
	</div>
</div>
</body>
</html>