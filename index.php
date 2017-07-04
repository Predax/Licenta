<?php
session_start();
if ($_SESSION["isLogged"] == true) {
	$nowFormat = date("Y-m-d");
	include 'Connection.php';
	$displayAdm = false;
	$displayUsr = false;
	$displayBtn = false;
	$sqlCheckReq = "SELECT * FROM requests WHERE Status = 'P'";
	$resultCheckReq = $conn->query($sqlCheckReq);
	if ($resultCheckReq && $resultCheckReq->num_rows > 0) {
		while($rowCheckReq = $resultCheckReq->fetch_assoc()) {
			if ($rowCheckReq["Date"] <= $nowFormat) {
				$sql="UPDATE requests SET Status = 'N' WHERE Request_id = '" . $rowCheckReq["Request_id"] . "'";
				if ($conn->query($sql) === TRUE) {
					$sqlAccounts = "SELECT * FROM accounts WHERE Username_id = '" . $rowCheckReq["Username_id"] . "'"; 
					$resultAccounts = $conn->query($sqlAccounts);
					$rowAccounts = $resultAccounts->fetch_assoc();
					$currentBalance = $rowAccounts["Money"];
					$total = $currentBalance + $rowCheckReq["Sum_total"];
					$sqlNewTotal = "UPDATE accounts SET Money ='" . $total . "' WHERE Username_id = '" . $rowCheckReq["Username_id"] . "'";
					if ($conn->query($sqlNewTotal) === TRUE) {
						$ok = true; 
					} else { 
						$ok = false; 
						}
				} else {
					echo "Error deleting record: " . $conn->error;
				}
			}
		}		
	}	
	$sqlAdm = "SELECT * FROM requests WHERE status = 'P' ORDER BY Date ASC";
	$sqlUsr = "SELECT * FROM requests WHERE Username_id = '" . $_SESSION["id"] . "' ORDER BY Date DESC";
	$resultAdm = $conn->query($sqlAdm);
	$resultUsr = $conn->query($sqlUsr);
	if($resultAdm->num_rows > 0) {
		$displayAdm = true;
	}
	if($resultUsr->num_rows > 0) {
		$displayUsr = true;
	}
} else {
	$newURL = "http://localhost/deton/login.php";
	header('Location: '.$newURL);	
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="table.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
	<meta charset="UTF-8">
</head>
<body>
<?php 
include 'OwnFunctions.php';
if ($_SESSION["role"] == $_roleClient) {
	include_once 'Navbar.php';	
	if ($displayUsr == true) { ?>
	<div class="container2">
	<h2 class="release-header">All requests</h2>
	<form action="" method="post">
	<!-- TABLE -->
	<table class="table table-action">
	  <thead>
		<tr>
		  <th class="t-small"></th>
		  <th class="t-medium">Service</th>
		  <th class="t-little">Processing</th>
		  <th class="t-little">County</th>
		  <th class="t-medium">City</th>
		  <th class="t-medium">Address</th>
		  <th class="t-little">Total</th>
		  <th class="t-medium">Date</th>
		  <th class="t-small"></th>
		</tr>
	  </thead>
	  <tbody>
		 <?php if ($resultUsr && $resultUsr->num_rows > 0) {
					while($row = $resultUsr->fetch_assoc()) { ?>
					  <tr>
						 <td><?php if ($row["Status"] == "P") { $displayBtn = true; ?>
						 <label><input name="num[]" type="checkbox" value="<?php echo $row['Request_id'];?>"></label>
						 <?php } ?></td>
						 <td><?php echo $row["Service"] ?></td>
						 <td><?php echo $row["Processing"] ?></td>
						 <td><?php echo $row["County"] ?></td>
						 <td><?php echo htmlspecialchars($row["CityTown"]) ?></td>
						 <td><?php echo htmlspecialchars($row["Address"]) ?></td>
						 <td><?php echo $row["Sum_total"] ?></td>
						 <td><?php echo $row["Date"] ?></td>
						 <td><?php if ($row["Status"] == "Y") { ?> <img src="img/check.png" alt="Y"> 
						 <?php } else if ($row["Status"] == "N") { ?>  <img src="img/uncheck.png" alt="N">
						 <?php } else if ($row["Status"] == "P") { ?> <img src="img/pending.png" alt="P">
						 <?php } ?></td>
					  </tr>
				 <?php
						}
					}
				 ?>
	  </tbody>
	</table>
	<!-- END TABLE -->
	<?php if ($displayBtn == true) { ?>
	<input type="submit" value="Remove" name="ren" id="delBtn" />
	<?php } ?>
	</form>
		<?php
		if(isset($_POST["ren"])) {
			$box=$_POST["num"];
			while(list($key,$val) = @each ($box)) {
				$sql="UPDATE requests SET Status = 'N' WHERE Request_id = '" . $val . "'";
				if ($conn->query($sql) === TRUE) {
					$sqlRequest = "SELECT * FROM requests Where Request_id = '" . $val . "'";
					$resultRequest = $conn->query($sqlRequest);
					$rowRequest = $resultRequest->fetch_assoc();
					$sqlAccounts = "SELECT * FROM accounts WHERE Username_id = '" . $rowRequest["Username_id"] . "'"; 
					$resultAccounts = $conn->query($sqlAccounts);
					$rowAccounts = $resultAccounts->fetch_assoc();
					$currentBalance = $rowAccounts["Money"];
					$total = $currentBalance + $rowRequest["Sum_total"] - 150;
					$sqlNewTotal = "UPDATE accounts SET Money ='" . $total . "' WHERE Username_id = '" 
					. $rowRequest["Username_id"] . "'";
					if ($conn->query($sqlNewTotal) === TRUE) {
						$cancelRequest = true; 
					} else { 
						$cancelRequest = false; 
					}
			} else {
				echo "Error deleting record: " . $conn->error;
			}
		} 
	}
		?>
	</div>
		<?php
	if (isset($cancelRequest) && $cancelRequest == true) { include 'ResourceUser.php'; ?>
		<script>
		  $( function() {
			$( "#dialog-message-cancel" ).dialog({
			  modal: true,
			  width: 500,
			  dialogClass: "no-close",
			  buttons: {
				Ok: function() {
				  $( this ).dialog( "close" );
				  window.location.replace("http://localhost/deton/index.php");
				}
			  }
			});
		  }); 
		</script> 
	<?php
	}
	} else { include 'noRequests.php'?> 
	 <div class="container2">
	 	<script>
		  $( function() {
			$( "#dialog-message-noreq" ).dialog({
			//  modal: true,
			  draggable: false,
			  resizable: false,
			  width: 500,
			  dialogClass: "no-close",
			  show: {
				effect: "bounce",
				duration: 500
			}
			});
		  }); 
		</script> 
	 </div>
	<?php }	
} else if ($_SESSION["role"] == $_roleAdmin) {	
	include_once 'NavbarAdm.php'; 
	if ($displayAdm == true) { ?>
	<div class="container2">
	<h2 class="release-header">Curent requests</h2>
	<form action="" method="post">
	<!-- TABLE -->
	<table class="table table-action"> 
	  <thead>
		<tr>
		  <th class="t-small"></th>
		  <th class="t-little">Username</th>
		  <th class="t-medium">Service</th>
		  <th class="t-little">Processing</th>
		  <th class="t-little">County</th>
		  <th class="t-medium">City</th>
		  <th class="t-medium">Address</th>
		  <th class="t-little">Total</th>
		  <th class="t-medium">Date</th>
		  <th class="t-small">Resources</th>
		</tr>
	  </thead> 
	  <tbody>
		 <?php if ($resultAdm && $resultAdm->num_rows > 0) {
					while($row = $resultAdm->fetch_assoc()) { ?>
					  <tr>
						 <td><label><input name="num[]" type="checkbox" value="<?php echo $row['Request_id'];?>"></label></td>
						 <td><?php echo $row["Username"] ?></td>
						 <td><?php echo $row["Service"] ?></td>
						 <td><?php echo $row["Processing"] ?></td>
						 <td><?php echo $row["County"] ?></td>
						 <td><?php echo htmlspecialchars($row["CityTown"]) ?></td>
						 <td><?php echo htmlspecialchars($row["Address"]) ?></td>
						 <td><?php echo $row["Sum_total"] ?></td>
						 <td><?php echo $row["Date"] ?></td>
						 <td><?php echo $row["Resource"] ?></td>
					  </tr>
				 <?php
						}
					}
				 ?>
	  </tbody>
	</table>
	<!-- END TABLE -->
	<div id="divider">
	<input type="submit" value="Approve" name="app" id="appBtn" />
	<input type="submit" value="Reject" name="rej" id="rejBtn" />
	</div>
	</form>
		<?php
		if(isset($_POST["rej"])) {
			$box=$_POST["num"];
			while(list($key,$val) = @each ($box)) {
				$sql="UPDATE requests SET Status = 'N' WHERE Request_id = '" . $val . "'";
				if ($conn->query($sql) === TRUE) {
					$sqlRequest = "SELECT * FROM requests Where Request_id = '" . $val . "'";
					$resultRequest = $conn->query($sqlRequest);
					$rowRequest = $resultRequest->fetch_assoc();
					$sqlAccounts = "SELECT * FROM accounts WHERE Username_id = '" . $rowRequest["Username_id"] . "'"; 
					$resultAccounts = $conn->query($sqlAccounts);
					$rowAccounts = $resultAccounts->fetch_assoc();
					$currentBalance = $rowAccounts["Money"];
					$total = $currentBalance + $rowRequest["Sum_total"];
					$sqlNewTotal = "UPDATE accounts SET Money ='" . $total . "' WHERE Username_id = '" . $rowRequest["Username_id"] . "'";
					if ($conn->query($sqlNewTotal) === TRUE) {
						$rejectRequest = true; 
					} else { 
						$rejectRequest = false; 
					}
			} else {
				echo "Error deleting record: " . $conn->error;
			}
			} 
		} if(isset($_POST["app"])) {
			$box=$_POST["num"];
			while(list($key,$val) = @each ($box)) {
				$sqlRequest = "SELECT * FROM requests Where Request_id = '" . $val . "'";
				$resultRequest = $conn->query($sqlRequest);
				$rowRequest = $resultRequest->fetch_assoc();
				$sqlResource = "SELECT * FROM date_resource WHERE Schedule_date = '" . $rowRequest["Date"] . "'";
				$resultResource = $conn->query($sqlResource);
				$rowResource = $resultResource->fetch_assoc();
				if ($rowRequest["Resource"] <= $rowResource["Total_resource"]) {
					$sql="UPDATE requests SET Status = 'Y' WHERE Request_id = '" . $val . "'";
					if ($conn->query($sql) === TRUE) {
						$newResource = $rowResource["Total_resource"] - $rowRequest["Resource"];
						$sqlUpdateResource = "UPDATE date_resource SET Total_resource = '" . $newResource . "' WHERE Schedule_date = '" . 
						$rowRequest["Date"] . "'";
						if ($conn->query($sqlUpdateResource) === TRUE) {
							$sqlAccounts = "SELECT * FROM accounts WHERE Username_id = '" . $_SESSION["id"] . "'"; 
							$resultAccounts = $conn->query($sqlAccounts);
							$rowAccounts = $resultAccounts->fetch_assoc();
							$currentBalanceAdm = $rowAccounts["Money"];
							$newBalanceAdm = $currentBalanceAdm + $rowRequest["Sum_total"];
							$sqlNewTotalAdm = "UPDATE accounts SET Money ='" . $newBalanceAdm . "' WHERE Username_id = '" . $_SESSION["id"] . "'";
							$conn->query($sqlNewTotalAdm);
							$acceptRequest = true; 
							}
					} else {
						$acceptRequest = false;
						echo "Error deleting record: " . $conn->error;
					}
				}
			} ?>	
</div>
		<?php
		}
	} else { include 'noRequests.php' ?> 
	 <div class="container2">
		<script>
		  $( function() {
			$( "#dialog-message-noreq" ).dialog({
			//  modal: true,
			  draggable: false,
			  resizable: false,
			  width: 500,
			  dialogClass: "no-close",
			  show: {
				effect: "bounce",
				duration: 500
			}
			});
		  }); 
		</script> 
	 </div>
	<?php } 
	if (isset($acceptRequest) && $acceptRequest == true) { include 'Choice.php'; ?>
	<script>
		  $( function() {
			$( "#dialog-message-accept" ).dialog({
			  modal: true,
			  width: 500,
			  dialogClass: "no-close",
			  buttons: {
				Ok: function() {
				  $( this ).dialog( "close" );
				  window.location.replace("http://localhost/deton/index.php");
				}
			  }
			});
		  }); 
		</script> 
	<?php } else if (isset($rejectRequest) && $rejectRequest == true) { include 'Choice.php'; ?>
		<script>
		  $( function() {
			$( "#dialog-message-reject" ).dialog({
			  modal: true,
			  width: 500,
			  dialogClass: "no-close",
			  buttons: {
				Ok: function() {
				  $( this ).dialog( "close" );
				  window.location.replace("http://localhost/deton/index.php");
				}
			  }
			});
		  }); 
		</script> 
	<?php }
} ?>
</body>
</html>