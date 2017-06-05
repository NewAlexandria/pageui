<?php
session_start();

require_once('config/keys.php');
require_once('config/db.php');

// screen for whether they are logged in
if ($_SESSION['user_id']=="") {

	if ($_COOKIE['maintain'] != "") {
		header("Location: config/login.php?x=login&user_id=".$_COOKIE['maintain']);
	} else {
		header("Location: ".$home);	
		exit;
	}
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>PageUI Search</title>
	<meta name="generator" content="BBEdit 8.7" />
	<link rel="stylesheet" rev="stylesheet" href="beta.css" />
	
</head>
<body>

<table style=" width: 100%;" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td id="frame_header" colspan="3">
	<?php require('header.php'); ?>
	</td>
</tr>
<tr>
	<td id="frame_pages" >
		<div class="search">
			<?php  require('search_cell.php');  ?>
		</div>

		<div id="frame_pages_include">
		<?php require('pages.php'); ?>
		</div>


	</td>

	<td id="frame_content">
		
		<div id="search_block">
		<?php require ('cse/gapi.php'); ?>
		</div>
		
	</td>

	<td id="frame_banner">
	<?php require('banner.php'); ?>
	</td>
</tr>
<tr>
	<td id="frame_footer" colspan="3">
	<?php require('footer.php'); ?>
	</td>
</tr>
</table>


</body>
</html>