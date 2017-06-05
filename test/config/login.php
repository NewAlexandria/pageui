<?php
session_start();

include('keys.php');
include('db.php');

$DEBUG = 0;

$err = $_REQUEST['err'];
$errtxt = '';

setcookie("user_id",Ê5);

if ($DEBUG==1) { echo 'page open<br/>'; }

/// SEC check the sender page, or there needs to be a higher level permission to get at this file.

if ($_REQUEST['x'] == "login") {

	$errtxt .= 'into default<br/>'; 
	if ($_REQUEST['password']) {
		
		// select rec
		$sql = "SELECT email, id, username, view FROM Users WHERE password LIKE '".$_REQUEST['password']."' AND username LIKE '".$_REQUEST['username']."'";

		//init db vars
		$result = mysql_query($sql);
		// as long as there is a record

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
			$errtxt .= ' maintain: '.$_REQUEST['make_maintain'].'[';
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
			$sql = "INSERT INTO UserActivity (user_id, IPaddress, success, datetime) VALUES (".$_SESSION['user_id'].", '".$_SERVER["REMOTE_ADDR"]."', 0 '".date("Y-m-d h:m:s")."')";
			$result = mysql_query($sql);

			$errtxt .= $sql.'<br/>'.mysql_affected_rows($db); 
		}	
	
//					$_SESSION['errtxt'] = $errtxt;

		// set which page to go to after login; did we get a page variable?
		// if not, we shoudl here rediret the user to their built static page
		if ($_REQUEST['page']) { 
			$page = $_REQUEST['page'];
//				$page = $site_uri.$_REQUEST['page'];
		} else { 
		
			// only append if admin user
			if ($_SESSION['DEBUG'] == 1) {
				$_SESSION['errtxt'] = $errtxt;
			}
//			$page = $site_uri."z.php";
			$page = $site_uri.$site_path."home.php";
		}

			
		if (!isset($err)) {
			// only append if admin user
			if ($_SESSION['DEBUG'] == 1) {
				$_SESSION['errtxt'] = $errtxt.'login-sent';
			}
			header("Location: ".$page);
			exit;
		} else {
			// only append if admin user
			if ($_SESSION['DEBUG'] == 1) {
				$_SESSION['errtxt'] = $errtxt;
			}
			header("Location: login_front.php?err=".$err);
			exit;
		}
	} else if ($_COOKIE['maintain']) {
		$sql = "SELECT * FROM Users WHERE id = ".$_COOKIE['maintain'];

		$errtxt .= '<br/>'.$sql.'<br/>';
		$result = mysql_query($sql);
		
		if (mysql_num_rows($result) == 1) {
			$curRow = mysql_fetch_assoc($result);

			if ( md5($curRow['auth_key']) == $_COOKIE['maintain_key'] ) {
				doLogin ( $curRow );
				
			}

			// only append if admin user
			if ($_SESSION['DEBUG'] == 1) {
				$_SESSION['errtxt'] = $errtxt;
			}
//			$page = $site_uri."z.php";
			$page = $site_uri.$site_path."home.php";
// echo $errtxt;

			header("Location: ".$page);
			exit;
		} else {
	
			// only append if admin user
			if ($_SESSION['DEBUG'] == 1) {
				$_SESSION['errtxt'] = $errtxt;
			}
			header("Location: login_front.php");	
			exit;
		}
	} else {
		// only append if admin user
		if ($_SESSION['DEBUG'] == 1) {
			$_SESSION['errtxt'] = $errtxt;
		}
		header("Location: login_front.php");	
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

/****************  FUNCTIONS ****************/

function doLogin ( $curRow ) {
	global $errtxt;
	global $db;
	
	// set the major cookies
	$_SESSION['user_id'] = $curRow["id"];
	$errtxt .= 'row: '.$curRow["id"].' session: '.$_SESSION['user_id'].'<br />';
	$_SESSION['username'] = $curRow["username"];
	$errtxt .= 'row: '.$curRow["username"].' username: '.$_SESSION['username'].'<br />';

	/// BAD admin view privileges should be assigned by a secured login script.
	
	/// admin bview priviledges should be handed out along with an encrypted key - the user with debug view can suffer slightly slower page loads necessary for each confirmation.  This validation shoudl occur where $_SESSION['errtxt'] = $errtxt; as this is the only place that admin data can escape.... the front-end UI has nothing that it's not given.  This is mostly relevant to the config/* pages; all front-end prints are limited to /footer.php
	
	// only assign admin view var if they're admin.  Still in the clear to interceptors of network traffic
	if ( $curRow["view"] > 0 ) { 
		$_SESSION['DEBUG'] = $curRow["view"];
	}
	$errtxt .= 'row: '.$curRow["view"].' view: '.$_SESSION['DEBUG'].'<br />';
	
	// set the default page
	/// check for SQL fail of page select
	$sql = "SELECT MIN(P.id) AS id FROM Pages P INNER JOIN UsersPages UP ON P.id = UP.page_id WHERE UP.user_id = ".$_SESSION['user_id']."";
	$result = mysql_query($sql);
	$res = mysql_fetch_assoc($result);
	$_SESSION['page_id'] = $res['id'];
	$errtxt .= 'row: '.$res["id"].' page_id: '.$_SESSION['page_id'].'<br />';
	
	
	// insert UserActivity
	/// non functioning insert UserActivity
	$sql = "INSERT INTO UserActivity (user_id, IPaddress, success, date_occurred) VALUES (".$_SESSION['user_id'].", '".$_SERVER["REMOTE_ADDR"]."', 1, '".date("Y-m-d h:m:s")."')";
	$result = mysql_query($sql);
	$errtxt .= $sql.'<br/>'.mysql_affected_rows($db); 
	$errtxt .= '<br/>'.mysql_error($db); 

}
?>