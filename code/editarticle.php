<?php include("templates/page_header.php");?>
<?php include("lib/auth.php") ?>
<?php

if($_SERVER['REQUEST_METHOD'] == 'GET') {
	$aid = $_GET['aid'];	
	
	//if the current user's role is admin, access any article for edit
	if ($_SESSION['username'] === 'admin'){
	$result=get_article($dbconn, $aid);
	$row = pg_fetch_array($result, 0);
	event_logger($dbconn,"Article $aid edit attempt.");
	}

	//check if current user's role is not admin and if it is the author of the article. If yes, access article for edit
	if ($_SESSION['username'] !== 'admin'){
		$result = get_article($dbconn, $aid);
		$row = pg_fetch_array($result);
	
		if($_SESSION['username'] === $row['author']) {
			$row = pg_fetch_array($result, 0);
			event_logger($dbconn,"Article $aid edit attempt.");	
		} else {
			header("Location: /admin.php");
			exit();
		}	
	} 
			
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$title = $_POST['title'];
	$content = $_POST['content'];
	$aid = $_POST['aid'];
	$result=update_article($dbconn, $title, $content, $aid);
	Header ("Location: /");
}
?>

<!doctype html>
<html lang="en">
<head>
	<title>New Post</title>
	<?php include("templates/header.php"); ?>
</head>
<body>
	<?php include("templates/nav.php"); ?>
	<?php include("templates/contentstart.php"); ?>

<h2>New Post</h2>

<form action='#' method='POST'>
	<input type="hidden" value="<?php echo htmlspecialchars($row['aid']) ?>" name="aid">
	<div class="form-group">
	<label for="inputTitle" class="sr-only">Post Title</label>
	<input type="text" id="inputTitle" required autofocus name='title' value="<?php echo htmlspecialchars($row['title']) ?>">
	</div>
	<div class="form-group">
	<label for="inputContent" class="sr-only">Post Content</label>
	<textarea name='content' id="inputContent"><?php echo htmlspecialchars($row['content']) ?></textarea>
	</div>
	<input type="submit" value="Update" name="submit" class="btn btn-primary">
</form>
<br>

	<?php include("templates/contentstop.php"); ?>
	<?php include("templates/footer.php"); ?>
</body>
</html>
