<?php

$servername = "localhost";
$database = "cpc";
$username = "root";
$password = "";
// Create connection
$link = mysqli_connect($servername, $username, $password, $database);
// Check connection
$link->set_charset("utf8");
if (!$link) {
      die("Connection failed: " . mysqli_connect_error());
}
?>