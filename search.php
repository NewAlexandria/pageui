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
	<title>Searching Your PageUI Links First</title>
	<meta name="generator" content="BBEdit 8.7" />
	<link rel="stylesheet" rev="stylesheet" href="beta.css" />
<?php if ( $_SESSION['tut']=='on' ) {
	require('script_versions.php');
} ?>
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

	</td>

	<td id="frame_content">
		
		<div id="search_block">
		<?php require ('cse/gapi_raw.php'); ?>
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