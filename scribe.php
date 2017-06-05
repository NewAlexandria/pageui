<?php
session_start();

require('config/keys.php');
require_once('config/db.php');

// screen for whether they are logged in
if ($_SESSION['user_id']=="") {
	if ($_COOKIE['maintain'] != "") {
		header("Location: config/login.php?x=login&user_id=".$_COOKIE['maintain']);
	} else {
		header("Location: home.php");	
		exit;
	}
}

if ( is_numeric($_REQUEST['link_id']) ) {
	$sql = "INSERT INTO LinkActivity VALUES (".$_REQUEST['link_id'].", '".date("Y-m-d H:m:s")."')";
	$result = mysql_query ( $sql );

	header("Location: ".$_REQUEST['URL']);	
	exit;
} else {
	header("Location: home.php");	
	exit;
}

?>