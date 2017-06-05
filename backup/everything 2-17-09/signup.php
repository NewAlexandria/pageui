<?php
session_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>PageUI - Getting Things Moving</title>
	<meta name="generator" content="BBEdit 8.7" />
</head>
<body>


<span style="color: red;"><?php echo $_GET['fail'] ?></span>

<p>
Intro stuff
</p>



<form action="config/users.php" method="get">

<table border="0">
	<tr>
		<td>Username</td>
		<td><input name="username" id="username" type="text" size="20" maxlength="40" tabindex="1" value="<?php echo $_GET['username'] ?>" /></td>
	</tr>
	<tr>
		<td>Password</td>
		<td><input name="password" id="password" type="password" size="20" maxlength="40" tabindex="2" /></td>
	</tr>
	<tr>
		<td>Password confirmation</td>
		<td><input name="password_c" id="password_c" type="password" size="20" maxlength="40" tabindex="3" /></td>
	</tr>
	<tr>
		<td>Email address</td>
		<td><input name="email" id="email" type="text" size="30" maxlength="100" tabindex="4" value="<?php echo $_GET['email'] ?>" /></td>
	</tr>
</table>


<table style="width: 100%">
	<tr>
		<td style="color: red; text-align: center;">Red Pill</td>
		<td style="text-align: center; color: blue;">Blue Pill</td>
	</tr>
	<tr><td colspan="2" align="center"><button type="submit" id="submit" value="1">Go</button></td></tr>
</table>

<input name="proc" type="hidden" value="add" />


</form>



</body>
</html>
