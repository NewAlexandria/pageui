<?php
session_start();

include('keys.php');
include('db.php');

$DEBUG = 0;

if ($_REQUEST['proc'] == "add") {

	/// check for DB attacks, and log
	
	/// validate email, variable presence



	
	// check duplicate page name
	if ($_REQUEST['name']=="") {
		$page_name = "default";
	} else {
		$page_name = ReturnSecureString($_REQUEST['name']);
	}
	
	$sql = "SELECT * FROM Pages P LEFT OUTER JOIN UsersPages UP ON P.id = UP.page_id WHERE P.Name LIKE '".$page_name."' AND ( UP.user_id = ".ReturnSecureString($_SESSION['user_id'])." OR UP.user_id IS NULL )";

//	$sql = "SELECT P.id FROM Pages P INNER JOIN GroupsPages GP ON P.id = GP.page_id WHERE GP.user_id = ".ReturnSecureString($_SESSION['user_id'])." AND Name LIKE '".$page_name."'";
	$result = mysql_query($sql);
	/// if there is a record, go back / err

	$errtxt .= $sql; 
	$errtxt .= '<br/>rows: '.mysql_num_rows($result).'<br/>'; 
	
	if (mysql_num_rows($result)>0) {
		// if we got anything then we must see if we have a user_id / UP rowan
		
		/// pass back error message
//		header("Location: ../signup.php?username=".$_REQUEST['username']."&email=".$_REQUEST['email']."&fail=a+page+of+this+name+is+already+in+your+account");	
//		header("Location: ".$_SERVER["HTTP_REFERER"]."?username=".$_REQUEST['username']."&email=".$_REQUEST['email'])."&fail=Username+already+in+database";	
//		exit;
		$r = mysql_fetch_assoc($result);
		$page_id = $r['id'];
		
		if (is_numeric ( $r['user_id'] ) ) {
			// THey already have a page by this name, send back
			/// UI provide feedback on page dupe
				$msg = "You already have one of those!";
				Leave( $msg );
		} 

		// ELSE  the page exists, but they don't have a UP record yeti

	} else {
		// if we have nothing, then we must create a new page
		// insert / create page
		$sql = "INSERT INTO Pages (Name, sort, date_created) VALUES ('".$page_name."', 1, '".date("Y-m-d h:i:s A")."')";
		$result = mysql_query($sql);
		/// check for insert error
		
		$errtxt .= $sql; 
		$errtxt .=  mysql_insert_id(); 
		
		$page_id = mysql_insert_id();
	}

	// get the UP max sort value
	$sql = "SELECT * FROM UsersPages UP WHERE UP.user_id = ".ReturnSecureString($_SESSION['user_id'])." ORDER BY sort DESC";
	$result = mysql_query($sql);

	$errtxt .= $sql.'<br/>'; 
	$errtxt .= '<br/>rows: '.mysql_num_rows($result).'<br/>'; 
	
	if (mysql_num_rows($result)>0) {
		$r = mysql_fetch_assoc($result);
		$sort = $r['sort']+1;
	}

	// insert UP so it can show up in the UI
	$sql = "INSERT INTO UsersPages (user_id, page_id, sort, date_created) VALUES (".ReturnSecureString($_SESSION['user_id']).", ".$page_id.", ".$sort.", '".date("Y-m-d h:i:s A")."')";
	$errtxt .= $sql.'<br/>'; 
	$result = mysql_query($sql);
	
	
	// get group id for unsorted
	$sql = "SELECT id FROM Groups WHERE Title LIKE 'Unsorted' AND GroupType_id = 2";
	$errtxt .= $sql.'<br/>'; 
	$result = mysql_query($sql);
	$r = mysql_fetch_assoc($result);
	$group_id = $r['id'];


	/// ?? max number of rows for a user's groups is 99.  seems sufficient, but variable with the row # of the system group "unsorted"

	// insert / create page-group
	$sql = "INSERT INTO GroupsPages (page_id, group_id, user_id, row, col, date_created) VALUES (".$page_id.", ".$group_id.", ".ReturnSecureString($_SESSION['user_id'])." 100, 0, '".date("Y-m-d h:i:s A")."')";
	$errtxt .= $sql.'<br/>'; 
	$result = mysql_query($sql);

	
	Leave();

}



?>