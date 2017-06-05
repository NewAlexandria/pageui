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
	<script src="jquery-1.2.5.pack.js" type="text/javascript"></script>
	<script src="jquery-ui-personalized-1.5.2.min.js" type="text/javascript"></script>
	
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
		<div id="login">
			<?php  require_once('config/login_front.php');  ?>
		</div>

		<div class="search">
			<?php  require('search_cell.php');  ?>
		</div>

		<div id="frame_pages_include">
		<?php require('pages.php'); ?>
		</div>


	</td>

	<td id="frame_content_edit">
		<div class="content_floater">
			<a href="#" onclick="javascript:addGroup_UI();">Add Group</a> <a href="#" onclick="javascript:addLink_UI('','links_groups');">Add Link</a> <a href="home.php">Home</a>
		</div>
		
		<?php /*
		if ( $_REQUEST['link_id'] ) {
				require ('links_edit_front.php'); 
			} else {
				require ('links_add_front.php'); 
			} */
			?>
		
		<div id="links_groups">
		<?php require ('groups_block_list.php'); ?>
		</div>
		
		<div id="links_flat_list">
		<?php require ('links_flat_list.php'); ?>
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