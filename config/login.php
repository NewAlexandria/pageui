<?php
session_start();

require_once('keys.php');
require_once('db.php');

require_once('login_lib.php');


$DEBUG = 0;

$err = $_REQUEST['err'];
$errtxt = '';
if ( $_REQUEST['page'] ) {
	$page = $_REQUEST['page'];
} else {
	$page = "home.php";
}

// we tag this onto the end of redirects / Location: calls.  
/// INFRA:  All of them, eventually, if not already.  Now it only draws on jsoncallback.  Eventually it should search for $_REQUEST['parcel'] which is a JSON object containing {page, return, passthrough} being where to go on success, where to go on fail, and what varibles to pass on.

$uri_append = '';
if ( $_REQUEST['jsoncallback'] ) {
	$uri_append = "jsoncallback=".$_REQUEST['jsoncallback'];
}

$password = ReturnSecureString($_REQUEST['password']);
$username = ReturnSecureString($_REQUEST['username']);
$email = ReturnSecureString($_REQUEST['email']);
if ( $iDaystoExpire == 0 ) $iDaystoExpire = 14;

$errtxt .= '<br />URI: '.$_SERVER["REQUEST_URI"];


if ($DEBUG==1) { echo 'page open<br/>'; }

/// SEC check the sender page, or there needs to be a higher level permission to get at this file.

if ($_REQUEST['x'] == "login") {

	$errtxt .= 'into default<br/>'; 
	if ($password) {		
		$sql = "SELECT email, id, username, view FROM Users WHERE active = 1 AND password LIKE '".$password."' AND LCASE(username) LIKE '".strtolower($username)."'";
		$result = mysql_query($sql);
		$errtxt .= $sql.'<br/>'.mysql_num_rows($result); 
		
		// if corrupt accounts; needs to trigger admin notification
		if (mysql_num_rows($result)>1) {
			$errtxt .= "You've gone duplicate!  You've beat our error-checkers to the punch.  Please let the admin know.  Thanks."; 
			// trigger_error("Your account is unavailable at this time.  Our support staff is now aware of it.  Please feel free to contact us to determine the status of resolution.");
			
		} elseif ( (mysql_num_rows($result)==1) ) {
		//  or (mysql_num_rows($result)=="")
			$curRow = mysql_fetch_assoc($result);
//			$errtxt .= $curRow.'ssd';
			
			doLogin ( $curRow );		
		
			//if they want to stay logged in.
			// currently sets both a cookie, and a database field, which may be unnecessarily redundent
			/// SEC create a 'Visitors' table that maps a Session and IP to a database field.  
			
			$iDaystoExpire = 14;
			$errtxt .= ' maintain: '.$_REQUEST['make_maintain'].'][';
			if ($_REQUEST['make_maintain'] == '1') {
				/// SEC FIX createKey doesn't work, and so every user is identical.  Also, it doesn't salt, and therefore is vulnerable to rainbow tables.
				
				//make a key
				$key = createKey(25);
				
				//create an MD5 hash
				$pub = md5($key);
				
				// set the user_id and $pub as cookies
				setcookie("maintain", $_SESSION['user_id'], time()+60*60*24*$iDaystoExpire, '/');
				setcookie("maintain_key", $pub, time()+60*60*24*$iDaystoExpire, '/');
				
				// put the expiration date and the $key in the database
				$sql = "UPDATE Users SET auto_login = '".date('Y-m-d', mktime(0, 0, 0, date("m"), date("d")+$iDaystoExpire, date("Y")) )."', auth_key = '".$key."' WHERE id = ".$_SESSION['user_id'];
				
				$errtxt .= '<br/>'.$sql.'<br/>';
				$result = mysql_query($sql);
				
				// this security scheme relies upon the unlikeliness of a hacker to have a rainbow table that includes the rand(25) string.  Caveat Emptor.
			}
		} else {
			$err=1;
			$errtxt .= 'bad login <br/>'; 
			// trigger_error("You've entered the wrong information to login.  Try again.");

			// insert UserActivity
			$sql = "INSERT INTO UserActivity (user_id, IPaddress, success, datetime) VALUES (".$_SESSION['user_id'].", '".$_SERVER["REMOTE_ADDR"]."', 0 '".date("Y-m-d H:m:s")."')";
			$result = mysql_query($sql);

			$errtxt .= $sql.'<br/>'.mysql_affected_rows(); 
		}	


	// insert link history
//	$sql = "INSERT INTO admin_DEBUG (user_id, errtxt, created_at) VALUES (".$_SESSION['user_id'].", '".mysql_escape_string($errtxt)."', '".date("Y/m/d H:m:s")."')";
//	$result = mysql_query($sql);
//	$errtxt .=  '<br />'.$sql;

			
		if ( $err < 1 ) {
			// only append if admin user
			if ($_SESSION['DEBUG'] == 1) {
				$_SESSION['errtxt'] = $errtxt.'login-sent';
			}
			$errtxt .= "<br /> Login successful";
			Leave("Login successful", $page.'?'.$uri_append); //header("Location: ".$page.'?msg=Login+successful&'.$uri_append);
			exit;
		} else {
			// only append if admin user
			if ($_SESSION['DEBUG'] == 1) {
				$_SESSION['errtxt'] = $errtxt;
			}
			$errtxt .= "<br/> Error on Login";
			
			$err = urlencode ( $err );
			if ( $_REQUEST['return'] != '' ) {
				$return = $_REQUEST['return'];
			} else {
				$return = 'login_front.php';
			}
//			Leave("Login Successful", $return."?err=".$err.'&'.$uri_append );
			header("Location: ".$return."?err=".$err.'&'.$uri_append);
			exit;
		}
	// if they don't have a password specified then look to log them in via cookies
	} else if ($_COOKIE['maintain']) {

		$good = validateUser ( $_COOKIE['maintain'], $_COOKIE['maintain_key'] );
		
		if ($good == 1) {

			$sql = "SELECT * FROM Users WHERE active = 1 AND id =  ".$_COOKIE['maintain']."";
			$result = mysql_query($sql);
			$errtxt .= $sql.'<br/>'.mysql_num_rows($result); 	
			$curRow = mysql_fetch_assoc($result);
			
			doLogin( $curRow );
			
			// only append if admin user
			if ($_SESSION['DEBUG'] == 1) {
				$_SESSION['errtxt'] = $errtxt;
			}
			
			if ( $uri_append ) $page .= '?'.$uri_append;
			Leave("Login successful", $page);
//			header("Location: ".$page.'?msg=Auto-login+successful&'.$uri_append);
			exit;
		} else {
	
			// only append if admin user
			if ($_SESSION['DEBUG'] == 1) {
				$_SESSION['errtxt'] = $errtxt;
			}
			
			// their cookie-based ID failed, so send them to a login page for redo, or wherever else they were to go.
			/// SEC if this happen we should log it, as normal users won't be screwing with the cookies.
			if ($_REQUEST['return']) {
				header("Location: ".$_REQUEST['return'].'?'.$uri_append );
			} else {
				header("Location: login_front.php".'?msg=Auto-login+unsuccessful&'.$uri_append );
			}
			exit;
		}
		
	// login methods failed, so send them to a login page for redo, or wherever else they wanted to go
	/// SEC if this happen we should log it, as normal users won't be screwing with the Request variables.
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

		setcookie('maintain_key', '', time()-3600, '/' );
		setcookie('maintain', '', time()-3600, '/' );
		
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
