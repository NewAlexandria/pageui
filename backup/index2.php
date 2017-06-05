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
	<title>PageUI home</title>
	<meta name="generator" content="BBEdit 9.0" />
</head>
<body>
<div style="text-align: right;"><a href="config/login_front.php?new=y">Already Have an Account - Click Here</a></div>

<div style="width: 100% text-align: center;">
	<div style="width: 60%; margin: 30px 0 0 20%;">
		<div style="text-align: center; width: 100%; font-size: 20px; font-family: sans-serif; font-weight: bold;">Welcome to "Page User Interface" <i>Beta</i></div>
		
		<div style="background-color: #E2EEF9; width: 250px; padding: 5px 0 5px 10px; margin: 20px 0 0 0; font-size: 14pt; font-style: italic; font-family: sans-serif;">What does this site do?</div>
		<div style="padding: 15px 0 0 10px; line-height: 22px;  font-family: sans-serif;">Helps you organize and save your Bookmarks / Favorites / Links on one extremely fast website for quick access on any Internet connection from any computer or cell phone.</div>
		
		<div style="background-color: #E2EEF9; width: 280px; padding: 5px 0 5px 10px; margin: 20px 0 0 0; font-size: 14pt; font-style: italic; font-family: sans-serif;">How does this benefit me?</div>
		<div style="padding: 15px 0 0 10px; line-height: 22px; font-family: sans-serif;">This site saves you Time &amp; Energy by safely storing all of your personal / business / educational links in a clean simple format and will definitely change the way you surf the Internet today.</div>
		
		<p style="text-align: center; line-height: 20px;"><span style="color: #fff;">Click Here to see an example<br />
		then<br /></span>
		Open Up Your Account Below
		
		<form action="config/accounts.php" method="get" id="signup_form">
		
		<table border="0" style="margin: auto; background-color: #F9F8E1; padding: 7px 12px 7px 12px;">
			<tr>
				<td>Username</td>
				<td><input name="username" id="username" type="text" size="20" maxlength="40" tabindex="1" value="<?php echo $_GET['username'] ?>" /></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input name="password" id="password" type="password" size="30" maxlength="40" tabindex="2" /></td>
			</tr>
			<tr>
				<td>Password confirmation</td>
				<td><input name="password_c" id="password_c" type="password" size="30" maxlength="40" tabindex="3" /></td>
			</tr>
			<tr>
				<td>Email address</td>
				<td><input name="email" id="email" type="text" size="30" maxlength="100" tabindex="4" value="<?php echo $_GET['email'] ?>" /></td>
			</tr>
			<tr><td colspan="2" align="center"><button type="submit" id="submit" value="1">Setup my account</button></td></tr>
		</table>
		
		<input name="proc" type="hidden" value="add" />
		<input name="return" type="hidden" value="signup.php" />
		<input name="page" type="hidden" value="intro.php" />
		
		</form>
		</p>
	</div>
</div>

</body>
</html>
