<?php
session_start();
session_unset();
 //unset($_SESSION['pass']);
 session_destroy();
 header('Location: index.php');
?>