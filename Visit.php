<?php
session_start();
$nowFormat = date('Y-m-d', strtotime("1 day"));
if ($_SESSION["isLogged"] == true) {
	include 'Connection.php';
	$sqlServices = "Select * from services";
	$sqlProcessing = "Select * from processing";
	$sqlCounty = "Select * from county ORDER BY Name ASC";
	$resultServices = $conn->query($sqlServices);
	$resultProcessing = $conn->query($sqlProcessing);
	$resultCounty = $conn->query($sqlCounty);
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
	<meta charset="UTF-8">
</head>
<body>
<script type="text/javascript" src="js/jquery.js"></script>
<script>
$(document).ready(function(){ 
    var previous;
    $("#service").focus(function () {
        // Store the current value on focus, before it changes
        previous =  parseInt($(this).val(), 10);
    }).change(function() {
        // Do something with the previous value after the change
        document.getElementById("xx").innerHTML = "<b>Previous: </b>"+previous;
		var total = parseInt($("#total").val(), 10);
		total = total - previous;
        var serviceCost = parseInt($(this).val(), 10);
		total = total + serviceCost;
		$( "#total" ).val( total );
		$("#service").blur();
    });
})();
</script>
<script>
$(document).ready(function(){ 
    var previous;
    $("#processing").focus(function () {
        // Store the current value on focus, before it changes
        previous =  parseInt($(this).val(), 10);
    }).change(function() {
        // Do something with the previous value after the change
        document.getElementById("xx").innerHTML = "<b>Previous: </b>"+previous;
		var total = parseInt($("#total").val(), 10);
		total = total - previous;
        var serviceCost = parseInt($(this).val(), 10);
		total = total + serviceCost;
		$( "#total" ).val( total );
		$("#processing").blur();
    });
})();
</script>
<?php 
include 'OwnFunctions.php';
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
					<option value="<?php echo htmlspecialchars($row['Cost']); ?>"><?php echo $row["Service_name"] ?></option>
				<?php }
				} ?>
				</select>
				<br>
				<span>Paper processing:</span>
				<select id="processing" name="processing">
				<?php if ($resultProcessing && $resultProcessing->num_rows > 0) {
					while($row = $resultProcessing->fetch_assoc()) { ?>
					<option value="<?php echo htmlspecialchars($row['Cost']); ?>"><?php echo $row["Type"] ?></option>
				<?php }
				} ?>
				</select>
				<br>
				<span>County</span>
				<select name="county">
					<?php if ($resultCounty && $resultCounty->num_rows > 0) {
					while($row = $resultCounty->fetch_assoc()) { ?>
					<option value="<?php echo htmlspecialchars($row['Name']); ?>"><?php echo $row["Name"] ?></option>
				<?php } 
				} ?>
				</select>
				<br>
				<span>City/Town:</span><input name="city" type="text" maxlength="50"><br>
				<span>Address</span><input name="address" type="text"><br>
				<span>Other information</span><input name="other" type="text"><br>
				<span>Total (RON)</span><input id="total" value="0" readonly="totalSum" type="text"><br>
				<input id="scheduleBtn" type="submit" value="Schedule" name="submitBtn">
				<br><br>
			</form>	
			<div id="xx"></div>
		</div>
	</div>	
<?php } else if ($_SESSION["role"] == $_roleAdmin) {
	include_once 'NavbarAdm.php'; ?>
	<div class="container2">
	<h2 class="release-header">Visits history</h2>
	<form action="" method="post">
	<!-- TABLE -->
	<table class="table table-action">
	  
	  <thead>
		<tr>
		  <th class="t-small"></th>
		  <th class="t-medium">Username</th>
		  <th class="t-medium">Convict Name</th>
		  <th class="t-small">ID</th>
		  <th class="t-medium">Visit Date</th>
		  <th class="t-small">Status</th>
		</tr>
	  </thead>
	  
	  <tbody>

		
	  </tbody>
	</table>
	<!-- END TABLE -->
	<input type="submit" value="Delete" name="del" id="delBtn" />
	</form>

<?php } ?>
</div>
</body>
</html>