<?php
session_start();

include_once('../config/keys.php');
include_once('../config/db.php');

// retain the debug information in a var; for printing in the footer, and so that var can be built during the execution of this page

if ($_SESSION['DEBUG']==1) { 
/// BAD session debug should not be done without server-side validation
	$err = $_SESSION['errtxt'];
	$_SESSION['errtxt'] = '';
} 

// screen for whether they are logged in
if (!$_SESSION['aid']) {
	header("Location: index.php"); //.$home);	
	exit;
}
	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>PageUI Reports</title>
	<meta name="generator" content="BBEdit 9.0" />
</head>
<body>
<h2>System Overview - Groups</h2>

<p><?php  require_once('login_front.php');  ?></p>

<?php // total logins by user
$sql = "SELECT COUNT(*), user_id FROM UserActivity GROUP BY user_id";
?>

<?php // total failed logins by IP
$sql = "SELECT COUNT(*), IPaddress FROM UserActivity WHERE success = 0 GROUP BY IPaddress";
?>

<table>
<tr><td style="border-width: 0 0 1px 0; border-color: #000; border-style: solid;">Title</td><td style="border-width: 0 0 1px 0; border-color: #000; border-style: solid;">Total # of links</td></tr>
<?php // total group counts
$sql = "SELECT G.Title, COUNT(*) total FROM Links L INNER JOIN Groups G ON L.group_id = G.id GROUP BY G.Title ORDER BY G.Title -- total # of links for a group name";
$result = mysql_query ( $sql );
$errtxt .= '<br />'.$sql;

if ( mysql_num_rows ( $result ) > 0 ) {
	while ($r = mysql_fetch_assoc ( $result ) ) { ?>
		<tr><td><?php echo $r['Title'] ?></td><td><?php echo $r['total'] ?></td></tr>
<?php }
}

?>
</table>

</body>
</html>
