<?php
session_start();
session_unset(); 
setcookie('sid','',0,'/');
header('location: index.php');
?>