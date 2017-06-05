<?php
session_start();

include_once('../config/keys.php');
include_once('../config/db.php');

require_once('login_lib.php');


$DEBUG = 0;

$err = $_REQUEST['err'];
$errtxt = '';
if ( $_REQUEST['page'] ) {
	$page = $_REQUEST['page'];
} else {
	$page = "core.php";
}

// we tag this onto the end of redirects / Location: calls.  
/// INFRA:  All of them, eventually, if not already.  Now it only draws on jsoncallback.  Eventually it should search for $_REQUEST['parcel'] which is a JSON object containing {page, return, passthrough} being where to go on success, where to go on fail, and what varibles to pass on.

$uri_append = '';
if ( $_REQUEST['jsoncallback'] ) {
	$uri_append = "jsoncallback=".$_REQUEST['jsoncallback'];
}

$password = ReturnSecureString($_REQUEST['password']);
$username = ReturnSecureString($_REQUEST['username']);

// setcookie("user_id",Ê5);

if ($DEBUG==1) { echo 'page open<br/>'; }

/// SEC check the sender page, or there needs to be a higher level permission to get at this file.

if ($_REQUEST['x'] == "login") {

	$errtxt .= 'into default<br/>'; 
	if ($password) {		
		$sql = "SELECT * FROM Admins WHERE active = 1 AND password LIKE '".$password."' AND username LIKE '".$username."'";
		$result = mysql_query($sql);
		$errtxt .= $sql.'<br/>'.mysql_num_rows($result); 
		
		// if corrupt accounts; needs to trigger admin notification
		if (mysql_num_rows($result)>1) {
			Leave("You've gone duplicate!  You've beat our error-checkers to the punch.  Please let the admin know.  Thanks.", 'reports/'.$page ); 
			// trigger_error("Your account is unavailable at this time.  Our support staff is now aware of it.  Please feel free to contact us to determine the status of resolution.");
			
		} elseif ( (mysql_num_rows($result)==1) ) {
			$curRow = mysql_fetch_assoc($result);
			
			doAdminLogin ( $curRow );		
		} else {
			$err=1;
			$errtxt .= 'bad login <br/>'; 
			// trigger_error("You've entered the wrong information to login.  Try again.");

			// insert UserActivity
			$sql = "INSERT INTO AdminsActivity (admin_id, IPaddress, success, datetime) VALUES (".$_SESSION['aid'].", '".$_SERVER["REMOTE_ADDR"]."', 0 '".date("Y-m-d H:m:s")."')";
			$result = mysql_query($sql);

			$errtxt .= $sql.'<br/>'.mysql_affected_rows(); 
		}	

		// set which page to go to after login;
		if ($_REQUEST['page']) { 
			$page = "reports/".$_REQUEST['page'];
		} else { 
			$page = "reports/core.php";
		}
			
		if (!isset($err)) {
			// only append if admin user
			if ($_SESSION['DEBUG'] == 1) {
				$_SESSION['errtxt'] = $errtxt.'login-sent';
			}

			Leave("Login successful", $page.$uri_append);
			exit;
		} else {
			// only append if admin user
			if ($_SESSION['DEBUG'] == 1) {
				$_SESSION['errtxt'] = $errtxt;
			}
			header("Location: login_front.php?err=".$err.'&'.$uri_append);
			exit;
		}
	} else {
		// only append if admin user
		if ($_SESSION['DEBUG'] == 1) {
			$_SESSION['errtxt'] = $errtxt;
		}
		
		if ($_REQUEST['return']) {
			header("Location: ".$_REQUEST['return'].'?msg=no+login+credentials&'.$uri_append );
		} else {
			header("Location: login_front.php".'?msg=No+login+credentials&'.$uri_append );
		}
		
		exit;
	
	}
	
} elseif ($_REQUEST['x'] == 'logout') {

		$_SESSION = array(); 
		session_destroy(); 
		
		// redirect to useful logout page
		// header("Location: https://www.quarterfiler.com");	
		
		// only append if admin user
		if ($_SESSION['DEBUG'] == 1) {
			$_SESSION['errtxt'] = $errtxt;
		}
		header("Location: login_front.php?err=logout");	
		exit;

} else {
	// really, then just send them to the login page

	// only append if admin user
	if ($_SESSION['DEBUG'] == 1) {
		$_SESSION['errtxt'] = $errtxt;
	}
	header("Location: login_front.php");	
	exit;

}
