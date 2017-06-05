<?php
session_start();

require_once('config/keys.php');

// screen for whether they are logged in
if ($_SESSION['user_id']=="") {

	if ($_COOKIE['maintain'] != "") {
		header("Location: config/login.php?x=login&user_id=".$_COOKIE['maintain']);
	} else {
		header("Location: ".$home);	
		exit;
	}
}

require_once('config/db.php');
$username = $_SESSION['username'];

// print_r($_COOKIE);

/// check here as failsafe against page deletes, etc.
$sqlPages = "SELECT P.* FROM Pages P INNER JOIN UsersPages UP ON P.id = UP.page_id WHERE UP.user_id = ".$_SESSION['user_id']." ORDER BY UP.sort ASC";
$resPages = mysql_query($sqlPages);
// if there is a record, go pages-add

$errtxt .= $sql; 
$errtxt .= '<br/>sql error: '.mysql_error($db).''; 
$errtxt .= '<br/>rows (p): '.mysql_num_rows($resPages).'<br/>'; 


if (!(mysql_num_rows($resPages)>0)) {
//	header("Location: config/pages.php?proc=add");	
//	exit;
}	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Your PageUI Links</title>
	<meta name="generator" content="BBEdit 8.7" />
	<link rel="stylesheet" rev="stylesheet" href="beta.css" />
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
		<div id="content_floater">
			<a href="edit.php">Edit</a>
		</div>

	</td>

	<td id="frame_content">
		<div id="links_groups">
		<?php require ('groups_block_list_tables.php'); ?>
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
