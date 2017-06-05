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

$sql = "SELECT * FROM Users WHERE id = ".$_SESSION['user_id'];
$errtxt .= '<br />1'.$sql;
$result = mysql_query ( $sql );
$r = mysql_fetch_assoc ( $result );

$email = $r['email'];
$name_first = $r['name_first'];
$name_last = $r['name_last'];


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Your PageUI Links</title>
	<meta name="generator" content="BBEdit 8.7" />
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

	  /* ]]> */
	  </style>
	  <script type="text/javascript">
	  $(document).ready(function(){
		$('#tabs').tabs();
		$("html,body").scrollTop( 1 );
	  });
	  </script>
</head>
<body>

<table style=" width: 100%;" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td valign="top" id="left_col">
		<div id="frame_header">
		<?php require_once('../header.php'); ?>
		</div>

		<?php if ( $_SESSION['show_home'] == 'on' ) { include_once('../set_homepage.php'); } ?>
		<?php if ( $_SESSION['show_dropper'] == 'on' ) { include_once('../set_bookmarker.php'); } ?>
		
		<div id="frame_pages">
			<div id="login">
				<?php  require_once('../login_front.php');  ?>
			</div>
	
			<div id="frame_pages_include">
			<?php require_once('../pages.php'); ?>
			</div>
		</div>
<!-- 
		<div id="content_floater">
			<a href="edit.php">Edit</a>
		</div>
 -->
	</td>

	<td id="frame_content" >
	
		<div id="tabs">
			<ul>
				<li><a href="#fragment-1"><span>Account</span></a></li>
				<li><a href="#fragment-2"><span>Bookmarking Tool</span></a></li>
			</ul>
			
			<div id="fragment-1">
				More Soon.
				<form action="accounts.php" method="POST" id="account_update">
					<div class="inputs">
						<div>Account Information</div>
						<table border="0" cellspacing="0" cellpadding="0">
							<tr><td>Email:</td><td><input name="email" id="email" type="text" size="25" maxlength="100" tabindex="1" value="<?php echo $email; ?>" /></td></tr>
							<tr><td>Password:</td><td><input name="password" id="password" type="password" size="25" maxlength="40" tabindex="1" /></td></tr>
							<tr><td>Password:<br />(confirm)</td><td valign="top"><input name="password_confirm" id="password_confirm" type="password" size="25" maxlength="40" tabindex="1" /></td></tr>
						</table>
					</div>
					
<!-- 
					<div class="inputs">
						<table border="0" cellspacing="0" cellpadding="0">
							<div>Personal Information</div>
							<tr><td>First Name:</td><td><input name="name_first" id="name_first" type="text" size="25" maxlength="45" tabindex="1" value="<?php echo $name_first; ?>" /></td></tr>
							<tr><td>Last Name:</td><td><input name="name_last" id="name_last" type="text" size="25" maxlength="45" tabindex="1" value="<?php echo $name_last; ?>" /></td></tr>
						</table>
					</div>
 -->
					<button type="submit" id="Update Account">Update Account</button>
					<input name="proc" type="hidden" value="update" />
					<input name="page" type="hidden" value="config/account.php" />
				</form>
				
				<div style="margin: 20px 0 0 0;"><a href="accountclose.php">Close My Account</a></div>
			</div>
			
			<div id="fragment-2">
				<p>Make your life easier with the PageUI bookmark tool!  Drag this link to your browser's link bar: </p> 
				<a href="javascript:(function(){var%20pageui_s=document.createElement('script');pageui_s.setAttribute('src','http://www.pageui.com/jquery-1.2.5.pack.js');document.getElementsByTagName('head')[0].appendChild(pageui_s);var%20pageui_b=document.getElementsByTagName('body')[0];%20var%20pageui_s=document.createElement('script');pageui_s.charset='UTF-8';pageui_s.src='http://www.pageui.com/test/link_dropper8h.js';pageui_b.appendChild(pageui_s);})();" style="background-color:#eef; border:2px groove #55a; padding: 2px 5px 2px 5px; margin-top:5px; color:black; font-family:sans-serif; font-size:10pt; text-decoration:none; ">Add to PageUI</a>
				
				<!-- javascript:(function(){var%20pageui_s=document.createElement('script');pageui_s.setAttribute('src','http://www.pageui.com/jquery-1.2.5.pack.js');document.getElementsByTagName('head')[0].appendChild(pageui_s);var%20pageui_b=document.getElementsByTagName('body')[0];%20var%20pageui_s=document.createElement('script');pageui_s.charset='UTF-8';pageui_s.src='http://www.pageui.com/test/link_dropper8h.js';pageui_b.appendChild(pageui_s);})(); -->
				<p>IE Users: Right click the above link and select "Add to Favorites" or "Bookmark this Link" (depending upon your current browser).
				</p>
			</div>
		</div>


		
		<p id="lesson1_link1" style="position: absolute; top: 97px; left: 257px; height: 150px; border-width: 3px 3px 0 0; border-color: #000; border-style: solid;"></p>
		<p id="lesson1_n" style="background-color: #0f0; border: 1px #000 solid; text-align: center; line-height: 56px; position: absolute; top: 250px; left: 190px; height: 56x; width: 20px;">&radic;</p>
		<p id="lesson1" style="position: absolute; top: 250px; left: 210px; border: 1px #000 solid; padding: 10px; width: 230px; background-color: #fff;">Add the bookmark tool and you're ready to go!
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


</body>
</html>
