<?php
include("templates/page_header.php");
include("lib/auth.php"); 

//check if current user is admin, if so, delete the article
$aid = $_GET['aid'];
if ($_SESSION['role'] === 'admin'){
	$result = delete_article($dbconn, $aid);
	header("Location: /admin.php");
}

//check if current non-admin user is the author of the article, if yes, delete article
if ($_SESSION['role'] !== 'admin'){
	$result = get_article($dbconn, $aid);
	$row = pg_fetch_array($result);
	
	if($_SESSION['username'] === $row['author']) {
		$result = delete_article($dbconn, $aid);
		header("Location: /admin.php");		
	} else {
		header("Location: /admin.php");
		exit();
	}	
} 
?>
