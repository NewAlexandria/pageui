<?php
session_start();

require_once('../config/keys.php');

// screen for whether they are logged in
if ($_SESSION['user_id']=="") {

	if ($_COOKIE['maintain'] != "") {
		header("Location: config/login.php?x=login&user_id=".$_COOKIE['maintain']);
	} else {
		header("Location: ".$home);	
		exit;
	}
}

require_once('../config/db.php');
$username = $_SESSION['username'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>PageUI User's Guide</title>
	<meta name="generator" content="BBEdit 9.0" />
	<link rel="stylesheet" rev="stylesheet" href="../beta.css" />
	<link rel="stylesheet" rev="stylesheet" href="../theme/ui.all.css" />
<?php	require('../script_versions.php'); ?>

	  <style type="text/css" title="text/css">
	  /* <![CDATA[ */
		#tabs
		{	
			font-size: 12px;
			margin: 0 20px 20px 20px;
		}
		
		#tabs div {
			
		}

		#accordion
		{
			font-size: 12px;
			margin: 0 20px 20px 20px;
		}
		
		#accordion div {
			max-height: 800px; overflow: scroll;
		}
	  /* ]]> */
	  </style>
	  
	  <script>
	  $(document).ready(function(){
		$('#tabs').tabs();
		$('#accordion').accordion({
				header: "h3"
			});
	  });
	  </script>
	  
</head>
<body>
<style type="text/css" title="text/css">
/* <![CDATA[ */
	li { margin: 5px; }
/* ]]> */
</style>
<script type="text/javascript">
<!-- 

-->
</script>

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
		<div id="content_floater">
			<div class="option"><a href="edit.php">Edit Links</a></div>
		</div>

	</td>

	<td id="frame_content">
			<div id="services_header" >
				<table style="width: 100%;" cellpadding="0" cellspacing="0"><tr><td>
				<?php  require_once('../search_head.php');  ?>
				</td><td>
				<?php if ( $_SESSION['tutorials'] == 'on' || $_SESSION['tutorials'] == '' ) { include_once('../tutorial.php'); } ?>
				</td></tr></table>
			</div>

		<div id="tabs">
			<ul>
				<li><a href="#fragment-1"><span>First-round FAQ</span></a></li>
				<li><a href="#fragment-2"><span>Flowchart</span></a></li>
<!-- 				<li><a href="#fragment-3"><span>Homepage Setup</span></a></li> -->
			</ul>
				
			<div id="fragment-1">
				<h3>First-round FAQ</h3>
				
				<ul>
					<li>If anthing below, or anything unmentioned, is critical to you - <a href="mailto:admin@dominavi.com">please email us</a>.</li>
					<li style="background-color: #ddd; padding: 3px 3px 3px 6px">In time you'll be able to both import, and export, your bookmarks.</li>
					<li>The "Unsorted" group is the same set of links, available on every page.  It's a temporary space that available everywhere.</li>
					<li style="background-color: #ddd; padding: 3px 3px 3px 6px">For now, the system works best if you drag links a little more slowly than you're used to in regular operating-system windows.</li>
					<li>A little precision is needed when dragging links and groups.  We're continuing to improve this, too.  </li>
					<li style="background-color: #ddd; padding: 3px 3px 3px 6px">Some older browsers handle our beta-code slowly.  See if upgrding solves any lag issues you may have.</li>
					<li>When adding a new group from LinkSaver, it goes into the last page you were using.</li>
					<li style="background-color: #ddd; padding: 3px 3px 3px 6px">If you manually update a link URL, for now you need to keep the http://</li>
					<li>For now, don't delete all your pages.</li>
					<li style="background-color: #ddd; padding: 3px 3px 3px 6px">Intentional 'stress testing' the system (like entering javascript code for a link title) can disable your account.  Solution: don't goof around like that yet. ;)</li>
		<!-- 			<li></li> -->
		
					<p style="margin-top: 20px;">
					<a href="intro.php" style="color: blue;">See a live help guide for the site</>
					</p>
				</ul>
			</div>
			<div id="fragment-2">
				<div style="width: 550px; margin: 20px auto 20px auto; z-index: 11;" ><img src="../images/Activity%20Cycle%20Diagram2-t65.png" width="546" height="383" /></div>
			</div>
		</div>
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


</body>
</html>