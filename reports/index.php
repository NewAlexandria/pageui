<?php
session_start();

// retain the debug information in a var; for printing in the footer, and so that var can be built during the execution of this page

if ($_SESSION['DEBUG']==1) { 
/// BAD session debug should not be done without server-side validation
	$err = $_SESSION['errtxt'];
	$_SESSION['errtxt'] = '';
} 

/*
// screen for whether they are logged in
if ($_SESSION['user_id']) {
	header("Location: home.php"); //.$home);	
	exit;
}
if ($_COOKIE['maintain'] != "") {
	header("Location: config/login.php?x=login&user_id=".$_COOKIE['maintain']);
}
*/
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>PageUI Reports</title>
	<meta name="generator" content="BBEdit 9.0" />
</head>
<body>
Reports Login
	<div class="login_cell" style="margin: 10px; border: 3px #daa double; padding: 7px; width: 300px;">
		<form action="login.php" method="post">
		<table border="0" cellspacing="0" cellpadding="0" style="border: 1 black dotted;">
			<tr>
				<td><span class="text">Username:</span></td>
				<td><input type="text" name="username" size="15" maxlength="100" tabindex="1" accesskey="u"></td>
			</tr><tr>
				<td><span class="text">Password:</span></td>
				<td><input type="password" name="password" size="15" maxlength="40" tabindex="2" accesskey="p"></td>
			</tr><tr>
				<td><input type="submit" name="submit" value="Login" tabindex="4"></td>
			</tr>
		</table>
		<input name="x" type="hidden" value="login" />
		</form>
	</div>

</body>
</html>
