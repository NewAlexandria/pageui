<?phpfunction AddGroup ( $new_group, $page_id, $user_id, $row = 0, $col = 0) {	// add check for if group name already exists	//  this is both for the user's groups, as well as for the any user group in the system	/// check group capitalization usage	global $errtxt;	$n = $new_group;	$new_group = trim($n);	$errtxt = '<br/>'.$new_group;		if ( $new_group == '' ) {		Leave('New groups need titles!', $page);		exit;	}		//get the ID if it exists, and creates one if needed	$group_id = GetGroupID ( $new_group );		$sql = "select * from GroupsPages where user_id = ".$user_id." AND page_id = ".$page_id." AND group_id = '".$group_id."' order by page_id, row, col";	$errtxt .= '<br/>'.$sql;		$result = mysql_query($sql);	$errtxt .= '<br />'.mysql_num_rows($result);	// this row did not exist in the system, so we have to make it	if (mysql_num_rows($result) != 0) {		Leave('This group already is used on this page.<br/> Please choose another name, or consider reorganizing.', $page);		exit;	}	// now we have the group_id	// it could be : just made (no GP row), new to the user (no GP row), already in use by them (GP row exists)		// czech to see if the group is already in use 			/// ?? max number of rows for groups on any given page is 99.  seems sufficient (given, say, 4 cols of groups per row = 396), but variable with the row # of the system group "unsorted"	$sql = "SELECT GP.row, GP.col FROM GroupsPages GP INNER JOIN Links L ON GP.group_id = L.group_id INNER JOIN Groups G ON GP.group_id = G.id WHERE G.GroupType_id = 1 AND L.user_id = GP.user_id AND L.user_id = ".$user_id." AND GP.page_id = ".$page_id." AND GP.group_id = ".$group_id." GROUP BY GP.row, GP.col";	$errtxt .= $sql;	$result = mysql_query($sql);	$errtxt .= '<br />rows in GP table matching group_id: '.mysql_num_rows($result);	// if a row was returned then we're set and clear for a link INSERT	if (mysql_num_rows($result) == 0) {//			mysql_data_seek($result, (mysql_num_rows($result) - 1) );	// if there is no return, then we need a row for the group in GP		// first, we need the row and col to insert		$sql = "SELECT MAX(GP.row) as row , MAX(GP.col) as col FROM Groups G INNER JOIN GroupsPages as GP ON GP.group_id = G.id INNER JOIN Pages P ON GP.page_id = P.id LEFT OUTER JOIN Links as L ON (L.user_id = GP.user_id AND L.group_id = GP.group_id AND L.page_id = GP.page_id ) WHERE GP.user_id = ".$user_id." AND GP.page_id = ".$page_id." AND G.GroupType_id = 1 GROUP BY GP.row, GP.col ORDER BY GP.row, GP.col";		//SELECT MAX(GP.row) as row, MAX(GP.col) as col FROM GroupsPages GP INNER JOIN Links L ON GP.group_id = L.group_id INNER JOIN Groups G ON GP.group_id = G.id WHERE G.GroupType_id = 1 AND L.user_id = GP.user_id AND L.user_id = ".$user_id." AND GP.page_id = ".$page_id." GROUP BY GP.group_id, GP.col, GP.row ORDER BY GP.row, GP.col";		$errtxt .= '<BR/> '.$sql;			$result = mysql_query($sql);		$errtxt .= '<br />'.mysql_num_rows($result);		if (mysql_num_rows($result) > 0) {			mysql_data_seek($result, (mysql_num_rows($result) - 1) );			$resGroupMax = mysql_fetch_assoc($result);			$errtxt .= '<br />pre Row: '.$row.'  Col: '.$col;			$row = $resGroupMax['row'];			$col = $resGroupMax['col'];						//set the values for insertion of new group			/// THREE COL MAX is hardcoded......			if ($col < 3) {				$col += 1;				// keep row the same			} else {				$row += 1;				$col = 1;			}			$errtxt .= '<br />post Row: '.$row.'  Col: '.$col;		} else {		// else, the didn't have any groups on the page			/// if this is the first group of their first page, perhaps we should congratulate them			$msg = "Huzzah! The first group of this page";			$row = 1;			$col = 1;		}		$errtxt .= '<br />final Row: '.$row.'  Col: '.$col;			// else we must have just inserted it	} else {	$errtxt .= '<br />forced Row: '.$row.'  Col: '.$col;		$row = 1;		$col = 1;			}			// now we INSERT into GP	$sql = "INSERT INTO GroupsPages (group_id, page_id, user_id, row, col, date_created) VALUES (".$group_id.", ".$page_id.", ".$user_id.", ".$row.", ".$col.", '".date("Y-m-d")."')";	$errtxt .= $sql;		$result = mysql_query($sql);	$errtxt .= '<br/>num: '.mysql_affected_rows();		InsertGroupHistory ($group_id, $page_id, $user_id, $row, $col);		return $group_id;}function EditGroupName ($user_id, $page_id, $group_id, $title) {	global $errtxt;	require_once('links_lib.php');		if ( $title == '' ) {		Leave('Groups need names ;)', $page);		exit;	}	//get the ID if it exists, and creates one if needed	$group_id_new = GetGroupID ( $title );		$sql = "UPDATE GroupsPages SET group_id = ".$group_id_new." WHERE group_id = ".$group_id." AND page_id = ".$page_id." AND user_id = ".$user_id."";	$errtxt .= '<br/>'.$sql;		$result = mysql_query($sql);	$errtxt .= '<br/>num: '.mysql_affected_rows();	InsertGroupHistory ($group_id, $page_id, $user_id);	$sql = "UPDATE Links SET group_id = ".$group_id_new." WHERE group_id = ".$group_id." AND page_id = ".$page_id." AND user_id = ".$user_id."";	$errtxt .= '<br/>'.$sql;		$result = mysql_query($sql);	$errtxt .= '<br/>num: '.mysql_affected_rows();		InsertLinkHistory ($link_id, $URL, $title, $group_id, $user_id, $row, $col);}function DeleteGroup ($user_id, $page_id, $group_id) {	global $errtxt;	$sql = "SELECT L.*, GP.row, GP.col FROM GroupsPages GP LEFT OUTER JOIN Links L ON (GP.group_id = L.group_id AND GP.user_id = L.user_id AND GP.page_id = L.page_id) WHERE GP.group_id = ".$group_id." AND GP.page_id = ".$page_id." AND GP.user_id = ".$user_id."";	$result = mysql_query($sql);	$errtxt .=  '<br />'.$sql;	if ( mysql_num_rows ( $result ) > 0 ) {		// we should get wo ways this runs, with a set of rows that have an id, or one row that is null for the link fields (i.e. group has no links)		while ( $r = mysql_fetch_assoc ( $result ) ) {			$col = $r['col'];			$row = $r['row'];			if ( $r['id'] != '' ) {				require_once('links_lib.php');					LinkDelete ($r['id'], $user_id);				$errtxt  .= '<br/>link! '.$r['id'].';;';			} else {				// if the id field is empty then the database row was null from the LEFT OUTER JOIN, so we delete the GP entry				$errtxt  .= '<br />no links';			}		}		DeleteFromGP ($user_id, $group_id, $page_id, $col, $row);	}}function updateGroupSort ( $user_id, $page_id, $grow, $aGroups ) {	global $errtxt;	$sort = 1;	$row_is_new = 0;		// loop the SQL inserts		$errtxt .= '<br/>groups count: '.count($aGroups); 	$errtxt .= '<br/>groups empty: '.($aGroups=='');	$errtxt .= '<br/>grow orig: '.$grow;			// the code here has several important dimensions:  any call to this procedure will happen in twos, one for each group row that sent or received a group.  these can fire in either order, and so this procedure must be neutral to that sequence.  This neutrality is necessary for new-row or empty-row updates, which shift the row position of many groups in order to maintain consistency.  When this shift happens before another update, that second update (as of 12/8/08 it is always non-row-shifting) must account for the revised update position, since the variable for row position comes in from the web page � since it's an update.  Modifying this update is done with a table, admin_SQL_TRANSACTIONS, as detailed below.		$mod = '';	if ($aGroups=='') {		// if no group_ids were passed then all was removed from this group.  delete it by decrementing the above rows		$sql = "UPDATE GroupsPages SET row = row - 1 WHERE row > ".$grow." AND user_id = ".$user_id . " AND page_id = ".$page_id;		$errtxt .= '<br/>'.$sql; 		$result = mysql_query($sql);		$errtxt .= '<br/>num: '.mysql_affected_rows();					// if we do the above SQL row shift, it will impact a subsequent GroupsPages update whch fall in the shifted range.  In order to prevent this we need a Transactional system of updating; and since that is too much overhead for these sortUpdates, a simple one is created by storing the shift direction and the row-limit for shifting.  These are formated into one variable with a comma separator.  When checked later, the variable is exploded() again.				$sql = "SELECT sql_str FROM admin_SQL_TRANSACTIONS WHERE user_id = ".$user_id.' ORDER BY id DESC';		$errtxt .= '<br/>check: '.$sql; 		$result = mysql_query($sql);		$errtxt .= '<br/>num: '.mysql_num_rows ( $result );		// if there were rows queued up from the last insert, so we'll clear that 'transaction' rather than adding one		if ( mysql_num_rows ( $result ) != 0 ) {			while ( $r = mysql_fetch_assoc ( $result ) ) {				$errtxt .= '<br/>'.$r['sql_str']; 				$result = mysql_query( $r['sql_str'] );			}						// then we clear the admin_SQL_TRANSACTIONS table for completenss & integrity			$sql = 'DELETE FROM admin_SQL_TRANSACTIONS WHERE user_id = '.$user_id;			$errtxt .= '<br/>'.$sql; 			$result = mysql_query($sql);						$errtxt .= '<br/>num: '.mysql_affected_rows();		} else {					$sqlt = "INSERT INTO admin_SQL_TRANSACTIONS (type_id, sql_str, user_id, created_at) VALUES (1, '-1,".$grow."', ".$user_id . ", '".date("Y-m-d H:m:s")."')";			$errtxt .= '<br/>queued: '.$sqlt;			$result = mysql_query($sqlt);		}	} else {		// if they dropped it onto a new group row		if ( substr ( $grow, 0, 1 ) == 'n' ) {			//advance all current rows			$sql = "UPDATE GroupsPages SET row = row + 1 WHERE row > ".substr ( $grow, 1 )." AND user_id = ".$user_id . " AND page_id = ".$page_id;			$errtxt .= '<br/>'.$sql; 			$result = mysql_query($sql);			$errtxt .= '<br/>num: '.mysql_affected_rows();			// see the comment above on "if we do the above SQL row shift"			$sqlt = "INSERT INTO admin_SQL_TRANSACTIONS (type_id, sql_str, user_id, created_at) VALUES (1, '+1,".substr ( $grow, 1 )."', ".$user_id . ", '".date("Y-m-d H:m:s")."')";			$errtxt .= '<br/>queued: '.$sqlt; 			$result = mysql_query($sqlt);						//set the updates to insert to the interleave			$grow = substr ( $grow, 1 )+1;						$errtxt .= '<br/>grow: '.$grow;			$sql = "SELECT sql_str FROM admin_SQL_TRANSACTIONS WHERE user_id = ".$user_id.' ORDER BY id DESC';			$errtxt .= '<br/>check: '.$sql; 			$result = mysql_query($sql);			$errtxt .= '<br/>num: '.mysql_num_rows ( $result );			$row_is_new = 1;					} else {				//  this shifts the group insert position when a previous UPDATE call has already updated another set of group_ids into this row position.  We select the row for a to-be-updated group_id, finding that it has already been shifted by another UPDATE statement (to a different row than our input $grow) we make $grow be the newly-discovered row value.			// hence, we only want to do this if $grow was not previous a 'n*' value			$sql = "SELECT sql_str FROM admin_SQL_TRANSACTIONS WHERE type_id = 1 AND user_id = ".$user_id." AND created_at = '".date("Y-m-d H:m:s")."' ORDER BY id DESC";			$errtxt .= '<br/>'.$sql; 			$result = mysql_query($sql);			$errtxt .= '<br/>num: '.mysql_num_rows ( $result );						// any rows in admin_SQL_TRANSACTIONS exist then we need to account for them			if ( mysql_num_rows ( $result ) > 0 ) {						$r = mysql_fetch_assoc ( $result );								// break out the encoded variable				$mod = explode(',', $r['sql_str']);								// the second half is row limiter from the previous execution.  if our input group_row data is greater, then we need to shift the group_row that will be used in our main updateSort loop (below) with the correctly-shit group row, which is the first variable in the admin_SQL_TRANSACTIONS item.				if ( intval($grow) > intval($mod[1]) ) { $grow .= $mod[0]; }				$errtxt .= '<br/>mod: '.$mod[0].','.$mod[1];				$errtxt .= '<br/>mod: '.$r['sql_str'];								// then we clear the admin_SQL_TRANSACTIONS table for completenss & integrity				$sql = 'DELETE FROM admin_SQL_TRANSACTIONS WHERE user_id = '.$user_id;				$errtxt .= '<br/>'.$sql; 				$result = mysql_query($sql);				$errtxt .= '<br/>num: '.mysql_affected_rows();											}		}				// get the groupd_IDs existing in the row to be inserted......		$sql = "SELECT G.Title AS GTitle, GP.row as grow, GP.col as gcol, G.id as gid FROM Groups G INNER JOIN GroupsPages as GP ON GP.group_id = G.id INNER JOIN Pages P ON GP.page_id = P.id LEFT OUTER JOIN Links as L ON (L.user_id = GP.user_id AND L.group_id = GP.group_id AND L.page_id = GP.page_id ) WHERE GP.user_id = ".$user_id." AND GP.page_id = ".$page_id." AND GP.row = (".$grow.") GROUP BY G.id ORDER BY GP.row, GP.col, L.col, L.row";		$errtxt .= '<br/>'.$sql; 		$result = mysql_query($sql);				// if the $row_is_new and row has items in it then we'll need to put the updates in the admin_SQL_TRANSACTIONS queue				if ( $row_is_new == 1 and mysql_num_rows ( $result ) > 0 ) {			foreach ( $aGroups as $i ) {					$sqlt = "UPDATE GroupsPages SET col = (".$sort."), row = (".$grow.") WHERE group_id = " . $i . " AND user_id = ".$user_id . " AND page_id = ".$page_id;				$sql = "INSERT INTO admin_SQL_TRANSACTIONS (type_id, sql_str, user_id, created_at) VALUES (2, '".$sqlt."', ".$user_id . ", '".date("Y-m-d H:m:s")."')";							$errtxt .= '<br/>'.$sql; 					$result = mysql_query($sql);				//InsertLinkHistory ( $i, 'NULL', 'NULL', 'NULL', 'NULL', $sort, $col );				$errtxt .= '<br/>num: '.mysql_affected_rows();				$sort += 1;					}		} else {			// use array_diff ($aGroups, $r) to get straggler, for re-positioning.  this is a failsafe against documented orphans of unknown origin.  poor little guys, and poor me.			// $r = mysql_fetch_assoc ( $result );			/*			$j = 0;			$a = Array();			while ( $r = mysql_fetch_assoc ( $result ) ) {				$a[j] = $r['gid'];				$j += 1;			}			$diff = array_diff ($aGroups, $a);			*/						foreach ( $aGroups as $i ) {					$sql = "UPDATE GroupsPages SET col = (".$sort."), row = (".$grow.") WHERE group_id = " . $i . " AND user_id = ".$user_id . " AND page_id = ".$page_id;				$errtxt .= '<br/>'.$sql; 					$result = mysql_query($sql);				$errtxt .= '<br/>num: '.mysql_affected_rows();					InsertGroupHistory ($i, $page_id, $user_id, $grow, $sort);						$sort += 1;					}						/*			/// BUG we could instead get the max row from $r, build an array values to that index $ar = ( 1, 2, ... n), then array_diff($ar, $r[1]) to find any missing rows.  if exists then update to that row, else update just as below:			foreach ( $diff as $i ) {					$sql = "UPDATE GroupsPages SET col = (".$sort."), row = (".$grow.") WHERE group_id = " . $i . " AND user_id = ".$user_id . " AND page_id = ".$page_id;				$errtxt .= '<br/>straggler sort: '.$sql; 					$result = mysql_query($sql);				$sort += 1;					}			*/		}	}}/************************  HELPER FUNCTIONS ************************/// This procedure assumes that no links are left attached to it, and has no internal checks for sake of speed.  Calling this with links still attached will orphan them from display, and should the user add again to that page a group of the same name, the links will appear again.function DeleteFromGP ($user_id, $group_id, $page_id, $col_orig, $row_orig) {	/// SPEED can we do the next two checks with a since SQL call?	global $errtxt;		// first we see if there's anythign else ahead, in the same row as the group	$sql = "SELECT GP.group_id, MAX(GP.row) as row, MAX(GP.col) as col FROM GroupsPages GP INNER JOIN Links L ON GP.group_id = L.group_id AND L.page_id = GP.page_id AND L.user_id = GP.user_id WHERE L.user_id = ".$user_id." AND GP.page_id = ".$page_id." AND GP.row = ".$row_orig." AND GP.col > ".$col_orig." GROUP BY GP.group_id, GP.col, GP.row ORDER BY GP.row, GP.col";	$errtxt .= '<BR/> '.$sql;	$result = mysql_query($sql);	$errtxt .= '<br />'.mysql_num_rows($result);	if ( ( mysql_num_rows($result) == 0 ) AND ( $col_orig == 1 ) ) {		$sql = "UPDATE GroupsPages SET row = row-1 WHERE row < 100 AND row > ".$row_orig." AND user_id = ".$user_id." AND page_id = ".$page_id."  ";		$errtxt .= '<BR/> '.$sql;			$result = mysql_query($sql);		$errtxt .= '<br/>num: '.mysql_affected_rows();	} else if ( mysql_num_rows($result) > 0 ) {		// precess the cols (in row), no adjust rows		$sql = "UPDATE GroupsPages SET col = col-1 WHERE row < 100 AND user_id = ".$user_id." AND page_id = ".$page_id." AND row = ".$row_orig." AND col > ".$col_orig." ";		$errtxt .= '<BR/> '.$sql;			$result = mysql_query($sql);		$errtxt .= '<br/>num: '.mysql_affected_rows();	} 		// then we should delete the GP reference, if it's not a system table	if ( $GroupType_id != 2) {		$sql = "DELETE FROM GroupsPages WHERE group_id = ".$group_id." AND user_id = ".user_id." AND row = ".$row_orig." AND col = ".$col_orig."";		$result = mysql_query($sql);		$errtxt .=  '<br />'.$sql;		$errtxt .= '<br/>num: '.mysql_affected_rows();	}}function GetGroupID ( $title ) {	global $errtxt;		$sql = "SELECT G.id, G.Title FROM Groups G WHERE Title LIKE '".$title."'";	$errtxt .= '<br/>'.$sql;		$result = mysql_query($sql);	$errtxt .= '<br />'.mysql_num_rows($result);	// this row did not exist in the system, so we have to make it	if (mysql_num_rows($result) == 0) {					// do the insert			$sql = "INSERT INTO Groups (Title, date_created) VALUES ('".$title."', '".date("Y-m-d h:i:s A")."')";		$result = mysql_query($sql);		$errtxt .= '<br />'.$sql;		// get group_id		$group_id = mysql_insert_id(); 		$errtxt .=  '<br /> new id: '.mysql_insert_id();	} else {		if (mysql_num_rows($result) > 1) {				/// flag the administrator, there is a chance of data corruption in the Groups table		}		// the group exists in the system; get it's ID and find out if the user has already used this group name				$res = mysql_fetch_assoc($result);		$group_id = $res['id'];	}	return $group_id;}function InsertGroupHistory ($group_id, $page_id, $user_id, $row='0', $col='0') { 	global $errtxt;		// insert link history	$sql = "INSERT INTO GroupsPagesHistory (group_id, page_id, user_id, col, row, date_occurred) VALUES (".$group_id.", ".$page_id.", ".$user_id.", ".$col.", ".$row.", '".date("Y-m-d H:m:s")."')";	$result = mysql_query($sql);		$errtxt .=  '<br />'.$sql;	$errtxt .=  mysql_insert_id();		/// report insert error		//	if (mysql_num_rows($result) < 1) {	//		$fail = 'There was a problem creating your link metadata';	//	}		return mysql_insert_id();}?>