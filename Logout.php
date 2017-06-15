<?php
Session_start();
Session_destroy();
$newURL = "http://localhost/deton/login.php";
header('Location: '.$newURL);
?>