<?php
session_start();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Your PageUI - Putting it in order</title>
	<meta name="generator" content="BBEdit 9.0" />
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

		<div id="frame_pages">
			<div id="login">
				<?php // require_once('config/login_front.php');  ?>
			</div>
	
			<div class="search">
				<?php // require_once('search_cell.php');  ?>
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
	<td id="frame_content" style="padding: 0 20px 0 20px">
		<div id="links_groups">
			<?php require ('groups_block_list.php'); ?>
		</div>

		<p id="lesson1_link1" style="position: absolute; top: 20px; left: 730px; width: 40px; height: 60px; border-width: 3px 3px 0 0; border-color: #000; border-style: solid;"></p>
		<p id="lesson1" style="position: absolute; top: 70px; left: 670px; border: 1px #000 solid; padding: 10px; width: 400px; background-color: #fff;">
		You can drag groups to reorder them, just like dragging files and folders on your computer.  The groups will resize themselves so they all fit in the row.  When you drag a group, a line will appear between rows; this guide helps you put a group in a new row.  The icons in the upper-right corner let you add a link to it, edit the group name, and delete the whole group.
		</p>
		
		<p id="lesson2_link1" style="position: absolute; top: 150px; left: 290px; height: 100px; width: 90px; border-width: 3px 3px 0 0; border-color: #000; border-style: solid;"></p>
		<p id="lesson2" style="position: absolute; top: 250px; left: 300px; border: 1px #000 solid; padding: 10px; width: 400px; background-color: #fff;">The links in a group can be reordered by dragging, too.  When you drag the, you can drop them in the space at the right side of the group in order to create a new column of links.  This way, you can create a layout that bestworks best for you.  The icons on the right side let you edit the link name, and delete it.
		</p>
		
		<p id="lesson2_link1" style="position: absolute; top: 110px; left: 120px; width: 30px; height: 300px; border-width: 3px 3px 0 0; border-color: #000; border-style: solid;"></p>
		<p id="lesson2" style="position: absolute; top: 380px; left: 100px; border: 1px #000 solid; padding: 10px; width: 400px; background-color: #fff;">The pages, likewise, can be edited and deleted.  
		</p>
		
		<p id="goto" style="position: absolute; top: 470px; left: 170px; border: 1px #000 solid; padding: 10px; width: 430px;"><a href="intro4.php">We have for you a convenient tool to add bookmarks to your page.</a>
		</p>		
		
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

<script type="text/javascript">
// <![CDATA[
$('#email').focus();
// ]]>
</script>

</body>
</html>