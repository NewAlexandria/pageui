<?php
session_start();

include('keys.php');
include('db.php');

$DEBUG = 0;

if ($_REQUEST['proc'] == "add") {

	/// check for DB attacks, and log
	
	/// validate email, variable presence

	// check password matching
	if ($_REQUEST['password'] != $_REQUEST['password_c']) {
		/// pass back error messages
		$errtxt .= 'goodness<br/>'; 
		$errtxt .= $_REQUEST['password'] .'<br/>'; 
		$errtxt .= $_REQUEST['password_c'] .'<br/>'; 
		
		header("Location: ../signup.php?username=".$_REQUEST['username']."&email=".$_REQUEST['email']."&fail=Passowrds+do+not+match");
//		header("Location: ".$_SERVER["HTTP_REFERER"]."?username=".$_REQUEST['username']."&email=".$_REQUEST['email'])."&fail=Passowrds+do+not+match";	
		exit;
	}

	// check duplicate username
	$sql = "SELECT id FROM Users WHERE username LIKE '".$_REQUEST['username']."'";
	$result = mysql_query($sql);
	// if there is a record, go back

	$errtxt .= $sql; 
	$errtxt .= '<br/>'.mysql_affected_rows($db).'<br/>'; 
	
	if (mysql_affected_rows($db)>0) {
		/// pass back error message
		header("Location: ../signup.php?username=".$_REQUEST['username']."&email=".$_REQUEST['email']."&fail=Username+already+in+database");	
//		header("Location: ".$_SERVER["HTTP_REFERER"]."?username=".$_REQUEST['username']."&email=".$_REQUEST['email'])."&fail=Username+already+in+database";	
		exit;
	}
			
	// insert / create user
	$sql = "INSERT INTO Users (username, password, email, date_created) VALUES ('".$_REQUEST['username']."', '".$_REQUEST['password']."', '".$_REQUEST['email']."', '".date("Y-m-d h:m:s")."')";
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

	// insert UP reference
	$sql = "INSERT INTO UsersPages(user_id, page_id, date_created, sort) VALUES(".$user_id.", 1, '".date("Y-m-d h:m:s")."', 1)";
	$result = mysql_query($sql);
	/// check for insert error
	
	$errtxt .= $sql; 
	
	
	// determine next page
	// this could be done as a JSON array of pages, with key-value pairs for each of the variables to call for each page
	
	/// should send to header("Location: config/pages.php?proc=add"); after login

	if ($_REQUEST['page']) { 
		$page = $site_uri.$site_path.$_REQUEST['page'];
	} else { 
		$page = $site_uri.$site_path."config/login.php?x=login&username=".$_REQUEST['username']."&password=".$_REQUEST['password'];

	}


	// redirect
			
//	if (!isset($err)) {
		header("Location: ".$page);	
		exit;
//	}


}
?>
