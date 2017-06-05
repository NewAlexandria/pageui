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
<h2>Reports Core</h2>
<p><a href="http://www.pageui.com/reports/login.php?x=logout">Logout</a></p>

<ul>
<li><a href="overview_groups.php">Overview: Groups</a></li>
<li><a href="overview_links.php">Overview: Links</a></li>
<li> <div style="display: inline;"><form action="user.php" method="get">User ID: <input name="user_id" type="text" value="<?php if ($_REQUEST['user_id']) { echo $_REQUEST['user_id']; } ?>" size="2" maxlength="4" />  <button type="submit" id="View">View</button></form></div></li>

</body>
</html>
