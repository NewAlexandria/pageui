<?php
session_start();

require('config/keys.php');
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
<?php	require('script_versions.php'); ?>

</head>
<body>

<table style=" width: 100%;" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td valign="top" id="left_col">
		<div id="frame_header">
			<?php require_once('header.php'); ?>
		</div>
		
		<?php if ( $_SESSION['show_home'] == 'on' ) { require_once('set_homepage.php'); } ?>
		<?php if ( $_SESSION['show_dropper'] == 'on' ) { require_once('set_bookmarker.php'); } ?>


		<div id="frame_pages">
			<div id="login">
				<?php  require_once('config/login_front.php');  ?>
			</div>
	
			<div id="frame_pages_include">
				<?php require_once('pages.php'); ?>
			</div>

		</div>
			<div id="content_floater_edit">
				<div class="option"><a href="home.php">Save & Go Home</a></div>
				<div class="option"><a href="#" onclick="javascript:addLink_UI('','links_groups_edit');">Add Link</a>
				</div>
				<div class="option"><a href="#" onclick="javascript:addGroup_UI();">Add Group</a>
				</div>
				<div id="tools_UI" class="option">
				<?php require_once("DHTML/addPagesUILoader.html"); ?>	
				</div>

			</div>
	</td>

	<td id="frame_content">
		<div id="services_header" >
			<table style="width: 100%;" cellpadding="0" cellspacing="0"><tr><td>
			<?php  require_once('search_head.php');  ?>
			</td><td>
			<?php if ( $_SESSION['tutorials'] == 'on' || $_SESSION['tutorials'] == '' ) { include_once('tutorial.php'); } ?>
			</td></tr></table>
		</div>
		
		<div id="links_groups_edit">
			<?php require ('groups_block_list.php'); ?>
		</div>
		
<!-- 
		<div id="links_flat_list">
		</div>
 -->
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