<?php
session_start();

// retain the debug information in a var; for printing in the footer, and so that var can be built during the execution of this page

if ($_SESSION['DEBUG']==1) { 
/// BAD session debug should not be done without server-side validation
	$err = $_SESSION['errtxt'];
	$_SESSION['errtxt'] = '';
} 


// screen for whether they are logged in
if ($_SESSION['user_id']) {
	header("Location: home.php"); //.$home);	
	exit;
}
if ($_COOKIE['maintain'] != "") {
	header("Location: config/login.php?x=login&user_id=".$_COOKIE['maintain']);
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>PageUI home</title>
	<meta name="generator" content="BBEdit 9.0" />
</head>
<body>


<table style=" width: 100%; height: 500px;">
<tr><td style=" text-align: center; width: 70%;">
	<div>Welcome to PageUI</div>
	
	<div>Your bookmarks, made intelligent.</div>
</td><td valign="top">
<form action="config/login.php" method="post">

	<div style="display: inline;">Username:</div>
	<div style="display: inline;"><input name="username" id="username" type="text" size="15" maxlength="40" tabindex="1" /></div>
	<br />
	<div style="display: inline;">Password:</div>
	<div style="display: inline;"><input name="password" id="password" type="password" size="15" maxlength="40" tabindex="2" /></div>
	<br />
	<button type="submit" id="submit" value="Login" tabindex="4">Login</button>
	<br />
	<input id="make_maintain" type="checkbox" value="1" name="make_maintain" tabindex="3" /><span style="font-size: 10pt;">: Keep me logged in</span>
	<input name="x" type="hidden" value="login" />
</form>
<div><a href="signup.php">Ready to get setup?  Start here.</a></div>

</td></tr>
</table>



</body>
</html>
