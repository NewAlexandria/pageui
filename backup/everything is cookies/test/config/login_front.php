<?phpsession_start();// receive logout landing// receive 'show' requestsif ($_SESSION['user_id']) { 	// login, be personable	/// put anythign here to check whether to show login, or show username		?>				<div class="login_cell">			<p>Ad Astra, <?php echo $_SESSION['username'] ?></p>			<p><a href="<?php echo $site_uri.$site_path; ?>config/account.php">Account</a> | <a href="<?php echo $site_uri.$site_path; ?>config/login.php?x=logout">Logout</a></p>		</div><?php } else {	// no login, give cel	?>	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"			"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">	<html xmlns="http://www.w3.org/1999/xhtml">	<head>		<title>PageUI Login</title>		<meta name="generator" content="BBEdit 9.0" />	</head>	<body style="padding: 5px;">	<h2 style="border-top: 2px #ddd solid;">PageUI</h2>	<?php ;	if ($_REQUEST['err'] == 'logout') { ?>		You have been successfully logged out.<BR /><BR />			<?php } else if ($_REQUEST['err'] == '1') { ?>		There was a problem with your login information.  Please try to login again.		<BR><BR>		If the problem persists, please contact the administrator.</font>	<?php } else if ($_REQUEST['err'] == '2') { ?>		There was a problem displaying the page.  Please try to login again.		<BR><BR>		If the problem persists, please contact the administrator.</font>	<?php } ?>		<div class="login_cell" style="margin: 10px; border: 3px #daa double; padding: 7px; width: 300px;">		<form action="login.php" method="post">		<table border="0" cellspacing="0" cellpadding="0" style="border: 1 black dotted;">			<tr>				<td><span class="text">Login:</span></td>				<td><input type="text" name="username" size="15" maxlength="20" tabindex="1" accesskey="p"></td>			</tr><tr>				<td><span class="text">Password:</span></td>				<td><input type="password" name="password" size="15" maxlength="30" tabindex="2" accesskey="p"></td>			</tr><tr>				<td><input id="make_maintain" type="checkbox" value="1" name="make_maintain" tabindex="3" /><span style="font-size: 10pt;">: Keep me logged in</span></td>			</tr><tr>				<td><input type="submit" name="submit" value="Login" tabindex="4"></td>			</tr>		</table>		<input name="x" type="hidden" value="login" />		</form>	</div>		<div><a href="../signup.php">Don't have an account, perhaps?  Register here.</a></div>	<BODY></HTML><?php }?>