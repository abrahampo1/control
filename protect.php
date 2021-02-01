<?php

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
if(isset($_SESSION["user_id"]))
{
    $usuario = $_SESSION["user_id"];
}else
{
    header("location: ./login.php");
}
?>