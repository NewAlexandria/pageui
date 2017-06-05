<?php
session_start();

include('config/keys.php');
require('config/db.php');

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
	<title>Your PageUI Links - editing</title>
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
			<?php  require('search.php');  ?>
		</div>

		<?php require('pages.php'); ?>

		<div id="tools_UI">
	
			<form action="config/pages.php" method="post" id="new_page_form" name="new_page_form">
			New Page:  	<input name="pages_submit" type="submit" value="Add" id="pages_submit" />
			<input name="name" id="pages_name" type="text" size="15" maxlength="60" tabindex="1" value="<?php echo $name ?>" />
					
			<input name="proc" type="hidden" value="add" />
			<input name="page" type="hidden" value="edit.php" />
			</form>	
			
		</div>

	</td>

	<td id="frame_content">
		<div class="content_floater">
			<a href="home.php">Home</a>
		</div>
		
		<div id="links_groups">
		<?php if ( $_REQUEST['link_id'] ) {
				require ('links_edit_front.php'); 
			} else {
				require ('links_add_front.php'); 
			}
			?>
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