<?php
session_start();


/*********************		FUNCTIONS    ****************/

function LinkAdd_Single ($link_id, $page_id, $user_id, $URL='', $title='', $new_group='', $group_id='', $row='', $col='') {
	global $errtxt;
	
	// make a group, if needed.  first we see if the group exists	
	if ($_REQUEST['group_id'] == '/new/') {

		require_once('groups_lib.php');

		$group_id = AddGroup ($new_group, $page_id, $user_id);
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
	$sql = "INSERT INTO Links (URL, title, date_add, group_id, user_id, page_id, col, row) VALUES ('".$URL."', '".$title."', '".date("Y-m-d h:i:s A")."', ".$group_id.", ".$user_id.", ".$page_id.", ".$col.", ".$row.")";
	$result = mysql_query($sql);

	$errtxt .=  $sql;
	$errtxt .=  mysql_insert_id();

	$link_id = mysql_insert_id();
	/// report insert error	
//	if (mysql_num_rows($result) < 1) {
//		$fail = 'There was a problem creating your link';
//	}

	InsertLinkHistory ($link_id, $URL, $title, $group_id, $user_id, $row, $col);
}

function LinkUpdate_Single ($user_id, $page_id, $link_id, $URL='', $title='', $group_id='', $row='', $col='', $new_group = '' ) {
/// needs to validate the Link row / col integrity within the group ?  This must be screened for, as AJAX functions maintaining this integrity at the UI level could be sabotaged.
	global $errtxt;
	
	/// Updates like this should probably wrapped in a transaction.... which essentially means writing a SQL proc for it, instead
	
	$sql = "SELECT group_id FROM Links WHERE id = ".$link_id."";

	$result = mysql_query($sql);
	$errtxt .= '<br />'.$sql;
	
	$r = mysql_fetch_assoc ( $result );
	$orig_group_id = $r['group_id'];
	$errtxt .= '<br />orig/db group id: '.$orig_group_id.' input group id: '.$group_id;

	if ($group_id != $orig_group_id) {
		$new_row = '';
		$new_col = '';
		$errtxt .= '<br />group changing';

		
		// since we changed the group, delete GP reference if that was the last link
		$isGone = Links_DeleteGPIf ($link_id, $user_id);

		if ( !$isGone ) {
			// slide orig group links, since some still exist
			LinkUpdate_PositionSlide ($orig_group_id, $user_id, $page_id, $row, $col );
			$errtxt .= '<br />links still present in old group; order shifted';
		}
		
		// add a group, as needed
		if ($group_id == '/new/') {
			$errtxt .= '<br />new group: '.$new_group;
			
			require_once('groups_lib.php');
			// gourp add function
			$group_id = AddGroup ($new_group, $page_id, $user_id);
	
			$col = 1;
			$row = 1;
		} else {  			
			// get new insert position
			$a = Link_GetNewRowCol ($group_id);
	
			// set the row and col		
			$row = $a[0];
			$col = $a[1];
			$errtxt .= '<br />to be inserted at row: '.$row.' col: '.$col;
		}
		
	}
	
	
	// do the update
	$sql = "UPDATE Links SET URL = '".$URL."' , title = '".$title."' , group_id = ".$group_id.", col = ".$col.", row = ".$row." WHERE id = ".$link_id."";

	$result = mysql_query($sql);
	$errtxt .=  '<br />'.$sql;
	$errtxt .= '<br/>num: '.mysql_affected_rows();


	InsertLinkHistory ($link_id, $URL, $title, $group_id, $user_id, $row, $col);
}

function updateLinkSort ( $user_id, $aLinks, $col, $group_id, $page_id ) {
	global $errtxt;

	$sort = 1;

	if ( $aLinks != '') {
		// if they dropped it onto a new lnk col
		if ( substr ( $col, 0, 1 ) == 'n' ) {
		
			$sql = "UPDATE Links SET col = ".substr ( $col, 1 ). ", group_id = ".$group_id.", page_id = ".$page_id." WHERE id = ".ReturnSecureString( $aLinks[0] ) ; //. " AND user_id = ".$user_id;
			$errtxt .= '<br/>'.$sql; 
			$result = mysql_query($sql);
			$errtxt .= '<br/>num: '.mysql_affected_rows();
	
			InsertLinkHistory ( $aLinks[0], '', '', $group_id, $user_id, 1, $col );
			
		} else {
			// we have a number of links in a column that is not new
			foreach ( $aLinks as $i ) {	
				/// SEC no check of input number $i
				$sql = "UPDATE Links SET row = ".$sort. ", col = ".$col.", group_id = ".$group_id.", page_id = ".$page_id." WHERE id = " . ReturnSecureString( $i ) ; //. " AND user_id = ".$user_id;
				$errtxt .= '<br/>'.$sql; 
				$result = mysql_query($sql);
				$errtxt .= '<br/>num: '.mysql_affected_rows();
	
				InsertLinkHistory ( $i, '', '', $group_id, $user_id, $sort, $col );
		
				$sort += 1;		
			}		
		}
	}
	
	
	// we need the list of used columns.  a few times we'll skip the "if col > 1" statement, but if we embed this conditionally in previous logic with a function, it looks like on average we do more if calculations that just this one.
	$sql = "SELECT col from Links WHERE group_id = ".$group_id." AND user_id = ".$user_id." GROUP BY col";
	$errtxt .= '<br/>'.$sql; 
	$result = mysql_query($sql);
	$errtxt .= '<br/> num rows: '.mysql_num_rows($result);
	$r = mysql_fetch_assoc($result);
	$errtxt .= '<br/> first col: '.$r['col'];
	
	// having found some, we will cycle until the col mismatches with an increment var; letting us know of a gap to patch
	$ref = 1;
	
	if ($r['col'] > 1) { 
		LinkUpdate_ColSlide ($group_id, $user_id, $page_id, $ref);
		$errtxt .= '-1.  no more col.  scrap it';
	} else if ($r['col'] < 1) { 
		LinkUpdate_ColSlide ($group_id, $user_id, $page_id, $ref, '+1');
		$errtxt .= '+1.  no more col.  scrap it';
	}	
}	


function LinkDelete ($link_id, $user_id) {
	/// needs to adjust the Link row / col integrity within the group
	/// needs to remove GroupsPages row if last link in group
	global $errtxt;
	
	// delete old GP entry, but only if we're not in a system table
	$sql = "SELECT GroupType_id FROM Links L INNER JOIN Groups G ON L.group_id = G.id WHERE L.id = ".$link_id." AND user_id = ".$user_id."";
	$result = mysql_query($sql);
	$errtxt .=  '<br />'.$sql;
	$r = mysql_fetch_assoc ( $result );

	/* // we used to delete groups if they were bereft of links.
	if ( $r['GroupType_id'] == 1 ) {
		Links_DeleteGPIf ($link_id, $user_id);
	}
	*/
	
	// do select to get data & ensure integrity
	$sql = "SELECT * FROM Links WHERE id = ".$link_id."";
	$result = mysql_query($sql);
	$errtxt .=  '<br />'.$sql;
	$r = mysql_fetch_assoc ( $result );
	
	
	// now we can delete
	$sql = "DELETE FROM Links WHERE id = ".$link_id."";
	$result = mysql_query($sql);
	$errtxt .=  '<br />'.$sql;
	$errtxt .= '<br/>num: '.mysql_affected_rows();

	// resort as needed.  
	$sql = "UPDATE Links SET row = row-1 WHERE col = ".$r['col']." AND row > ".$r['row']." AND group_id = ".$r['group_id']." AND user_id = ".$user_id." ";

	$errtxt .= '<br/>'.$sql; 
	$result = mysql_query($sql);
	$errtxt .= '<br/>num: '.mysql_affected_rows();
	

	InsertLinkHistory ($link_id);
}



/*********************		FUNCTION HELPERS    ****************/

function Link_GetNewRowCol ( $group_id ) {
		global $new_row;
		global $new_col;
		global $errtxt;
		
		$sql = "SELECT L.row as row, L.col as col FROM GroupsPages GP INNER JOIN Links L ON GP.group_id = L.group_id WHERE L.user_id = GP.user_id AND L.page_id = GP.page_id AND GP.group_id = ".$group_id." AND L.user_id = ".$_SESSION['user_id']." AND GP.page_id = ".$_SESSION['page_id']." ORDER BY L.col, L.row ASC";
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


function LinkUpdate_PositionSlide ($group_id, $user_id, $page_id, $row, $col = 1, $dir = -1 ) {
	global $errtxt;
	
	// simple slide
	$sql = "UPDATE Links SET row = row".$dir." WHERE row > ".$row." AND col = ".$col." AND group_id = ".$group_id." AND user_id = ".$user_id." AND page_id = ".$page_id;
	$errtxt .= '<BR/> '.$sql;

	$result = mysql_query($sql);
	$errtxt .= '<br/>num: '.mysql_affected_rows();
	
}

function LinkUpdate_ColSlide ($group_id, $user_id, $page_id, $col, $dir = '-1' ) {
	global $errtxt;

	// $col	 not used
	
	// simple slide
	$sql = "UPDATE Links SET col = col".$dir." WHERE group_id = ".$group_id." AND user_id = ".$user_id." AND page_id = ".$page_id;
	$errtxt .= '<BR/>LinkUpdate_ColSlide: '.$sql;

	$result = mysql_query($sql);
	$errtxt .= '<BR/>Col Slide, num rows: '.mysql_affected_rows();
	$errtxt .= '<br/>err: ( '.mysql_errno() . " ) " . mysql_error();
	
}


function Links_DeleteGPIf ( $link_id, $user_id ) {
	// this procedure determines if a link is the last link in the group.  If it is, the group is deleted, neighbor groups managed, and the orphaned link left for further processing
	
	global $errtxt;
	$errtxt .= '<br />Links_DeleteGPIf';
	
	// get the group information for the link 
	$sql = "SELECT L.group_id, GP.row, GP.col, G.GroupType_id, GP.page_id FROM Links L INNER JOIN GroupsPages GP ON L.group_id = GP.group_id AND L.page_id = GP.page_id INNER JOIN Groups G ON G.id = GP.group_id WHERE L.id = ".$link_id."";
	$result = mysql_query($sql);
	$errtxt .=  '<br />'.$sql;

	$r = mysql_fetch_assoc ( $result );
	$group_id = $r['group_id'];
	$GroupType_id = $r['GroupType_id'];
	$row_orig = $r['row'];
	$col_orig = $r['col'];
	$page_id = $r['page_id'];

	$errtxt .=  '<br />group id: '.$group_id;
	
	$sql = "SELECT id FROM Links WHERE group_id = ".$group_id." AND id <> ".$link_id." AND page_id = ".$page_id." AND user_id = ".$user_id."";
	$result = mysql_query($sql);
	$errtxt .=  '<br />'.$sql;
	
	// if no other links in the group, we need to delete the GP reference
	// such group activity should probably happen from the group.php file
	if ( mysql_num_rows ( $result ) == 0 ) {
		require_once('groups_lib.php');
		
		// remove the GP row and re-sort
		DeleteFromGP ($user_id, $group_id, $page_id, $col_orig, $row_orig);
		
		// notify a delete occurred
		return 1;
	} else {
		// notify that nothing occurred
		return 0;
	}	
}

function InsertLinkHistory ($link_id, $URL='', $title='', $group_id='0', $user_id = 'NULL', $row='0', $col='0') { 
	global $errtxt;
	
	// insert link history
	$sql = "INSERT INTO LinkHistory (link_id, URL, title, date_occurred, group_id, col, row) VALUES (".$link_id.", '".$URL."', '".$title."', '".date("Y-m-d H:m:s")."', ".$group_id.", ".$col.", ".$row.")";
	$result = mysql_query($sql);
	
	$errtxt .=  '<br />'.$sql;
	$errtxt .=  '<br />num: '.mysql_affected_rows();
	
	/// report insert error	
	//	if (mysql_num_rows($result) < 1) {
	//		$fail = 'There was a problem creating your link metadata';
	//	}
	
	return mysql_insert_id();
}


?>
