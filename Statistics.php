<?php
session_start();
if ($_SESSION["isLogged"] == true) {
	include 'Connection.php';
	$sqlTotalMoney = "SELECT Money FROM accounts WHERE Username_id ='" . $_SESSION["id"] . "'";
	$sqlTotalU = "SELECT COUNT(*) AS TOTALU FROM users";
	$sqlTotalRequests = "SELECT COUNT(*) AS TOTALV FROM requests WHERE Status = 'Y'";
	$sqlTotalM = "SELECT COUNT(*) AS TOTALM FROM messages";
	$sqlLastV = "SELECT MAX(Date) AS LASTV FROM requests WHERE Status = 'Y' and Username_id ='" . $_SESSION["id"] . "'";
	$sqlTotalR = "SELECT COUNT(*) AS TOTALR FROM requests WHERE Username_id = '" . $_SESSION["id"] . "'";
	$sqlTotalMU = "SELECT COUNT(*) AS TOTALMU FROM messages WHERE Username = '" . $_SESSION["username"] . "'";
} else {
	$newURL = "http://localhost/deton/login.php";
	header('Location: '.$newURL);
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Statistics</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="stats.css">
	<meta charset="UTF-8">
</head>
<body>
<?php 
include 'OwnFunctions.php';
if ($_SESSION["role"] == $_roleClient)
{
	include_once 'Navbar.php';	?>
<div class="statContainer">
	<a class="nounderline"  title="My money">
	<div class="statBubbleContainer">
	<div class="statBubble">
	  <div class="statNum">
	  <?php
			$result = $conn->query($sqlTotalMoney);
			$data=$result->fetch_assoc();
			echo $data["Money"];
	  ?>
	  </div>
	</div>
	  <h3>Total Balance</h3>
	</div>
	</a>


	<a class="nounderline" title="Closest date until an approved request">
	<div class="statBubbleContainer">
	<div class="statBubble">
	  <div class="statNum">
	  #<?php
			$result = $conn->query($sqlLastV);
			$data=$result->fetch_assoc();
			$nowFormat = date("Y-m-d");
			$sqlDif = "select datediff('" . $nowFormat . "', '" . $data['LASTV'] . "') AS DAYS;";
			$result2=$conn->query($sqlDif);
			$data2=$result2->fetch_assoc();
			echo abs($data2["DAYS"]);
	  ?>
	  </div>
	</div>
	  <h3>Closest date - days</h3>
	</div>
	</a>
	  
	<a class="nounderline" title="Total requests">
	<div class="statBubbleContainer">
	<div class="statBubble">
	  <div class="statNum">
	  #<?php
			$result = $conn->query($sqlTotalR);
			$data=$result->fetch_assoc();
			echo $data["TOTALR"];
	  ?>
	  </div>
	</div>
	  <h3>Number of requests</h3>
	</div>
	</a>
	
	<a class="nounderline" title="Total sent">
	<div class="statBubbleContainer">
	<div class="statBubble">
	  <div class="statNum">
	  #<?php
			$result = $conn->query($sqlTotalMU);
			$data=$result->fetch_assoc();
			echo $data["TOTALMU"];
	  ?>
	  </div>
	</div>
	  <h3>Number of messages</h3>
	</div>
	</a>
	  

</div>
	
<?php
} else if ($_SESSION["role"] == $_roleAdmin) {
include_once 'NavbarAdm.php'; ?>
<div class="statContainer">
	<a class="nounderline"  title="My money">
	<div class="statBubbleContainer">
	<div class="statBubble">
	  <div class="statNum">
	  <?php
			$result = $conn->query($sqlTotalMoney);
			$data = $result->fetch_assoc();
			echo $data["Money"];
	  ?>
	  </div>
	</div>
	  <h3>Total Balance</h3>
	</div>
	</a>


	<a class="nounderline" title="Total users">
	<div class="statBubbleContainer">
	<div class="statBubble">
	  <div class="statNum">
	  #<?php
			$result = $conn->query($sqlTotalU);
			$data=$result->fetch_assoc();
			echo $data["TOTALU"];
	  ?>
	  </div>
	</div>
	  <h3>Number of users</h3>
	</div>
	</a>
	  
	<a class="nounderline" title="Total processed requests">
	<div class="statBubbleContainer">
	<div class="statBubble">
	  <div class="statNum">
	  #<?php
			$result = $conn->query($sqlTotalRequests);
			$data=$result->fetch_assoc();
			echo $data["TOTALV"];
	  ?>
	  </div>
	</div>
	  <h3>Number of requests</h3>
	</div>
	</a>
	  
	 <a class="nounderline" title="Total messages">
	<div class="statBubbleContainer">
	<div class="statBubble">
	  <div class="statNum">
	  #<?php
			$result = $conn->query($sqlTotalM);
			$data=$result->fetch_assoc();
			echo $data["TOTALM"];
	  ?>
	  </div>
	</div>
	  <h3>Number of messages</h3>
	</div>
	</a>
</div>
<?php }
?>



</body>
</html>