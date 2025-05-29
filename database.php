<?php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASSWORD = '';
$DB_DATABASE = 'security';

$connection = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_DATABASE);
if (mysqli_connect_errno()){
	header("Location: sign-in.php?error=0");
	die();
	
}
if (!$connection){
	header("Location: sign-in.php?error=0");
	die();
}
?>