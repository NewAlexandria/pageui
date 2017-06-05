<?php
session_start();


/****************  FUNCTIONS ****************/

function doLogin ( $curRow ) {
	global $errtxt;
	global $db;
	global $iDaystoExpire;
	
	// set the major cookies
	$_SESSION['user_id'] = $curRow["id"];
	$errtxt .= 'row: '.$curRow["id"].' session: '.$_SESSION['user_id'].'<br />';
	$_SESSION['username'] = $curRow["username"];
	$errtxt .= 'row: '.$curRow["username"].' username: '.$_SESSION['username'].'<br />';

	/// BAD admin view privileges should be assigned by an apache-secured login script.
	
	/// admin bview priviledges should be handed out along with an encrypted key - the user with debug view can suffer slightly slower page loads necessary for each confirmation.  This validation shoudl occur where $_SESSION['errtxt'] = $errtxt; as this is the only place that admin data can escape.... the front-end UI has nothing that it's not given.  This is mostly relevant to the config/* pages; all front-end prints are limited to /footer.php
	
	// only assign admin view var if they're admin.  Still in the clear to interceptors of network traffic
	if ( $curRow["view"] > 0 ) { 
		$_SESSION['DEBUG'] = $curRow["view"];
	}
	$errtxt .= 'row: '.$curRow["view"].' view: '.$_SESSION['DEBUG'].'<br />';

	// show tutorials
	if ( $curRow['tutorials'] == 'n' ) {
		$_SESSION['tutorials'] = 'off';
	} else {
		$_SESSION['tutorials'] = 'on';	
	}

	// show homepage link
	if ( $curRow['homepage_set'] == 'n' ) {
		$_SESSION['show_home'] = 'off';
	} else {
		$_SESSION['show_home'] = 'on';
	}

	// show bookmarklet link
	if ( $curRow['bookmarker_set'] == 'n' ) {
		$_SESSION['show_dropper'] = 'off';
	} else {
		$_SESSION['show_dropper'] = 'on';
	}
	
/*	
	// reset the position of the unsorted row.
	/// BAD this is just a dumb practice
	$sql = "select GP.row from GroupsPages GP INNER JOIN Groups G ON GP.group_id = G.id where G.GroupType_ID = 2 AND GP.page_id = 1 AND GP.user_id = ".$_SESSION['user_id']."" ;

	$sql = "UPDATE GroupsPages SET row = 100 WHERE page_id = 1 AND user_id = 8 AND row = ". $row ;
*/	
	
	// set the default page
	/// check for SQL fail of page select
	if ( $_COOKIE['page_id'] ) {
		$errtxt .= 'cookie page id: '.$_COOKIE['page_id'];
		$_SESSION['page_id'] = 	$_COOKIE['page_id'];
	} else {
		$sql = "SELECT MIN(P.id) AS id FROM Pages P INNER JOIN UsersPages UP ON P.id = UP.page_id WHERE UP.user_id = ".$_SESSION['user_id']."";
		$result = mysql_query($sql);
		$res = mysql_fetch_assoc($result);

		$_SESSION['page_id'] = $res['id'];
		setcookie("page_id", $res['id'], time()+(60*60*24*$iDaystoExpire), '/');
		$errtxt .= 'row: '.$res["id"].' page_id: '.$_SESSION['page_id'].'<br />';
	}	

	
	
	// make a key	
	$key = createKey(25);
	$sql = "UPDATE Users SET auth_key = '".$key."' WHERE id = ".$_SESSION['user_id'];
	$result = mysql_query($sql);
	$errtxt .= '<br/>'.$sql.'<br/>'.mysql_affected_rows(); 

	// create an MD5 hash
	$pub = md5($key);
	
	// set $pub a cookie for authenticating changes
	$d = setcookie("key", $pub, time()+(60*60*24*$iDaystoExpire), '/');
	/// SEC I think as of 2/17/09 createKey still didn't generate a unique key
	$errtxt .= "<br/>pub key: ".$pub.' set: '.$d.' val: '.$_COOKIE['key'];

	
	// insert UserActivity
	$sql = "INSERT INTO UserActivity (user_id, IPaddress, success, date_occurred) VALUES (".$_SESSION['user_id'].", '".$_SERVER["REMOTE_ADDR"]."', 1, '".date("Y-m-d H:m:s")."')";
	$result = mysql_query($sql);
	$errtxt .= '<br/>'.$sql.'<br/>'.mysql_affected_rows(); 
	$errtxt .= '<br/>'.mysql_error($db); 

}

function validateUser ( $user_id, $pub_key ) {
	/// SEC if this validation fails, we should probably do the DB loggin here, and let the calling code just manage display messages based on return vals.
	global $errtxt;
	global $db;

	$sql = "SELECT * FROM Users WHERE id = ".$user_id;

	$errtxt .= '<br/>'.$sql.'<br/>';
	$result = mysql_query($sql);
	
	if (mysql_num_rows($result) == 1) {  // if this id exists
		$curRow = mysql_fetch_assoc($result);

		// this public-key method is covered in the PHP docs.
		if ( md5($curRow['auth_key']) == $pub_key ) {
			return 1;  // the info is good
		} else { 
			return -1;  // fail validation, bad key
		}
	} else { 
		return -2;  // fail validation, bad id
	}
}

?>