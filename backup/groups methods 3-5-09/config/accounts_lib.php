<?php
session_start();

require_once('db.php');


/*********************		FUNCTIONS    ****************/

function AccountAdd ($password, $password_c, $email, $username='') { 

	/// check for DB attacks, and log
	
	/// validate email, variable presence

	/* check password matching
	if ($password != $password_c) {
		/// pass back error messages
		$errtxt .= 'goodness<br/>'; 
		$errtxt .= $password .'<br/>'; 
		$errtxt .= $password_c .'<br/>'; 
		
		header("Location: ../signup.php?username=".$username."&email=".$email."&fail=Passowrds+do+not+match");
//		header("Location: ".$_SERVER["HTTP_REFERER"]."?username=".$username."&email=".$email)."&fail=Passowrds+do+not+match";	
		exit;
	}
*/
	// check duplicate username
	$sql = "SELECT id FROM Users WHERE email LIKE '".$email."'";
	$result = mysql_query($sql);
	// if there is a record, go back

	$errtxt .= $sql; 
	$errtxt .= '<br/>'.mysql_affected_rows().'<br/>'; 
	
	if (mysql_affected_rows()>0) {
		/// pass back error message
		header("Location: ../signup.php?username=".$username."&email=".$email."&fail=Email	+already+in+database");	
//		header("Location: ".$_SERVER["HTTP_REFERER"]."?username=".$username."&email=".$username)."&fail=Username+already+in+database";	
		exit;
	}
			
	// insert / create user
	$sql = "INSERT INTO Users (username, password, email, date_created) VALUES ('".$username."', '".$password."', '".$email."', '".date("Y-m-d H:m:s")."')";
	$result = mysql_query($sql);
	/// check for insert error
	
	$errtxt .= $sql; 
	$errtxt .=  mysql_insert_id(); 
	
	// create login session, unless account confirmation needed
	/// pass info to login module, rather than creating session here
	/// actually, pass user to login page to get authenticated and set page=pages.php with create info in JSON packet, which then sends the user to the home.  Init sequence: signup, users, login, pages, home
	
//	$_SESSION['user_id'] = mysql_insert_id();
	$user_id = mysql_insert_id();

	// only append if admin user
	if ($_SESSION['DEBUG'] == 1) {
		$_SESSION['errtxt'] = $errtxt;
	}

	// insert UP reference for Unsorted Group.  This is deprecated as Unsrted on every page by design
	$sql = "INSERT INTO UsersPages(user_id, page_id, date_created, sort) VALUES(".$user_id.", 1, '".date("Y-m-d H:m:s")."', 1)";
	$result = mysql_query($sql);
	/// check for insert error
	
	$errtxt .= $sql; 

	require_once('pages_lib.php');
	
//	AddPages($user_id, 'default', 'intro.php');
	
	// We will now insert some demo links.  This could best be done as a JSON array of pages, with key-value pairs for each of the variables to call for each page - effectively creating an internal scripting language.  The note is left here for uture consideration, but for now a master 'tutorial' account is cloned:

	// get all the groups and add them to the new account
	$sql = "INSERT INTO GroupsPages (group_id, page_id, user_id, row, col, date_created) SELECT GP.group_id, GP.page_id, ".$user_id.", GP.row, GP.col, '".date("Y-m-d H:m:s")."' FROM GroupsPages GP INNER JOIN Users U ON GP.user_id = U.id  INNER JOIN Pages P ON GP.page_id = P.id WHERE U.email LIKE 'TutorialAccount@pageui.com' AND P.Name = 'default'";
	$result = mysql_query ( $sql );
	$errtxt .= '<br />'.$sql;

	// get all the links and add them, too
	$sql = "INSERT INTO Links (URL, title, date_add, group_id, user_id, col, row, page_id) SELECT L.URL, L.title, '".date("Y-m-d H:m:s")."', L.group_id, ".$user_id.", L.col, L.row, L.page_id FROM Links L INNER JOIN Users U ON L.user_id = U.id WHERE U.email LIKE 'TutorialAccount@pageui.com' AND L.group_id IN (SELECT group_id FROM GroupsPages GP INNER JOIN Pages P ON GP.page_id = P.id INNER JOIN Users U ON GP.user_id = U.id WHERE U.email LIKE 'TutorialAccount@pageui.com' AND P.Name LIKE 'default')";
	$result = mysql_query ( $sql );
	$errtxt .= '<br />'.$sql;
	
	
/*
	if ($page) { 
		$page = $site_uri.$site_path.$page;
	} else { 
		$page = $site_uri.$site_path."config/login.php?x=login&username=".$username."&password=".$password;

	}


	// redirect
			
//	if (!isset($err)) {
		header("Location: ".$page);	
		exit;
//	}
*/
}

function AccountUpdate ($user_id, $email, $password = '', $name_first = '', $name_last = '', $username = '', $tutorials = 'y', $homepage_set = 'n', $bookmarker_set = 'n' ) {
/// needs to validate the Link row / col integrity within the group ?  This must be screened for, as AJAX functions maintaining this integrity at the UI level could be sabotaged.
	global $errtxt;
	
	/// Updates like this should probably wrapped in a transaction.... which essentially means writing a SQL proc for it, instead
	
	$sql = "UPDATE Users SET email = '".$email."' ";
	
	if ( $password ) $sql .= ", password = '".$password."' ";
	if ( $name_first ) $sql .= ", name_first = '".$name_first."' ";
	if ( $name_last ) $sql .= ", name_last = '".$name_last."' ";
	if ( $username ) $sql .= ", username = '".$username."' ";
	if ( $tutorials ) $sql .= ", tutorials = '".$tutorials."' ";
	if ( $homepage_set ) $sql .= ", homepage_set = '".$homepage_set."' ";
	if ( $bookmarker_set ) $sql .= ", bookmarker_set = '".$bookmarker_set."' ";
	
	$sql .= "WHERE id = ".$user_id;
	
	$result = mysql_query($sql);
	$errtxt .= '<br />'.$sql;
	$errtxt .= '<br/>num: '.mysql_affected_rows();

	// show tutorials
	if ( $tutorials == 'n' ) {
		$_SESSION['tutorials'] = 'off';
	} else {
		$_SESSION['tutorials'] = 'on';	
	}

	// show homepage link
	if ( $homepage_set == 'n' ) {
		$_SESSION['show_home'] = 'off';
	} else {
		$_SESSION['show_home'] = 'on';
	}

	// show bookmarklet link
	if ( $bookmarker_set == 'n' ) {
		$_SESSION['show_dropper'] = 'off';
	} else {
		$_SESSION['show_dropper'] = 'on';
	}
}


function AccountDeactivate ($user_id) {
	/// needs to adjust the Link row / col integrity within the group
	/// needs to remove GroupsPages row if last link in group
	global $errtxt;
	
	// delete old GP entry, but only if we're not in a system table
	$sql = "UPDATE Users SET active = 0 WHERE id = ".$user_id."";
	$result = mysql_query($sql);
	$errtxt .=  '<br />'.$sql;
	$errtxt .= '<br/>num: '.mysql_affected_rows();
}



/*********************		FUNCTION HELPERS    ****************/

?>