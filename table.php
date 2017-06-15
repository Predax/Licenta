<?php
session_start();
include 'Connection.php';
$sql = "SELECT * FROM convicts";
$result = $conn->query($sql);


?>
<!DOCTYPE html>
<html>
<head>
	<title>Release</title>
	<link rel="stylesheet" type="text/css" href="table.css">
	<meta charset="UTF-8">
</head>
<body>

<div class="container">
	<h2 class="release-header">Release convicts</h2>
	<!-- TABLE -->
	<table class="table table-action" id="testtable">
	  
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
						 <td><label><input type="checkbox"></label></td>
						 <td id= "sper"><?php echo $row["Id"] ?></td>
						 <td><?php echo $row["Full_name"] ?></td>
						 <td><?php echo $row["Begin_date"] ?></td>
						 <td><?php echo $row["End_date"] ?></td>
					  </tr>
				 <?php
						}
					}
					$conn->close();
				 ?>
		
	  </tbody>
	</table>
	<!-- END TABLE -->
	<input type="button" value="Release" onclick="deleteRow('testtable');" />
<script>
	function deleteme(delid)
		{
			
				window.location.href='delete.php?del_id=' +delid+'';
				return true;
		}
				
	function deleteRow(tableID)  {
        var table = document.getElementById(tableID).tBodies[0];
        var rowCount = table.rows.length;

        for(var i=0; i<rowCount; i++) {
            var row = table.rows[i];
            var chkbox = row.cells[0].getElementsByTagName('input')[0];
            if(null != chkbox && true == chkbox.checked) {
                table.deleteRow(i); 
				// document.write(row.cells[1].innerHTML);
				rowCount--;
                i--;
             }
		}
	}
</script>
</div>
</body>
</html>