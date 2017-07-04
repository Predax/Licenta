<?php
/*
This class contains jQuery. Is also specified in the refferences of the licence. 
*/
session_start();
include 'OwnFunctions.php';
$nowFormat = date('Y-m-d', strtotime("1 day"));
if ($_SESSION["isLogged"] == true) {
	include 'Connection.php';
	$sqlServices = "Select * from services";
	$sqlProcessing = "Select * from processing";
	$sqlCounty = "Select * from county";
	$resultServices = $conn->query($sqlServices);
	$resultProcessing = $conn->query($sqlProcessing);
	$resultCounty = $conn->query($sqlCounty);
	if(isset($_POST["submitBtn"])) {
		$d = $_POST["scheduleDate"];
		$serv = $_COOKIE["nameS"];
		$proc = $_COOKIE["nameP"];
		$co =  $_COOKIE["nameC"];
		$ci = $_POST["city"];
		$add = $_POST["address"];
		$ot = $_POST["other"];
		$tot = $_POST["total"];
		$resourceCheckerU = true;
		$moneyCheckerU = true;
		$sqlTotalResource = "Select * from date_resource where Schedule_date ='" . $d . "'";
		$sqlServiceResource = "Select * from services where Service_name ='" . $serv . "'";
		$sqlCountyResource = "Select Resource from county where Name ='" . $co . "'";
		$sqlMoney = "Select * from accounts where Username_id = '" . $_SESSION["id"] . "'"; 
		$resultTotalResource= $conn->query($sqlTotalResource);
		$resultServiceResource = $conn->query($sqlServiceResource);
		$resultCountyResource = $conn->query($sqlCountyResource);
		$resultMoney = $conn->query($sqlMoney);
		$rowT = $resultTotalResource->fetch_assoc();
		$rowS = $resultServiceResource->fetch_assoc();
		$rowC = $resultCountyResource->fetch_assoc();
		$rowM = $resultMoney->fetch_assoc();
		$varResTotal = $rowS["Resource"] + $rowC["Resource"];
		if ( $varResTotal > $rowT["Total_resource"]) {
			$resourceCheckerU = false;
		}
		if ($rowM["Money"] < $tot) {
			$moneyCheckerU = false;
		}
		if ($resourceCheckerU == true && $moneyCheckerU == true) {
			$stmt = $conn->prepare("Insert into requests (Username_id, Username, Service, Processing, County, CityTown, Address, Other,
			Date, Sum_total, Resource) Values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("sssssssssss", $_SESSION["id"], $_SESSION["username"], $service, $processing, $county, $city, $address, $other, $date, $total, $resTotal);
			$service = $serv;
			$processing = $proc;
			$county = $co;
			$city = $ci;
			$address = $add;
			$other = $ot;
			$date = $d;
			$total = $tot;
			$resTotal = $varResTotal;
			if ($stmt->execute() == true) {
				$inserted = true;
				$newValue = $rowM["Money"] - $total;
				$sqlTransferMoney = "Update accounts SET Money = '" . $newValue . "' Where Username_id = '" . $_SESSION["id"] . "'";
				$resultTransferMoney = $conn->query($sqlTransferMoney);
			} else {
				$inserted = false;
			}	
		} 	
	}
	$sqlHistory = "SELECT * FROM requests WHERE status = 'Y' OR status = 'N' ORDER BY Date DESC";
	$resultHistory = $conn->query($sqlHistory);
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
<script>
$(document).ready(function(){ 
    var previous;
	var name;
	name = $("#service").find('option:selected').text();
	document.cookie = "nameS="+name;
    $("#service").focus(function () {
        // Store the current value on focus, before it changes
        previous =  parseInt($(this).val(), 10);
		name = $(this).find('option:selected').text();
		document.cookie = "nameS="+name;
    }).change(function() {
		var total = parseInt($("#total").val(), 10);
		total = total - previous;
        var serviceCost = parseInt($(this).val(), 10);
		total = total + serviceCost;
		$( "#total" ).val( total );
		name = $(this).find('option:selected').text();
		document.cookie = "nameS="+name;
		//alert(name);
		$("#service").blur();

    });
})();
</script>
<script>
$(document).ready(function(){ 
    var previous;
	var name;
	name = $("#processing").find('option:selected').text();
	document.cookie = "nameP="+name;
    $("#processing").focus(function () {
        previous =  parseInt($(this).val(), 10);
		name = $(this).find('option:selected').text();
		document.cookie = "nameP="+name;
    }).change(function() {
		var total = parseInt($("#total").val(), 10);
		total = total - previous;
        var processingCost = parseInt($(this).val(), 10);
		total = total + processingCost;
		$( "#total" ).val( total );
		name = $(this).find('option:selected').text();
		document.cookie = "nameP="+name;
		$("#processing").blur();
    });
})();
</script>
<script>
$(document).ready(function(){ 
    var previous;
	var name;
	name = $("#county").find('option:selected').text();
	document.cookie = "nameC="+name;
    $("#county").focus(function () {
        previous =  parseInt($(this).val(), 10);
		name = $(this).find('option:selected').text();
		document.cookie = "nameC="+name;
    }).change(function() {
		var total = parseInt($("#total").val(), 10);
		total = total - previous;
        var countyCost = parseInt($(this).val(), 10);
		total = total + countyCost;
		$( "#total" ).val( total );
		name = $(this).find('option:selected').text();
		document.cookie = "nameC="+name;
		$("#county").blur();
    });
})();
</script>
<?php 
if ($_SESSION["role"] == $_roleClient) {
	include_once 'Navbar.php'; ?>
	<div class="container">
		<div id="scheduleBox" class="schedule">
			<form id="scheduleFrm" action="" method="post" class="schedule-container">
				<span>Choose date:</span><input type="date" name="scheduleDate" required="required" min="<?php echo htmlspecialchars($nowFormat); ?>"><br>	
				<span>Choose service:</span>
				<select id = "service" name="service">
				<?php if ($resultServices && $resultServices->num_rows > 0) {
					while($row = $resultServices->fetch_assoc()) { ?>
					<option name="<?php echo htmlspecialchars($row['Service_name']); ?>" value="<?php echo htmlspecialchars($row['Cost']); ?>"><?php echo $row["Service_name"] ?></option>
				<?php }
				} ?>
				</select>
				<br>
				<span>Paper processing:</span>
				<select id="processing" name="processing">
				<?php if ($resultProcessing && $resultProcessing->num_rows > 0) {
					while($row = $resultProcessing->fetch_assoc()) { ?>
					<option name="<?php echo htmlspecialchars($row['Type']); ?>" value="<?php echo htmlspecialchars($row['Cost']); ?>"><?php echo $row["Type"] ?></option>
				<?php }
				} ?>
				</select>
				<br>
				<span>County</span>
				<select id="county" name="county">
					<?php if ($resultCounty && $resultCounty->num_rows > 0) {
					while($row = $resultCounty->fetch_assoc()) { ?>
					<option name="<?php echo htmlspecialchars($row['Name']); ?>" value="<?php echo htmlspecialchars($row['Value']); ?>"><?php echo $row["Name"] ?></option>
				<?php } 
				} ?>
				</select>
				<br>
				<span>City/Town:</span><input name="city" required="required" type="text" maxlength="50"><br>
				<span>Address</span><input required="required" name="address" type="text"><br>
				<span>Other information</span><input name="other" type="text"><br>
				<span>Total (RON)</span><input id="total" name="total" value="0" readonly="totalSum" type="text"><br>
				<input id="scheduleBtn" type="submit" value="Schedule" name="submitBtn">
				<br><br> 
				<?php
				if (isset($resourceCheckerU) && $resourceCheckerU == false) { include 'ResourceUser.php'; ?>
					<script>
					  $( function() {
						$( "#dialog-message-checker" ).dialog({
						  modal: true,
						  width: 500,
						  dialogClass: "no-close",
						  buttons: {
							Understand: function() {
							  $( this ).dialog( "close" );
							}
						  }
						});
					  } );  
					</script> 
				<?php } else if (isset($inserted) && $inserted == true) { include 'Success.php';
				include 'PHPMailer-master/test.php';?>
					<script>
					  $( function() {
						$( "#dialog-message-success" ).dialog({
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
					  } ); 
					</script> 
				<?php }  else if (isset($inserted) && $inserted == false) { include 'Fail.php'; ?>	
				<script>
					  $( function() {
						$( "#dialog-message-fail" ).dialog({
						  modal: true,
						  width: 500,
						  dialogClass: "no-close",
						  buttons: {
							Ok: function() {
							  $( this ).dialog( "close" );
							}
						  }
						});
					  } );  
					</script> 
				<?php } else if (isset($moneyCheckerU) && $moneyCheckerU == false) { include 'ResourceUser.php'; ?>
				<script>
					  $( function() {
						$( "#dialog-message-money" ).dialog({
						  modal: true,
						  width: 500,
						  dialogClass: "no-close",
						  buttons: {
							Ok: function() {
							  $( this ).dialog( "close" );
							  window.location.replace("http://localhost/deton/Profile.php");
							}
						  }
						});
					  } );  
					</script> 
				<?php } ?>
			</form>	
		</div>
	</div>	
<?php } else if ($_SESSION["role"] == $_roleAdmin) {
	include_once 'NavbarAdm.php'; ?>
	<div class="container2">
	<h2 class="release-header">Requests history</h2>
	<!-- TABLE -->
	<table class="table table-action">  
	  <thead>
		<tr>
		  <th class="t-little">Username</th>
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
	  		 <?php if ($resultHistory && $resultHistory->num_rows > 0) {
					while($row = $resultHistory->fetch_assoc()) { ?>
					  <tr>
						 <td><?php echo $row["Username"] ?></td>
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

<?php } ?>
</div>
</body>
</html>