<?php session_start();require_once('config/keys.php');require_once('config/db.php');	/// User logins should be validated against a system login table, and the table should include a variable specific to the login cookie data: either a browser-generated cookie ID, or a seeded rand() saved as cookie var.  If it were hashed against the user password hash then its closer to a cryptographic login validation// setup the ORDER sufix to sort the SQL rowset.// setup, also, display vars and other user-controls (  $group_inv = '';  $URL_inv = '';  $link_inv = '';  $date_inv = '';  /// in the future we may get these from the $_SESSION.  Do huge sessions make for a slow connection??if ( ($_REQUEST['links_flat_list_sort']) or ( $_SESSION['links_flat_list_sort']) ) {		// set session variables to maintain the view	if ( $_REQUEST['links_flat_list_sort'] ) $_SESSION['links_flat_list_sort'] = $_REQUEST['links_flat_list_sort'];	if ( $_REQUEST['inv'] ) $_SESSION['inv'] = $_REQUEST['inv'];	$links_flat_list_sort = $_SESSION['links_flat_list_sort'];	$inv = $_SESSION['inv'];		switch ( $links_flat_list_sort )	{	    case "group":	        $order = ' ORDER BY G.Title ASC, G.row, G.col, L.col, L.row';	        if ( $inv == '1' ) {	        	$order = ' ORDER BY G.Title DESC, G.row, G.col, L.col, L.row';	        } else {	        	$group_inv = '&inv=1';	        }	        			$group_hl = ' class="sort-highlight"';	        break;	    case "date":	        $order = ' ORDER BY L.date_add';	        if ( $inv ) {	        	$order .= ' DESC';	        } else {	        	$date_inv = '&inv=1';	        }	        			$date_hl = ' class="sort-highlight"';	        break;	    case "title":	        $order = ' ORDER BY L.title';	        if ( $inv ) {	        	$order .= ' DESC';	        } else {	        	$link_inv = '&inv=1';	        }	        			$link_hl = ' class="sort-highlight"';	        break;	    case "URL":	        $order = ' ORDER BY L.URL';	        if ( $inv ) {	        	$order .= ' DESC';	        } else {	        	$URL_inv = '&inv=1';	        }	        			$URL_hl = ' class="sort-highlight"';	        break;	    default:	        $order = ' ORDER BY GP.row, GP.col, L.col, L.row';	        break;	}	} if ( $_REQUEST['s_page_id'] ) {	$page_id = $_REQUEST['s_page_id'];} else { 	$page_id = $_SESSION['page_id'];}$sql = "SELECT L.col, L.row, G.Title AS GTitle, GP.row as grow, GP.col as gcol, G.id as gid, L.* FROM Groups G INNER JOIN GroupsPages as GP ON GP.group_id = G.id INNER JOIN Pages P ON GP.page_id = P.id LEFT OUTER JOIN Links as L ON (L.user_id = GP.user_id AND L.group_id = GP.group_id AND L.page_id = GP.page_id ) WHERE GP.user_id = ".$_SESSION['user_id']." AND GP.page_id = ".$page_id." AND G.GroupType_id <> 2 ORDER BY GP.row, GP.col, L.col, L.row";$sqlSys = "SELECT L.col, L.row, G.Title AS GTitle, ".$unsorted_row." as grow, GP.col as gcol, G.id as gid, L.* FROM Groups G INNER JOIN GroupsPages as GP ON GP.group_id = G.id INNER JOIN Pages P ON GP.page_id = P.id LEFT OUTER JOIN Links as L ON (L.user_id = GP.user_id AND L.group_id = GP.group_id AND L.page_id = GP.page_id ) WHERE GP.user_id = ".$_SESSION['user_id']." AND G.GroupType_id = 2 ORDER BY GP.row, GP.col, L.col, L.row";// echo $sql;$result = mysql_query($sql);$errtxt .= '<br/>'.$sql;$errtxt .= '<br/>sql error: '.mysql_error($db).''; $errtxt .= '<br/>rows: '.mysql_num_rows($result).'<br/>'; $resultSys = mysql_query($sqlSys);$errtxt .= '<br/>'.$sqlSys;$errtxt .= '<br/>sql error: '.mysql_error($db).''; $errtxt .= '<br/>rows: '.mysql_num_rows($resultSys).'<br/>'; // this is buit for the links tha re-organize the list of links$sort_url = $site_uri.substr($_SERVER['SCRIPT_NAME'], 1);// $_SERVER['SCRIPT_NAME']?>		<div><?php // the whole group printing is wrapped in a function so that we can call it for regular groups and sys groups.function PrintGroups($result) {	global $errtxt;			if (mysql_num_rows($result)>0) { 			$grow_last = 0;			$gcol_last = 0;			$lcol_last = 0;			$grow_close = '';			$gcol_close = '';			$lcol_close = '';						$AJAX_originals = '';						while ( $res = mysql_fetch_assoc($result) ) { 				$grow = $res['grow'];				$gcol = $res['gcol'];				$lcol = $res['col'];				$edit_link = $site_uri.$site_path.'edit.php?link_id='.$res['id']; 				if ( $grow_close != '') {					if ( $grow_last != $grow ) {						echo '					</td></tr></table></td></tr></table>'.$crlf;					} else if ( $gcol_last != $gcol ) {						echo '					</td></tr></table></td>'.$crlf;					} else if ( $lcol_last != $lcol ) {						echo '					</td>'.$crlf;					}				}								// UL tags inserted with an id based on their group_id and link_col_t								if ( $grow_last != $grow ) { 				// echo $grow_close; 				?>				<table style="width: 100%" class="group_row">				<tr>				<td class="group_block" id="group_<?php echo $res['gid']?>">					<div class="group_title"><?php echo $res['GTitle']?>					</div>					<table class="link_block"><tr><td type="none" class="link_col" id="g-<?php echo $res['gid']?>=c-<?php echo $lcol?>">					<?php			} else if ( $gcol_last != $gcol ) { 				//echo $gcol_close; 				?>				<td class="group_block">					<div class="group_title"><?php echo $res['GTitle']?>					</div>					<table class="link_block"><tr><td type="none" class="link_col" id="g-<?php echo $res['gid']?>=c-<?php echo $lcol?>">					<?php			} else if ( $lcol_last != $lcol ) { 				// echo $lcol_close; 				?>					<td type="none" class="link_col_t" id="g-<?php echo $res['gid']?>=c-<?php echo $lcol?>">					<?php			}				//	the link row will always change				?>						<div class="link" id="link_<?php echo $res['id']?>"><a href="<?php echo $res['URL']?>" target="_blank"><?php echo $res['title']?></a> <a href="mailto:?SUBJECT=<?php echo $res['title'] ?>&BODY=<?php echo $res['title'] . ':  ' .  $res['URL']?>">mail</a></div><?php			if ( $lcol_last != $lcol ) { ?>				<?php			}				if ( $gcol_last != $gcol ) { ?>				<?php			}				if ( $grow_last != $grow ) { ?><?php			}							$grow_last = $grow;				$gcol_last = $gcol;				$lcol_last = $lcol;				$grow_close = '					</td></tr></table></td></tr></table>'.$crlf;				$gcol_close = '					</td></tr></table></td>'.$crlf;				$lcol_close = '					</td>'.$crlf;							} ?>			</td></tr></table></td></tr></table><?php	}	}PrintGroups($result);PrintGroups($resultSys);?>						</div><?php	if ($_SESSION['DEBUG']=='1') { ?>	<script type="text/javascript"><!--	$('#notify_d_page_id').text("<?php echo $page_id;?> (from groups_block_list)");	$('#notify_d_this_page_errtxt').html( $('#notify_d_this_page_errtxt').html() +  "<?php echo $errtxt;?>" + "<br /><?php echo $page_id;?> (from groups_block_list)" );//--></script><?php	}?>