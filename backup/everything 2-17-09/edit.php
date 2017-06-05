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

/* debugging
$_SESSION['user_id'] = 8;
$_SESSION['page_id'] = 73;
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Your PageUI Links - editing</title>
	<meta name="generator" content="BBEdit 8.7" />
	<link rel="stylesheet" rev="stylesheet" href="beta.css" />
	  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" type="text/javascript"></script>
	  <script type="text/javascript" src="jquery-ui-personalized-1.6rc6.min.js"></script>

</head>
<body>

<table style=" width: 100%;" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td valign="top" id="left_col">
		<div id="frame_header">
			<?php require_once('header.php'); ?>
		</div>

		<div id="bookmark_us"><a href="homepage.php" style="color: #000; text-decoration: none;">Make this your start page</a>
		</div>

		<div id="frame_pages">
			<div id="login">
				<?php  require_once('config/login_front.php');  ?>
			</div>
	
			<div class="search">
				<?php  require_once('search_cell.php');  ?>
			</div>
	
			<div id="frame_pages_include">
				<?php require_once('pages.php'); ?>
			</div>

		</div>
			<div id="content_floater_edit">
				<div id=""><a href="home.php">Home</a></div>
				<div id=""><a href="#" onclick="javascript:addLink_UI('','links_groups');">Add Link</a>
				</div>
				<div id=""><a href="#" onclick="javascript:addGroup_UI();">Add Group</a>
				</div>
				<div id="tools_UI">
				<?php require_once("DHTML/addPagesUILoader.html"); ?>	
				</div>

			</div>
	</td>

	<td id="frame_content_edit">

		<div id="links_groups">
			<?php require ('groups_block_list.php'); ?>
		</div>
		
		<div id="links_flat_list">
			<?php require ('links_flat_list.php'); ?>
		</div>
	</td>

	<td id="frame_banner">
		<?php require_once('banner.php'); ?>
	</td>
</tr>

<tr>
	<td id="frame_footer" colspan="3">
		<?php require_once('footer.php'); ?>
	</td>
</tr>
</table>

</body>
</html>