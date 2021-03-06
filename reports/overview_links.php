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

if ( $_REQUEST['groups'] ) {
	$gr = ', G.Title';
} else {
	$gr = '';
}

if ( $_REQUEST['days'] ) {
//	$sql = "SELECT COUNT(*) total, URL, U.username, G.Title FROM LinkActivity LA INNER JOIN Links L ON LA.link_id = L.id INNER JOIN Users U ON L.user_id = U.id INNER JOIN Groups G ON L.group_id = G.id WHERE LA.datetime > DATE_ADD(CURDATE(), INTERVAL '-".$_REQUEST['days']."' DAY) GROUP BY URL, U.username, G.Title";
	$sql = "SELECT COUNT(*) total, URL".$gr." FROM LinkActivity LA INNER JOIN Links L ON LA.link_id = L.id INNER JOIN Users U ON L.user_id = U.id INNER JOIN Groups G ON L.group_id = G.id WHERE LA.datetime > DATE_ADD(CURDATE(), INTERVAL '-".$_REQUEST['days']."' DAY) GROUP BY URL".$gr."";

} else {
	$sql = "SELECT COUNT(*) total, URL, U.username".$gr." FROM LinkActivity LA INNER JOIN Links L ON LA.link_id = L.id INNER JOIN Users U ON L.user_id = U.id INNER JOIN Groups G ON L.group_id = G.id GROUP BY URL".$gr."";
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

<table><tr>
	<td valign="top" style="padding:3px 0 0 0;">View: <a href="overview_links.php">All</a> |</td>
	<td valign="top"> <div style="display: inline;"><form action="overview_links.php" method="get">Past <input name="days" type="text" value="<?php if ($_REQUEST['days']) { echo $_REQUEST['days']; } else { echo 7; }?>" size="2" maxlength="4" /> days <button type="submit" id="View">View</button>
	</form></div></td>
	<td valign="top" style="padding:3px 0 0 20px;"><?php if ($gr) { ?><a href="overview_links.php?days=<?php echo $_REQUEST['days'] ?>">Hide Groups</a><?php } else { ?><a href="overview_links.php?groups=1&days=<?php echo $_REQUEST['days'] ?>">Factor Groups</a><?php } ?></td>
</tr></table>

<table>
<tr><td style="border-width: 0 0 1px 0; border-color: #000; border-style: solid;">totals</td><td style="border-width: 0 0 1px 0; border-color: #000; border-style: solid;">URL</td><td style="border-width: 0 0 1px 0; border-color: #000; border-style: solid;">Groups</td></tr>
<?php // total group counts
$result = mysql_query ( $sql );
$errtxt .= '<br />'.$sql;

if ( mysql_num_rows ( $result ) > 0 ) {
	while ($r = mysql_fetch_assoc ( $result ) ) { ?>
		<tr><td><?php echo $r['total'] ?></td><td><?php echo $r['URL'] ?></td><td><?php echo $r['Title'] ?></td></tr>
<?php }
}

?>
</table>

</body>
</html>
