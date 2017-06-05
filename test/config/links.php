<?php
session_start();

include('keys.php');
include('db.php');
$errtxt = ''; 

$proc = $_REQUEST['proc'];
$errtxt .= $_REQUEST['proc'];


/// check for DB attacks (including form field length assumptions, and log

$link_id = ReturnSecureString($_REQUEST['link_id']);
$URL = ReturnSecureString($_REQUEST['URL']);
$group_id = ReturnSecureString($_REQUEST['group_id']);
$title = ReturnSecureString($_REQUEST['title']);
$col = ReturnSecureString($_REQUEST['col']);
$row = ReturnSecureString($_REQUEST['row']);
$new_group = ReturnSecureString($_REQUEST['new_group']);

/// SEC check that the user seeks to update / delete only links belonging to them


/*********************		Link ADD    ****************/

if ($proc == "add") {

	/// validate link structure (http:// and whole URI format), variable presence

	/// check duplicate link


	$errtxt .= 'posted group id: '.$group_id.'<br/>';
	//	var_dump( $_REQUEST ).';;;;;';


	LinkAdd_Single ($link_id, $URL, $title, $new_group, $group_id, $row, $col);

/*********************		Link UPDATE    ****************/

} else if ($proc == "update") {

	LinkUpdate_Single ($link_id, $URL, $title, $group_id, $row, $col, $new_group ) ;
	

/*********************		Link DELETE    ****************/

} else if ($proc == "delete") {

	LinkDelete ($link_id); 

}


// only append DEBUG string if admin user
if ($_SESSION['DEBUG'] == 1) {
	$_SESSION['errtxt'] = $errtxt;
}

// determine next page	

if ($_REQUEST['page']) { 
	$page = $site_uri.$site_path.$_REQUEST['page'];
} else { 
//		$page = $site_uri.substr($_SERVER["REQUEST_URI"], 1);
	$page = $site_uri.$site_path.'edit.php';
	if ( $_REQUEST['link_id'] & ( !$proc = 'delete') ) $page .= '?link_id='.$_REQUEST['link_id'];
}

// redirect
$errtxt .=  '<br/>i am going to: '.$page;
		
if (!isset($err)) {
	header("Location: ".$page);	
	exit;
}


/*********************		FUNCTIONS    ****************/

function LinkAdd_Single ($link_id, $URL='', $title='', $new_group='', $group_id='', $row='', $col='') {
	global $errtxt;
	
	// make a group, if needed.  first we see if the group exists	
	if ($_REQUEST['group_id'] == '/new/') {

		require('groups.php');
/// gourp add function
		$group_id = AddGroup ($new_group, $_SESSION['page_id']);
	}   // else we already know the group_id
	
	
	
	// determine link row / col

	if ($_REQUEST['group_id'] == '/new/') {
		$col = 1;
		$row = 1;
	} else {  // we need to find the top element of the group
	
		$a = Link_GetNewRowCol ($group_id);
		
		$row = $a[0];
		$col = $a[1];
	}
	

	/// this makes for duplications in the Links table, but reflects each link added has create_date information attached to it that is unique to the link / its group position / etc.  URL could be normalized to a separate table, and Links could become LinksGroups
	
	// insert / create link
	$sql = "INSERT INTO Links (URL, title, date_add, group_id, user_id, page_id, col, row) VALUES ('".$URL."', '".$title."', '".date("Y-m-d h:i:s A")."', ".$group_id.", ".ReturnSecureString($_SESSION['user_id']).", ".ReturnSecureString($_SESSION['page_id']).", ".$col.", ".$row.")";
	$result = mysql_query($sql);

	$errtxt .=  $sql;
	$errtxt .=  mysql_insert_id();

	$link_id = mysql_insert_id();
	/// report insert error	
//	if (mysql_num_rows($result) < 1) {
//		$fail = 'There was a problem creating your link';
//	}

	InsertLinkHistory ($link_id);
}

function LinkUpdate_Single ($link_id, $URL='', $title='', $group_id='', $row='', $col='', $new_group = '' ) {
/// needs to validate the Link row / col integrity within the group ?  This must be screened for, as AJAX functions maintaining this integrity at the UI level could be sabotaged.
	global $errtxt;
	
	/// Updates like this should probably wrapped in a transaction.... which essentially means writing a SQL proc for it, instead
	
	$sql = "SELECT group_id FROM Links WHERE id = ".$link_id."";

	$result = mysql_query($sql);
	$errtxt .=  $sql;
	
	$r = mysql_fetch_assoc ( $result );
	$orig_group_id = $r['group_id'];

	if ($group_id != $orig_group_id) {
		$new_row = '';
		$new_col = '';

		// slide orig group links
		LinkUpdate_PositionSlide ($orig_group_id, $row, $col );

		// add a group, as needed
		if ($_REQUEST['group_id'] == '/new/') {
			require('groups.php');
			// gourp add function
			$group_id = AddGroup ($new_group, $_SESSION['page_id']);
	
			$col = 1;
			$row = 1;
		} else {  
		
			// get new insert position
			$a = Link_GetNewRowCol ($group_id);
	
			// set the row and col		
			$row = $a[0];
			$col = $a[1];
		}
		
	}
	
	// delete GP reference ?
	Links_DeleteGPIf ($link_id);
	
	// do the update
	$sql = "UPDATE Links SET URL = '".$URL."' , title = '".$title."' , group_id = ".$group_id.", col = ".$col.", row = ".$row." WHERE id = ".$link_id."";

	$result = mysql_query($sql);
	$errtxt .=  $sql;


	InsertLinkHistory ($link_id);
}


function LinkDelete ($link_id) {
	// needs to adjust the Link row / col integrity within the group
	// needs to remove GroupsPages row if last link in group
	global $errtxt;
	
	// delete old GP entry
	Links_DeleteGPIf ($link_id);
	
	
	// now we can delete
	$sql = "DELETE FROM Links WHERE id = ".$link_id."";

	$result = mysql_query($sql);
	$errtxt .=  '<br />'.$sql;

	InsertLinkHistory ($link_id);
}



/*********************		FUNCTION HELPERS    ****************/

function Link_GetNewRowCol ( $group_id ) {
		global $new_row;
		global $new_col;
		global $errtxt;
		
		$sql = "SELECT L.row as row, L.col as col FROM GroupsPages GP INNER JOIN Links L ON GP.group_id = L.group_id WHERE L.user_id = GP.user_id AND GP.group_id = ".$group_id." AND L.user_id = ".$_SESSION['user_id']." AND GP.page_id = ".$_SESSION['page_id']." ORDER BY L.col, L.row ASC";
		$errtxt .= '<BR/> '.$sql;

		$result = mysql_query($sql);
		$errtxt .= '<br />num rows: '.mysql_num_rows($result);

		if (mysql_num_rows($result) > 0) { 
			 mysql_data_seek($result, (mysql_num_rows($result) - 1) );
			$resLinkMax = mysql_fetch_assoc($result);
			$row = $resLinkMax['row'];
			$col = $resLinkMax['col'];
			
			$errtxt .= '<br />Link Row: '.$row.'  Col: '.$col;
			//set the values for insertion of new link
			/// SIX COL MAX is hardcoded for link inserts......
			if  ($row < 6) {
				$row += 1;
				// keep col the same
			} else {
				$errtxt .= '<br /> force set row and col to 1';
				$row = 1;
				$col += 1;
				// start a new col
			} 

		} else {
			$row = 1;
			$col = 1;
		}
		return Array( $row, $col);
		
		$errtxt .= '<br />Link Row: '.$row.'  Col: '.$col;	
}


function LinkUpdate_PositionSlide ($group_id, $row, $col = 1, $dir = -1 ) {
	global $errtxt;
	
	// simple slide
	$sql = "UPDATE Links SET row = row".$dir." WHERE row > ".$row." AND user_id = ".$_SESSION['user_id']." AND col = ".$col." AND group_id = ".$group_id;
	$errtxt .= '<BR/> '.$sql;

	$result = mysql_query($sql);
	
}

function Links_DeleteGPIf ( $link_id ) {
	global $errtxt;
	
	$sql = "SELECT L.group_id, GP.row, GP.col FROM Links L INNER JOIN GroupsPages GP ON L.group_id = GP.group_id where id = ".$link_id."";
	$result = mysql_query($sql);
	$r = mysql_fetch_assoc ( $result );
	$group_id = $r['group_id'];
	$row_orig = $r['row'];
	$col_orig = $r['col'];
	$errtxt .=  '<br />'.$sql;
	$errtxt .=  '<br />group id: '.$group_id;
	
	$sql = "SELECT id FROM Links WHERE group_id = ".$group_id." AND id <> ".$link_id." AND user_id = ".ReturnSecureString($_SESSION['user_id'])."";
	$result = mysql_query($sql);
	$errtxt .=  '<br />'.$sql;
	
	// if no other links in the group, we need to delete the GP reference
	// such group activity should probably happen from the group.php file
	if ( mysql_num_rows ( $result ) == 0 ) {
	
		// first we see if there's anythign else ahead, in the same row as the group
		$sql = "SELECT GP.group_id, MAX(GP.row) as row, MAX(GP.col) as col FROM GroupsPages GP INNER JOIN Links L ON GP.group_id = L.group_id WHERE GP.row < 100 AND L.user_id = GP.user_id AND L.user_id = ".$_SESSION['user_id']." AND GP.page_id = ".$_SESSION['page_id']." AND GP.row = ".$row_orig." AND GP.col > ".$col_orig." GROUP BY GP.group_id, GP.col, GP.row ORDER BY GP.row, GP.col";
		$errtxt .= '<BR/> '.$sql;
	
		$result = mysql_query($sql);
		$errtxt .= '<br />'.mysql_num_rows($result);

		if ( mysql_num_rows($result) > 0 ) {
		
			// precess the cols (in row), no adjust rows

			$sql = "UPDATE GroupsPages SET col = col-1 WHERE row < 100 AND user_id = ".$_SESSION['user_id']." AND page_id = ".$_SESSION['page_id']." AND row = ".$row_orig." AND col > ".$col_orig." ";
			$errtxt .= '<BR/> '.$sql;
		
			$result = mysql_query($sql);
			
		} else {
		
			// precess the rows
	
			$sql = "UPDATE GroupsPages SET row = row-1 WHERE row < 100 AND user_id = ".$_SESSION['user_id']." AND page_id = ".$_SESSION['page_id']." AND row > ".$row_orig." ";
			$errtxt .= '<BR/> '.$sql;
		
			$result = mysql_query($sql);
			
				
		}
		
		// then we should delete the GP reference, if it's not a system table
		if (! $group_id = 100) {
			$sql = "DELETE FROM GroupsPages WHERE group_id = ".$group_id." AND user_id = ".ReturnSecureString($_SESSION['user_id'])." AND row = ".$row_orig." AND col = ".$col_orig."";
		}
	
		$result = mysql_query($sql);
		$errtxt .=  '<br />'.$sql;
	}
}

function InsertLinkHistory ($link_id, $URL='', $title='', $group_id='', $row='', $col='') {
	global $errtxt;
	
	// insert link history
	$sql = "INSERT INTO LinksHistory (link_id, URL, title, date_occurred, group_id, col, row) VALUES (".$link_id.", '".$URL."', '".$title."', '".date("Y-m-d")."', ".$group_id.", ".$col.", ".$row.")";
	$result = mysql_query($sql);
	
	$errtxt .=  '<br />'.$sql;
	$errtxt .=  mysql_insert_id();
	
	/// report insert error	
	//	if (mysql_num_rows($result) < 1) {
	//		$fail = 'There was a problem creating your link metadata';
	//	}
	
	return mysql_insert_id();
}


?>
