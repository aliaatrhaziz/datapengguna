<?php
$hostname_dummyDB = "localhost";
$database_dummyDB = "datapengguna";
$username_dummyDB = "root";
$password_dummyDB = "";

$db = mysqli_connect($hostname_dummyDB, $username_dummyDB, $password_dummyDB,$database_dummyDB);
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set("Asia/Kuala_Lumpur");

?>
