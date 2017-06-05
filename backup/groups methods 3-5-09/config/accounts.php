<?php
session_start();

include_once('keys.php');
include_once('db.php');

require_once('accounts_lib.php');
require_once('login_lib.php');

$errtxt = ''; 

$proc = $_REQUEST['proc'];
$errtxt .= '<br />proc: '.$proc;

$DEBUG = 0;

/// check for DB attacks (including form field length assumptions, and log

if ( $_SESSION['user_id'] ) {
	$user_id = ReturnSecureString($_SESSION['user_id']);
} else {
	$user_id = ReturnSecureString($_COOKIE['user_id']);
}
$email = ReturnSecureString($_REQUEST['email']);
$password = ReturnSecureString($_REQUEST['password']);
$password_c = ReturnSecureString($_REQUEST['password_c']);
$username = ReturnSecureString($_REQUEST['username']);
$name_first = ReturnSecureString($_REQUEST['name_first']);
$name_last = ReturnSecureString($_REQUEST['name_last']);
if ( $_REQUEST['tutorials'] == 'on' ) {
	$tutorials = 'y';
} else {
	$tutorials = 'n';
}
if ( $_REQUEST['homepage_set'] == 'on' ) {
	$homepage_set = 'y';
} else {
	$homepage_set = 'n';
}
if ( $_REQUEST['bookmarker_set'] == 'on' ) {
	$bookmarker_set = 'y';
} else {
	$bookmarker_set = 'n';
}
	
$page = $_REQUEST['page'];
$return = $_REQUEST['return'];  /// SEC ? this could be spoofed


$errtxt .= '<br />page: '.$page;
$errtxt .= '<br />URI: '.$_SERVER["REQUEST_URI"];
/// SEC check that the user seeks to update / delete only links belonging to them


/// Ideally, each of the functions called here would instead pass data to SQL stored procedures.  These would execute more quickly, prevent poisoned input, and scale easier.  The SQL should return multiple variables for errtxt and success_status, and may need to bundle these as JSON / etc to accomplish it via the MySQL driver.  They were coded here in PHP for rapid prototyping

/// All the proces should take all their used data as inputs, and should not assume global scope variables.  It's insecure, and more breakable.  Some as of writing this note.
switch ( $proc )
{
    case "add":
        // validate input structure
        /// configure this
        
        if ( $email == '' ) {
        	/// make up a title and move on without missing the link_add

			$return .= "?username=".$username."&email=".$email."&fail=You+need+an+email+to+login";
	//		header("Location: ".$_SERVER["HTTP_REFERER"]."?username=".$username."&email=".$email)."&fail=Passowrds+do+not+match";	

        	Leave("An email is needed to avoid duplicate logins.", $return);
			exit;        	
        } else if ( $username == '' ) {
        	/// make up a title and move on without missing the link_add

			$return .= "?username=".$username."&email=".$email."&fail=You+need+a+username+to+login";

        	Leave("A username is needed to login.", $return);
			exit;        	

        } else if ( !preg_match("/^[\w\-\+\&\*]+(?:\.[\w\-\_\+\&\*]+)*@(?:[\w-]+\.)+[a-zA-Z]{2,7}$/", $email ) ) {
			$return .= "?username=".$username."&email=".$email."&fail=You+need+a+valid+email+to+login+".$em;

        	Leave("Your email looks invalid.  If there is an error, please contact us.", $return);
			exit;        	
        
        } else if ( !$password ) {
			$return .= "?username=".$username."&email=".$email."&fail=You+need+to+enter+a+password";
	//		header("Location: ".$_SERVER["HTTP_REFERER"]."?username=".$username."&email=".$email)."&fail=Passowrds+do+not+match";	

        	Leave("Passwords, they're a beautiful thing.".$em, $return);
			exit;        	
        } else if ( $password != $password_c ) {
			$errtxt .= $password .'<br/>'; 
			$errtxt .= $password_c .'<br/>'; 
			
			$return .= "?username=".$username."&email=".$email."&fail=Passowrds+do+not+match";
	//		header("Location: ".$_SERVER["HTTP_REFERER"]."?username=".$username."&email=".$email)."&fail=Passowrds+do+not+match";	

        	Leave("The passwords didn't match.  Be sure to enter a password you can remember.", $return);
			exit;
        }

		/// check duplicate user
	
		AccountAdd ($password, $password_c, $email, $username);

		header( "Location: login.php?x=login&password=".$password."&email=".$email."&page=".$page );
		
		Leave("Welcome to the <i>clarifed</i> web...", $page.'?');
        break;
    case "update":
    	$good = validateUser($user_id, $_COOKIE['key']);
    	
    	if ( $good ) {
			AccountUpdate ($user_id, $email, $password, $name_first, $name_last, $username, $tutorials, $homepage_set, $bookmarker_set ) ;
			Leave("Your account information updated successfully", $page);
			break;
		} else {
			Leave("There was a problem with login.  If the problem persists, and you cannot update information, please contact the administrator", $page);
			break;
		}
    case "delete":
    	$good = validateUser($user_id, $_COOKIE['key']);
    	
    	if ( $good ) {
			AccountDeactivate ($user_id); 
			$_REQUEST['page'] = 'config/login.php?x=logout';
			Leave("Your account is no more", $page);
			break;
		} else {
			Leave("There was a problem with your login.  If the problem persists, and you cannot update information, please contact the administrator", $page);
			break;
		}
    default:
    	$errtxt .= '<br/>no input';
        Leave("~ <i>What</i> would you like me to do? ~", $page);
        break;
}


?>
