<?php
session_start();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Your PageUI - Putting it in order</title>
	<meta name="generator" content="BBEdit 9.0" />
	<link rel="stylesheet" rev="stylesheet" href="../beta.css" />
<?php	require('../script_versions.php'); ?>

</head>
<body>

<table style=" width: 100%;" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td valign="top" id="left_col">
		<div id="frame_header">
			<?php require_once('../header.php'); ?>
		</div>
		
		<?php if ( $_SESSION['show_home'] == 'on' ) { require_once('../set_homepage.php'); } ?>
		<?php if ( $_SESSION['show_dropper'] == 'on' ) { require_once('../set_bookmarker.php'); } ?>

		<div id="frame_pages">
			<div id="login">
				<?php  require_once('../config/login_front.php');  ?>
			</div>
	
			<div id="frame_pages_include">
			<?php require_once('../pages.php'); ?>
			</div>

		</div>
			<div id="content_floater_edit">
				<div class="option"><a href="home.php">Home</a></div>
				<div class="option"><a href="#" onclick="javascript:addLink_UI('','links_groups');">Add Link</a>
				</div>
				<div class="option"><a href="#" onclick="javascript:addGroup_UI();">Add Group</a>
				</div>
				<div id="tools_UI" class="option">
				<?php require_once("../DHTML/addPagesUILoader.html"); ?>	
				</div>

			</div>
	</td>
	<td id="frame_content_edit" style="padding: 0 20px 0 20px">
		<div id="links_groups">
			<?php require ('../groups_block_list.php'); ?>
		</div>

		<p id="lesson1_link1" style="position: absolute; top: 20px; left: 730px; width: 40px; height: 60px; border-width: 3px 3px 0 0; border-color: #000; border-style: solid;"></p>
		<p id="lesson1_n" style="background-color: #0f0; border: 1px #000 solid; text-align: center; line-height: 92px; position: absolute; top: 70px; left: 650px; height: 56x; width: 20px;">1</p>		
		<p id="lesson1" style="position: absolute; top: 70px; left: 670px; border: 1px #000 solid; padding: 10px; width: 250px; background-color: #fff;">
		You can drag groups to reorder them.  <br/>When you drag a group, a line <br/>will appear between rows; this guide<br/> helps you put a group in a new row.  
		</p>
		
		<p id="lesson2_link1" style="position: absolute; top: 147px; left: 380px; height: 40px; width: 140px; border-width: 3px 3px 0 0; border-color: #000; border-style: solid;"></p>
		<p id="lesson2_n" style="background-color: #0f0; border: 1px #000 solid; text-align: center; line-height: 92px; position: absolute; top: 190px; left: 420px; height: 56x; width: 20px;">2</p>		
		<p id="lesson2" style="position: absolute; top: 190px; left: 440px; border: 1px #000 solid; padding: 10px; width: 240px; background-color: #fff;">You can move links, too.  <br/>When you drag one, you can <br/>create a new column of links by <br/>dropping it in the space to the right.
		</p>
		
		<p id="lesson3_link1" style="position: absolute; top: 177px; left: 123px; width: 166px; height: 120px; border-width: 3px 3px 0 0; border-color: #000; border-style: solid;"></p>
		<p id="lesson3_n" style="background-color: #0f0; border: 1px #000 solid; text-align: center; line-height: 56px; position: absolute; top: 300px; left: 170px; height: 56x; width: 20px;">3</p>
		<p id="lesson3" style="position: absolute; top: 300px; left: 190px; border: 1px #000 solid; padding: 10px; width: 230px; background-color: #fff;">The icons in the upper-right corner<br/> edit or delete the page / group / link.
		</p>
		
		<p id="lesson4_link1" style="position: absolute; top: 84px; left: 133px; width: 24px; height: 320px; border-width: 3px 3px 0 0; border-color: #000; border-style: solid; z-index: 100;"></p>
		<p id="lesson4_n" style="background-color: #0f0; border: 1px #000 solid; text-align: center; line-height: 56px; position: absolute; top: 390px; left: 159px; height: 56x; width: 20px;">4</p>
		<p id="goto" style="position: absolute; top: 390px; left: 180px; border: 1px #000 solid; padding: 10px; width: 200px; background-color: #fff;"><a href="intro_account.php#fragment-2">We have a convenient tool to<br/>add bookmarks to your page.</a>
		</p>		
		
	</td>
	<td id="frame_banner">
		<?php require_once('../banner.php'); ?>
	</td>
</tr>

<tr>
	<td id="frame_footer" colspan="3">
		<?php require_once('../footer.php'); ?>
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