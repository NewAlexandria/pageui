<?php
session_start();


/****************  FUNCTIONS ****************/

function doAdminLogin ( $curRow ) {
	global $errtxt;
	global $db;
	
	// set the major cookies
	$_SESSION['aid'] = $curRow["id"];
	$errtxt .= 'row: '.$curRow["id"].' session: '.$_SESSION['aid'].'<br />';
	$_SESSION['username'] = $curRow["username"];
	$errtxt .= 'row: '.$curRow["username"].' username: '.$_SESSION['username'].'<br />';

	/// BAD admin view privileges should be assigned by a secured login script.
	
	/// admin bview priviledges should be handed out along with an encrypted key - the user with debug view can suffer slightly slower page loads necessary for each confirmation.  This validation shoudl occur where $_SESSION['errtxt'] = $errtxt; as this is the only place that admin data can escape.... the front-end UI has nothing that it's not given.  This is mostly relevant to the config/* pages; all front-end prints are limited to /footer.php
	
	// only assign admin view var if they're admin.  Still in the clear to interceptors of network traffic
	if ( $curRow["view"] > 10 ) { 
		$_SESSION['view'] = $curRow["view"];
		$_SESSION['DEBUG'] = $curRow["view"];
	} else {
		$_SESSION['view'] = $curRow["view"];
	}
	$errtxt .= 'row: '.$curRow["view"].' view: '.$_SESSION['view'].'<br />';

	// make a key	
	$key = createKey(25);
	$sql = "UPDATE Admins SET auth_key = '".$key."' WHERE id = ".$_SESSION['aid'];
	$result = mysql_query($sql);
	$errtxt .= '<br/>'.$sql.'<br/>'.mysql_affected_rows(); 

	// create an MD5 hash
	$pub = md5($key);
	
	// set $pub a cookie for authenticating changes
	setcookie("key", $pub, time()+60*60*24*$iDaystoExpire, '/');
	/// SEC I think as of 2/17/09 createKey still didn't generate a unique key


	
	// insert UserActivity
	/// non functioning insert UserActivity
	$sql = "INSERT INTO AdminsActivity (admin_id, IPaddress, success, date_occurred) VALUES (".$_SESSION['aid'].", '".$_SERVER["REMOTE_ADDR"]."', 1, '".date("Y-m-d H:m:s")."')";
	$result = mysql_query($sql);
	$errtxt .= $sql.'<br/>'.mysql_affected_rows(); 
	$errtxt .= '<br/>'.mysql_error($db); 
}

function validateAdmin ( $aid, $pub_key ) {
	/// SEC if this validation fails, we should probably do the DB loggin here, and let the calling code just manage display messages based on return vals.
	global $errtxt;
	global $db;

	$sql = "SELECT * FROM Admins WHERE id = ".$aid;

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