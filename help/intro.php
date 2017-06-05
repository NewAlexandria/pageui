<?php
session_start();

require('../config/keys.php');
require_once('../config/db.php');

// screen for whether they are logged in
if ($_SESSION['user_id']=="") {

	if ($_COOKIE['maintain'] != "") {
		header("Location: config/login.php?page=".urlencode($site_uri.$site_path)."intro.php&x=login&user_id=".$_COOKIE['maintain']);
	} else {
		header("Location: ".$home);	
		exit;
	}
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Your PageUI - Knowing the layout</title>
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

		<div id="frame_pages">
			<div id="login">
				<?php  require_once('../config/login_front.php');  ?>
			</div>
	
			<div id="frame_pages_include">
			<?php require_once('../pages.php'); ?>
			</div>
		</div>
		<div id="content_floater">
			<div class="option"><a href="edit.php">Edit Links</a></div>
		</div>

	</td>

	<td id="frame_content" style="padding: 0 20px 0 20px">
		<div id="links_groups">
			<?php require ('../groups_block_list_tables.php'); ?>
		</div>
				
		<p id="lesson1" style="background-color: #0f0; border: 1px #000 solid; text-align: center; line-height: 56px; position: absolute; top: 30px; left: 200px; height: 56px; width: 20px;">1</p>
		<p id="lesson1_link1" style="position: absolute; top: 88px; left: 120px; width: 200px; height: 30px; border-width: 0 3px 3px 0; border-color: #000; border-style: solid;"></p>
		<p id="lesson1" style="background-color: #fff; position: absolute; top: 30px; left: 220px; border: 1px #000 solid; padding: 10px; width: 200px;">
		Your pages are on the left.<br />  We started you off with one.  
		</p>

		<div id="lesson2_link1" style="position: absolute; top: 30px; left: 720px; height: 77px; border-width: 3px 3px 0 0; border-color: #000; border-style: solid;"></div>
		<p id="lesson2_n" style="background-color: #0f0; border: 1px #000 solid; text-align: center; line-height: 74px; position: absolute; top: 90px; left: 610px; height: 74x; width: 20px;">2</p>
		<p id="lesson2" style="position: absolute; top: 90px; left: 630px; border: 1px #000 solid; padding: 10px; width: 180px; background-color: #fff;">
		We've given you a few <br /> groups to start, but you can <br />delete them all if you like.
		</p>
		
		<p id="lesson3_link1" style="position: absolute; top: 170px; left: 290px; height: 30px; width: 100px; border-width: 3px 3px 0 0; border-color: #000; border-style: solid;"></p>
		<p id="lesson3_n" style="background-color: #0f0; border: 1px #000 solid; text-align: center; line-height: 56px; position: absolute; top: 200px; left: 310px; height: 56px; width: 20px;">3</p>
		<p id="lesson3" style="position: absolute; top: 200px; left: 330px; border: 1px #000 solid; padding: 10px; width: 220px; background-color: #fff;">The links in the <b>Unsorted</b> group<br /> are available on every page.
		</p>
		
		<p id="lesson4_link1" style="position: absolute; top: 285px; left: 140px; width: 60px; border-width: 3px 0 0 0; border-color: #000; border-style: solid;"></p>
		<p id="lesson4_n" style="background-color: #0f0; border: 1px #000 solid; text-align: center; line-height: 38px; position: absolute; top: 270px; left: 179px; height: 38px; width: 20px;">4</p>
		<p id="lesson4" style="position: absolute; top: 270px; left: 200px; border: 1px #000 solid; padding: 10px; width: 320px; background-color: #fff; "><a href="intro_edit.php">Next step: editing your links, groups, and pages</a>
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

// ]]>
</script>

</body>
</html>