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
	<link rel="stylesheet" rev="stylesheet" href="theme/ui.all.css" />
<?php	require('script_versions.php'); ?>

	  <style type="text/css" title="text/css">
	  /* <![CDATA[ */
		#accordion
		{
			font-size: 12px;
			margin: 0 20px 20px 20px;
		}
		
		#accordion div {
			max-height: 400px; overflow: scroll;
		}

	  /* ]]> */
	  </style>
	  <script>
	  $(document).ready(function(){
		$('#accordion').accordion({
				header: "h3"
			});
	  });
	  </script>
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
		<p style="margin: 4px 0 10px 10px; font-weight: bold;">Making your PageUI links your homepage is easy!</p>
		<div id="accordion">
			<h3><a href="#">Internet Explorer</a></h3>
			<div>
				You should just be able to <a href="javascript:this.style.behavior='url(#default#homepage)';this.setHomePage('http://www.pageui.com/home.php');">click here to make PageUI your homepage</a>
				<p>Manual Instructions to Change Your Home Page</p>
				<p>1.
				Go to the Web page you want to make your home page.</p>
				<p>2.
				On the Internet Explorer Tools menu, click Internet Options.</p>
				<p>3.
				In the Internet Options box, on the General tab, enter the link http://www.pageui.com/home.php  (If you navigate to the home page itself then you may click the Use Current button.)</p>
				
				<img src="images/homepage/HomePage1.gif" width="315" height="191" />
				
				<p>4.
				Click OK.

				</p>
			</div>
			<h3><a href="#">Firefox</a></h3>
			<div>
				<p>Click on TOOLS on the menu bar at the top of the Firefox screen, then click on OPTIONS
				see fig 1.1 below for a screenshot: </p>
				
				<img src="images/homepage/tools-options.gif" width="433" height="297" /> 
				
				<p>After clicking options you will see the Firefox Options dialogue box as shown below in fig 1.2</p>
				
				<img src="images/homepage/change-homepage.gif" width="479" height="428" />
				 
				<p>Type the address of the webpage that you would like to use as your new homepage into the box provided (see Fig 1.2 above) and click the OK button. 
				
				If you do this from your PageUI home page just click the Use Current Page button then simply click on the OK button.   Otherwise, enter http://www.pageui.com/home.php

				</p>
			</div>
			<h3><a href="#">Safari for PC</a></h3>
			<div>
				<p>Change Default Home Page in Safari for Windows</p>
				<img src="images/homepage/wer_thumb.png" width="300" height="375" />
				<p>Click on Edit then choose Preferences.  Here you can change the default homepage from Apple's default. Just clear out the address in the Home page box.</p>
				<img src="images/homepage/sa_thumb1.png" width="509" height="499" />
				<p>Enter the page http://www.pageui.com/home.php </p>
				<img src="images/homepage/confirm_thumb.png" width="500" height="188" />
				
			</div>
			
			<h3><a href="#">Safari for Mac</a></h3>
			<div>
				<p>Click on Safari in your Safari menu, located at the top of your screen. When the drop-down menu appears, choose Preferences.</p>
				<img src="images/homepage/safaritabs2.jpg" width="400" height="326" />
				<p>Select General from the Preferences menu, which is now overlaying your browser window. Once General is selected, you will notice a section labeled Home Page in the main window of the Preferences dialog.</p>
				<p>Directly to the right of the "Home Page" label is an edit field containing your current home page URL. Change this field to read: http://www.pageui.com/home.php.</p>
				<p>Directly below this edit field you will see a button labeled Set to Current Page. If you prefer, you may navigate to your PageUI home page, then click this button to make it your home page.</p>
				<p>Once you have completed your changes, close the Safari Preferences dialog by clicking the red circle/x located in the top left hand corner of the box.</p>
				<img src="images/homepage/safhomepage3.jpg" width="250" height="238" />
			</div>
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
