<?php
session_start();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Your PageUI - getting things moving</title>
	<meta name="generator" content="BBEdit 9.0" />
	<link rel="stylesheet" rev="stylesheet" href="beta.css" />
	  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" type="text/javascript"></script>
	  <script type="text/javascript" src="jquery-ui-personalized-1.6rc6.min.js"></script>
	<script type="text/javascript">
	// <![CDATA[
		var tLSH = 0;
		function toggleLinkSaverHelp() {
		  if ( tLSH == 0 ) {
		  	$("#LinkSaverHelp p").toggle(); 
			$("#LinkSaverHelp").animate({ 
			  height: "540px"
			}, 800, function() {  } );
			tLSH = 1;
		  } else {
			$("#LinkSaverHelp").animate({ 
			  height: "145px",
			  overflow: 'hidden'
			}, 800, function() { $("#LinkSaverHelp p").toggle(); } );
			
			tLSH = 0;
		  }
		}
	// ]]>
	</script>
	<style type="text/css" title="text/css">
	/* <![CDATA[ */
		#bookmarklet:hover {
			background-color: #fff;
			
		}
	/* ]]> */
	</style>
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
			</div>
	
			<div class="search">
			</div>
	
			<div id="frame_pages_include">
			</div>

		</div>

	</td>
	<td id="frame_content" style="padding: 0 0px 0 10px; min-width: 800px">
		<div style="color: red; position: absolute; left: 600px; top: 20px; font-size: 18px;"><?php echo $_GET['fail'] ?></div>
		
		
		
		<div style="width: 560px; margin: 70px 0 0 auto; z-index: 11;" ><img src="images/Activity%20Cycle%20Diagram-t65-2.png" width="546" height="448" /></div>
		
		
		<div style="position:absolute; top: 5px;">
		<div style="width: 370px; margin: 10px auto 10px 7px;">
		PageUI will revolutionize the way you use bookmarks, and therefore the way you use the web.  <br />There's not much needed to get you going:
		</div>		
		
		<form action="config/accounts.php" method="get" id="signup_form" style="background-color: #EDEBE6; width: 390px; ">
		
		<table border="0" style="">
			<tr>
				<td>Username</td>
				<td><input name="username" id="username" type="text" size="30" maxlength="40" tabindex="4" value="<?php echo $_GET['username'] ?>" /></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input name="password" id="password" type="password" size="30" maxlength="40" tabindex="6" /></td>
			</tr>
			<tr>
				<td>Password confirmation</td>
				<td><input name="password_c" id="password_c" type="password" size="30" maxlength="40" tabindex="7" /></td>
			</tr>
			<tr>
				<td>Email address</td>
				<td><input name="email" id="email" type="text" size="30" maxlength="100" tabindex="8" value="<?php echo $_GET['email'] ?>" /></td>
			</tr>
			<tr><td colspan="2" align="center"><button type="submit" id="submit" value="1">Setup my account</button></td></tr>
		</table>
		
		<input name="proc" type="hidden" value="add" />
		<input name="return" type="hidden" value="signup.php" />
		<input name="page" type="hidden" value="home.php" />
		
		</form>
		</div>

		<div style="position: absolute; top: 290px; width: 19%; min-width:229px; margin: auto; background-color: #EDEBE6; overflow: hidden; height: 145px; z-index: 12;" id="LinkSaverHelp">
			<div style="text-align: center; background-color: #faa; padding: 3px; margin: 20px 0 17px 0; border-width: 1px 0 1px 0; border-color: #f77; border-style: solid;">So.... Before you dive in, <br/>add the LinkSaver bookmarklet to your browser's <a href="#" onclick="toggleLinkSaverHelp();">links bar</a>.</div>  
			<div id="bookmarklet" style="margin: auto; width: 170px; border: 1px #96a187 solid; background-color: #D6E1C7; text-align: center; font-size: 14px; padding: 6px 0 6px 0;"><a href="javascript:<?php require('link_dropper_bookmarklet.js'); ?>" style="padding: 2px 5px 2px 5px; margin-top: 5px; color: black; font-family: sans-serif; text-decoration: none;">PageUI LinkSaver</a>
			</div>
		
			<p style="display: none; padding: 0 0 0 12px">The links bar is the area right beneath the web page address.  In Firefox or Safari you can just drag PageUI LinkSaver to that area - then you're set.</p>  
			<p style="display: none; padding: 0 0 0 12px">If you have IE you'll need to right-click to add LinkSaver to your Favorites > Links.  If the Links bar is not visible, click the View menu, point to Toolbars, and then click Links.  If th elinks bar isn't positioned wel for you to use, first, right-click on any toolbar. If there is a checkmark in front of the "Lock the Toolbars" choice, click on the Lock the Toolbars option to remove the checkmark and unlock the bars for repositioning.</p>
		
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

<script type="text/javascript">
// <![CDATA[
$('#username').focus();
// ]]>
</script>

</body>
</html>