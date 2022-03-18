<?
include("lib/auth.php"); 
event_logger($dbconn,"Successfully logged out.");	
session_start();
session_unset();
session_destroy();


header("Location: /");
exit();
?>
