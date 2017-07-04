<?php
session_start();
if(isset($_POST["subBtn"])) {
	include 'Connection.php';
	$usr = $_POST["username"];
	$pass = $_POST["password"];
	$stmt = $conn->prepare("SELECT * FROM users WHERE Username = ? AND Password = ?");
	$stmt->bind_param("ss", $username, $password);
	$username = $usr;
	$password = $pass;
	$stmt->execute();
	$result = $stmt->get_result();
	$rowNum = $result->num_rows;
	if($rowNum > 0) {
		    $row = $result->fetch_assoc();
			$credentials = true;
			$_SESSION["isLogged"] = true;
			$_SESSION["id"] = $row["Id"];
			$_SESSION["username"] = $row["Username"];
			$_SESSION["email"] = $row["Email"];
			if ($row["Role"] == "ADM")
			{
				$_SESSION["role"] = "admin";
			} else 
			{
				$_SESSION["role"] = "client";
			}
			$newURL = "http://localhost/deton/index.php";
			header('Location: '.$newURL);
	   } else {
		   $credentials = false;
	   }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="login.css">
	<meta charset="UTF-8">
</head>
<body>
<div class="container">
	<div id="loginBox" class="register">
		<h2 class="register-header">Login</h2>
		<form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" class="register-container">
			<input id="user" type="text" name="username" placeholder="username" maxlength="25" required="required"><br>
			<input id="pass" type="password" name="password" placeholder="password"><br>
			<input id="loginBtn" type="submit" name="subBtn" value="Login"></input>
				<?php if(isset($credentials) && $credentials == false){ ?>
				<span id="invCredentials">Invalid credentials, please retry!</span>
				<?php } ?>
			<p style="text-align: right;"> To register, press <a href="Register.php" style="font-weight: bold;">here</em></a></p>
		</form>
	</div>
</div>
</body>
</html>