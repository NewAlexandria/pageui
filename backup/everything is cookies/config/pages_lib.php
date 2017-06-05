<?php
session_start();


function AddPages($user_id, $page_id, $name) {
	global $errtxt;
	/// check for DB attacks, and log
	
	/// validate email, variable presence

	$page_id = InsertPageIf( $name );


	// get the UP max sort value
	$sql = "SELECT * FROM UsersPages UP WHERE UP.user_id = ".$user_id)." ORDER BY sort DESC";
	$result = mysql_query($sql);

	$errtxt .= '<br/>'.$sql; 
	$errtxt .= '<br/>rows: '.mysql_num_rows($result).'<br/>'; 
	
	if (mysql_num_rows($result)>0) {
		$r = mysql_fetch_assoc($result);
		$sort = $r['sort']+1;
	}

	// insert UP so it can show up in the UI
	$sql = "INSERT INTO UsersPages (user_id, page_id, sort, date_created) VALUES (".$user_id).", ".$page_id.", ".$sort.", '".date("Y-m-d h:i:s A")."')";
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
	$sql = "INSERT INTO GroupsPages (page_id, group_id, user_id, row, col, date_created) VALUES (".$page_id.", ".$group_id.", ".$user_id).", 100, 1, '".date("Y-m-d h:i:s A")."')";
	$errtxt .= $sql.'<br/>'; 
	$result = mysql_query($sql);

	$errtxt .= '<br/> page_id was: '.$page_id; 
	$_SESSION['page_id'] = $page_id;
	setcookie("page_id", $_SESSION['page_id'], time()+60*60*24*$iDaystoExpire, '/');
}


function UpdatePage() {
	global $errtxt;
	$orig_page_id = ReturnSecureString( $_REQUEST['page_id'] );
	$errtxt .= '<br />'.$_SERVER['QUERY_STRING'];
	
	// see if the page exists
	// create the page, as needed
	// result is a live $page_id
	$page_id = InsertPageIf( $_REQUEST['name'] );	
	
	// update Links table
	$sql = "UPDATE Links SET page_id = ".$page_id." WHERE user_id = ".ReturnSecureString($_SESSION['user_id'])." and page_id = ".$orig_page_id."";
	$errtxt .= $sql.'<br/>'; 
	$result = mysql_query($sql);
	
	// update GP table
	$sql = "UPDATE GroupsPages SET page_id = ".$page_id." WHERE user_id = ".ReturnSecureString($_SESSION['user_id'])." and page_id = ".$orig_page_id."";
	$errtxt .= $sql.'<br/>'; 
	$result = mysql_query($sql);
	
	// update UP table
	$sql = "UPDATE UsersPages SET page_id = ".$page_id." WHERE user_id = ".ReturnSecureString($_SESSION['user_id'])." and page_id = ".$orig_page_id."";
	$errtxt .= $sql.'<br/>'; 
	$result = mysql_query($sql);	
	
	
	
}

function UpdatePageOrder() {
	global $errtxt;
	
	// loop SQL inserts

	$sort = 1;
	foreach ( $_REQUEST['page'] as $i ) {
		$sql = "UPDATE UsersPages SET sort = ".$sort. " WHERE page_id = " . $i . " AND user_id = ".$_SESSION['user_id'];
		$errtxt .= $sql.'<br/>'; 
		$result = mysql_query($sql);
		
		$sort += 1;
	}
	
	
}

function DeletePage( $page_id ) {
	global $errtxt;
	
	// confirm the page exists, also gets sort for re-sorting of UP
	$sql = "SELECT * FROM UsersPages WHERE user_id = ".ReturnSecureString($_SESSION['user_id'])." and page_id = ".$page_id."";
	$errtxt .= $sql.'<br/>'; 
	$result = mysql_query($sql);
	
	if ( mysql_num_rows ( $result ) > 0 ) {
		$r = mysql_fetch_assoc ( $result );
		$sort = $r['sort'];

		// remove the UP records	
		$sql = "DELETE FROM UsersPages WHERE user_id = ".ReturnSecureString($_SESSION['user_id'])." and page_id = ".$page_id."";
		$errtxt .= $sql.'<br/>'; 
		$result = mysql_query($sql);
		
		// resort as needed
			$sql = "UPDATE UsersPages SET sort = sort-1 WHERE user_id = ".ReturnSecureString($_SESSION['user_id'])." and page_id = ".$page_id." AND sort > ".$sort;
			$errtxt .= $sql.'<br/>'; 
			$result = mysql_query($sql);

		
		// remove the GP records
		$sql = "DELETE FROM GroupsPages WHERE user_id = ".ReturnSecureString($_SESSION['user_id'])." and page_id = ".$page_id."";
		$errtxt .= $sql.'<br/>'; 
		$result = mysql_query($sql);
	
		// perform LinkHistory inserts
		$sql = "SELECT * FROM Links WHERE user_id = ".ReturnSecureString($_SESSION['user_id'])." and page_id = ".$page_id."";
		$errtxt .= $sql.'<br/>'; 
		$result = mysql_query($sql);

		require_once('links_lib.php');
		if ( mysql_num_rows ( $result ) > 0 ) {
			while ( $r = mysql_fetch_assoc ( $result ) ) {
		
			InsertLinkHistory ($r['id']);
			}	
		}
	
		// remove Links records
		// update GP table
		$sql = "DELETE FROM Links WHERE user_id = ".ReturnSecureString($_SESSION['user_id'])." and page_id = ".$page_id."";
		$errtxt .= '<br/>'.$sql;
		$result = mysql_query($sql);
		
		
	} else {
		Leave ( $msg );
	}
	
	$sql = "SELECT * FROM UsersPages WHERE user_id = ".ReturnSecureString($_SESSION['user_id'])." ";
	$errtxt .= '<br/>'.$sql;
	$result = mysql_query($sql);
	
	if ( mysql_num_rows ( $result ) < 1 ) {
		$page_id = InsertPageIf();
		$_SESSION['page_id'] = $page_id;
		setcookie("page_id", $_SESSION['page_id'], time()+60*60*24*$iDaystoExpire, '/');
		$errtxt .= '<br/> page_id was: '.$page_id; 
		$errtxt .= '<br/> page_id is: '.$_SESSION['page_id']; 
	} else {
		$r = mysql_fetch_assoc ( $result );
		$_SESSION['page_id'] = $r['page_id'];
		setcookie("page_id", $_SESSION['page_id'], time()+60*60*24*$iDaystoExpire, '/');
		$errtxt .= '<br/> page_id was: '.$r['page_id']; 
		$errtxt .= '<br/> page_id is: '.$_SESSION['page_id']; 
	}
	
}



/******************* Helper Functions *********************/

function InsertPageIf( $name ) {
	global $errtxt;
	
	// check duplicate page name
	if ( $name == "" ) {
		$page_name = "default";
	} else {
		$page_name = $name;
	}
	
	$sql = "SELECT * FROM Pages P LEFT OUTER JOIN UsersPages UP ON P.id = UP.page_id WHERE P.Name LIKE '".$page_name."' AND ( UP.user_id = ".ReturnSecureString($_SESSION['user_id'])." OR UP.user_id IS NULL )";

	$result = mysql_query($sql);
	/// if there is a record, go back / err

	$errtxt .= $sql; 
	$errtxt .= '<br/>rows: '.mysql_num_rows($result).'<br/>'; 
	
	if (mysql_num_rows($result)>0) {
		$r = mysql_fetch_assoc($result);
		$page_id = $r['id'];
		
		if (is_numeric ( $r['user_id'] ) ) {
			// THey already have a page by this name, send back
			/// UI provide feedback on page dupe
				$msg = "You already have one of those!";
				Leave( $msg );
		}  // ^ ELSE  the page exists, but they don't have a UP record yeti

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
	
	return $page_id;
}

?>