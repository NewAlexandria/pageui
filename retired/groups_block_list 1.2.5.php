<?php session_start();require_once('config/keys.php');require_once('config/db.php');	/// User logins should be validated against a system login table, and the table should include a variable specific to the login cookie data: either a browser-generated cookie ID, or a seeded rand() saved as cookie var.  If it were hashed against the user password hash then its closer to a cryptographic login validationif ( $_REQUEST['s_page_id'] ) {	$page_id = $_REQUEST['s_page_id'];} else { 	$page_id = $_SESSION['page_id'];}$user_id = ReturnSecureString( $_SESSION['user_id'] );$sql = "SELECT L.col as lcol, L.row, G.Title AS GTitle, GP.row as grow, GP.col as gcol, G.id as gid, L.* FROM Groups G INNER JOIN GroupsPages as GP ON GP.group_id = G.id INNER JOIN Pages P ON GP.page_id = P.id LEFT OUTER JOIN Links as L ON (L.user_id = GP.user_id AND L.group_id = GP.group_id AND L.page_id = GP.page_id ) WHERE GP.user_id = ".$user_id." AND GP.page_id = ".$page_id." AND G.GroupType_id <> 2 ORDER BY GP.row, GP.col, L.col, L.row";// $sqlSys = "SELECT L.col as lcol, L.row, G.Title AS GTitle, ".$unsorted_row." as grow, 1 as gcol,  G.id as gid,  L.* FROM Links as L INNER JOIN Groups G ON L.group_id = G.id  WHERE L.user_id = ".$_SESSION['user_id']." AND G.GroupType_id = 2 ORDER BY grow, gcol, L.col, L.row";$sqlSys = "SELECT L.col as lcol, L.row, G.Title AS GTitle, 100 as grow, 1 as gcol, G.id as gid, L.* FROM Links as L INNER JOIN Groups G ON L.group_id = G.id WHERE L.user_id = ".$user_id." AND G.GroupType_id = 2 ORDER BY grow, gcol, L.col, L.row";// echo $sql;$result = mysql_query($sql);$errtxt .= '<br/>'.$sql;$errtxt .= '<br/>sql error: '.mysql_error($db).''; $errtxt .= '<br/>rows: '.mysql_num_rows($result).'<br/>'; $resultSys = mysql_query($sqlSys);$errtxt .= '<br/>'.$sqlSys;$errtxt .= '<br/>sql error: '.mysql_error($db).''; $errtxt .= '<br/>rows: '.mysql_num_rows($resultSys).'<br/>'; // this is buit for the links tha re-organize the list of links$sort_url = $site_uri.substr($_SERVER['SCRIPT_NAME'], 1);// $_SERVER['SCRIPT_NAME']?>		<div class="group_set"><?php $grow_last = '0';$AJAX_group_sortables = '';$AJAX_group_sortable_connections = '';$AJAX_link_sortables = '';$AJAX_link_sortable_connections = '';$AJAX_group_originals = "";$AJAX_sizes = '';// the whole group printing is wrapped in a function so that we can call it for regular groups and sys groups.  There probably exists a fancy enough SQL statement to do it all in 1 SQL call.function PrintGroups($result, $sys = 0) {	global $errtxt;	global $grow_last;		//	these 4 strings allow the DB while loop to build the list of groups and links that need to be activated via jQuery	global $AJAX_group_sortables;	global $AJAX_group_sortable_connections;	global $AJAX_link_sortables;	global $AJAX_link_sortable_connections;	global $AJAX_group_originals;	global $AJAX_sizes;			if (mysql_num_rows($result)>0) { 			// $grow_last = 0;  // we set $grow_last outside this proc to maintain group consistency across procedure calls			$gcol_last = 0;			$lcol_last = 0;			$grow_close = '';			$gcol_close = '';			$lcol_close = '';			$gid_last = '';						if ( ($AJAX_group_sortables == '') && ($sys == 0) ) { ?>				<table class="group_row_new" id="groupsRow_<?php echo ('n'.$grow_last) ?>"><tr><td></td></tr></table><?php							$AJAX_group_sortables .= '$("#groupsRow_'.('n'.$grow_last).'").sortable({ %%%, items: ".group_block", dropOnEmpty: true, update: function(element, ui) { updateGroupSort ("#groupsRow_'.('n'.$grow_last).'", "'.('n'.$grow_last).'", ui); }, start: function(e, ui) { groupsSortStart(ui); }, stop: function(e, ui) { groupsSortStop(); }, over: function(e, ui) { groupsSortOver( e, ui); }, out: function(e, ui) { groupsSortOut(ui) }, change: function(e, ui) { groupsSortChange( e, ui); }, receive: function(e, ui) { groupsSortReceive( e, ui); } });'."\r\n"."\r\n".$crlf.$crlf; 				$AJAX_group_sortable_connections .= ', "#groupsRow_'.('n'.$grow_last).'"';			}							while ( $res = mysql_fetch_assoc($result) ) { 				$grow = $res['grow'];				$gcol = $res['gcol'];				$lcol = $res['lcol'];								// this catches -1 lcol values when a group contains no links				if ($lcol == '' ) $lcol = '1';								$edit_link = $site_uri.$site_path.'edit.php?link_id='.$res['id']; 				if ( $grow_close != '') {  // set to a value at the end of each loop.  ensures that the LIs and ULs aren't closed on the first loop.					if ( $grow_last != $grow ) { ?>				</td><td class="link_col_t" id="links_g<?php echo $gid_last.'c'.($lcol_last+1) ?>"></td></tr></table></td></tr></table>			<table class="group_row_new" id="groupsRow_<?php echo 'n'.$grow_last ?>"><tr><td></td></tr></table><?php												$AJAX_group_sortables .= '$("#groupsRow_'.('n'.$grow_last).'").sortable({ %%%, items: ".group_block", dropOnEmpty: true, update: function(element, ui) { updateGroupSort ("#groupsRow_'.('n'.$grow_last).'", "'.('n'.$grow_last).'", ui); }, start: function(e, ui) { groupsSortStart(ui); }, stop: function(e, ui) { groupsSortStop(); }, over: function(e, ui) { groupsSortOver( e, ui); }, out: function(e, ui) { groupsSortOut(ui) }, change: function(e, ui) { groupsSortChange( e, ui); }, receive: function(e, ui) { groupsSortReceive( e, ui); } });'."\r\n"."\r\n".$crlf.$crlf; 						$AJAX_link_sortables .= '$("#links_g'.$gid_last.'c'.($lcol_last+1).'").sortable({ %%%, items: ".link_edit", receive: function (e, ui) { updateLinkReceive("#links_g'.$res['gid'].'c'.$lcol.'", ui) }, update: function(element, ui) { updateLinkSort ("#links_g'.$gid_last.'c'.($lcol_last+1).'", "n'.($lcol_last+1).'", "'.$gid_last.'", ui); }, start: function(e, ui) { linksSortStart(e, "link_'.$res['id'].'"); }, stop: function(e, ui) { linksSortStop(e, "link_'.$res['id'].'"); } });'."\r\n"."\r\n".$crlf.$crlf; 						//-------- BUILD sortables CONNECTIONS						$AJAX_link_sortable_connections .= ', "#links_g'.$gid_last.'c'.($lcol_last+1).'"';						$AJAX_group_sortable_connections .= ', "#groupsRow_'.('n'.$grow_last).'"';					} else if ( $gcol_last != $gcol ) { ?>					</td><td class="link_col_t" id="links_g<?php echo $gid_last.'c'.($lcol_last+1) ?>"></td></tr></table>				</td><?php															$AJAX_link_sortables .= '$("#links_g'.$gid_last.'c'.($lcol_last+1).'").sortable({ %%%, items: ".link_edit", receive: function (e, ui) { updateLinkReceive("#links_g'.$res['gid'].'c'.$lcol.'", ui) }, update: function(element, ui) { updateLinkSort ("#links_g'.$gid_last.'c'.($lcol_last+1).'", "n'.($lcol_last+1).'", "'.$gid_last.'", ui); }, start: function(e, ui) { linksSortStart(e, "link_'.$res['id'].'"); }, stop: function(e, ui) { linksSortStop(e, "link_'.$res['id'].'"); } });'."\r\n"."\r\n".$crlf.$crlf; 						//-------- BUILD sortables CONNECTIONS						$AJAX_link_sortable_connections .= ', "#links_g'.$gid_last.'c'.($lcol_last+1).'"';					} else if ( $lcol_last != $lcol ) { ?>				</td><?php					}				}								// UL tags inserted with an id based on their group_id and link_col								if ( $grow_last != $grow ) {  // if this is a new group row								//-------- BUILD sortables CALLS				$AJAX_group_sortables .= '$("#groupsRow_'.$res['grow'].'").sortable({ %%%, items: ".group_block", dropOnEmpty: true, update: function(element, ui) { updateGroupSort ("#groupsRow_'.$res['grow'].'", '.$res['grow'].', ui); }, start: function(e, ui) { groupsSortStart(ui); }, stop: function(e, ui) { groupsSortStop(); }, over: function(e, ui) { groupsSortOver( e, ui); }, out: function(e, ui) { groupsSortOut(ui) }, change: function(e, ui) { groupsSortChange( e, ui); }, receive: function(e, ui) { groupsSortReceive( e, ui); } });'."\r\n"."\r\n".$crlf.$crlf; 				$AJAX_link_sortables .= '$("#links_g'.$res['gid'].'c'.$lcol.'").sortable({ %%%, items: ".link_edit", receive: function (e, ui) { updateLinkReceive("#links_g'.$res['gid'].'c'.$lcol.'", ui) }, update: function(element, ui) { updateLinkSort ("#links_g'.$res['gid'].'c'.$lcol.'", "'.$lcol.'", "'.$res['gid'].'", ui); }, start: function(e, ui) { linksSortStart(e, "link_'.$res['id'].'"); }, stop: function(e, ui) { linksSortStop(e, "link_'.$res['id'].'"); } });'."\r\n"."\r\n".$crlf.$crlf; //  this and similar commented items are the remains of coding left and right handed new-link columns.  right now the page has only left-handed.//				$AJAX_link_sortables .= '$("#links_g'.$res['gid'].'c'.($lcol-1).'").sortable({ %%%, update: function(element, ui) { updateLinkSort ("#links_g'.$res['gid'].'c'.($lcol-1).'", "'.($lcol-1).'", "'.$res['gid'].'"); }, start: function(e, ui) { linksSortStart(e, "link_'.$res['id'].'"); }, stop: function(e, ui) { linksSortStop(e, "link_'.$res['id'].'"); } });'."\r\n"."\r\n".$crlf.$crlf; 								//-------- BUILD sortables CONNECTIONS				$AJAX_link_sortable_connections .= ', "#links_g'.$res['gid'].'c'.$lcol.'"';//				$AJAX_link_sortable_connections .= ', "#links_g'.$res['gid'].'c'.($lcol-1).'"';				$AJAX_group_sortable_connections .= ', "#groupsRow_'.$res['grow'].'"';								$AJAX_link_sortable_updates = 'update: linkUpdate(element, ui, "'.') ")';								//-------- BUILD sortable's objects DATA				$AJAX_group_originals .= "g_orig[".$res['gid']."] = $('#group_".$res['gid']."_name').html();\n";								$AJAX_sizes .= " $('#group_".$res['gid']."').css({ 'pageuiWidth': $('#group_".$res['gid']."').width() });\n"//				"alert( '".$res['gid'].": ' + $('#group_".$res['gid']."').css('pageuiWidth') );";				?>				<table class="group_row"><tr id="groupsRow_<?php echo $res['grow']?>">				<td class="group_block" id="group_<?php echo $res['gid']?>">					<table cellpadding="0" cellspacing="0" width="100%" class="group_title"><tr><td>						<div id="group_<?php echo $res['gid']?>_name"><?php echo $res['GTitle']?> <?php if ($_SESSION['DEBUG']==1) echo $res['gid']?></div>					</td><?php if ( $sys == 0 ) { ?>										<td align="right"><div class="link_UI_element">						<a class="link-look" onclick="addLink_UI(<?php echo $res['gid']; ?>,'group_<?php echo $res['gid']?>');" style="padding: 0 2px 0 1px; ">+</a> 						<a class="link-look" onclick="updateGroup_UI('<?php echo $res['gid']; ?>','group_<?php echo $res['gid']?>', '<?php echo $res['GTitle']?>');" style="padding: 0 2px 0 1px; ">E</a> 						<a class="link-look" onclick="deleteGroup('<?php echo $res['gid']; ?>');" style=" padding: 0 1px 0 1px; ">X</a>					</div></td><?php } ?>					</tr></table>					<table class="link_block"><tr><!-- 					<div class="link_col" id="links_g<?php echo $res['gid']?>c<?php echo ($lcol-1)?>"></div> -->					<td class="link_col" id="links_g<?php echo $res['gid']?>c<?php echo $lcol?>">					<?php			} else if ( $gcol_last != $gcol ) {  // if this is a new group col				//-------- BUILD sortables CALLS				$AJAX_link_sortables .= '$("#links_g'.$res['gid'].'c'.$lcol.'").sortable({ %%%, items: ".link_edit", receive: function (e, ui) { updateLinkReceive("#links_g'.$res['gid'].'c'.$lcol.'", ui) }, update: function(element, ui) { updateLinkSort ("#links_g'.$res['gid'].'c'.$lcol.'", "'.$lcol.'", "'.$res['gid'].'", ui); }, start: function(e, ui) { linksSortStart(e, "link_'.$res['id'].'"); }, stop: function(e, ui) { linksSortStop(e, "link_'.$res['id'].'"); } });'."\r\n"."\r\n".$crlf.$crlf; //				$AJAX_link_sortables .= '$("#links_g'.$res['gid'].'c'.($lcol-1).'").sortable({ %%%, update: function(element, ui) { updateLinkSort ("#links_g'.$res['gid'].'c'.($lcol-1).'", "'.($lcol-1).'", "'.$res['gid'].'"); }, start: function(e, ui) { linksSortStart(e, "link_'.$res['id'].'"); }, stop: function(e, ui) { linksSortStop(e, "link_'.$res['id'].'"); } });'."\r\n"."\r\n".$crlf.$crlf; 				//-------- BUILD sortables CONNECTIONS				$AJAX_link_sortable_connections .= ', "#links_g'.$res['gid'].'c'.$lcol.'"';//				$AJAX_link_sortable_connections .= ', "#links_g'.$res['gid'].'c'.($lcol-1).'"';				//-------- BUILD sortable's objects DATA				$AJAX_sizes .= " $('#group_".$res['gid']."').css({ 'pageuiWidth': $('#group_".$res['gid']."').width() });\n"//				$AJAX_sizes .= "alert( '".$res['gid'].": ' + $('#group_".$res['gid']."').css('pageuiWidth') );";								?>				<td class="group_block" id="group_<?php echo $res['gid']?>">					<table cellpadding="0" cellspacing="0" width="100%" class="group_title"><tr><td>						<div id="group_<?php echo $res['gid']?>_name"><?php echo $res['GTitle']?> <?php if ($_SESSION['DEBUG']==1) echo $res['gid']?></div>					</td><?php if ( $sys == 0 ) { ?>											<td align="right"><div class="link_UI_element">						<a class="link-look" onclick="addLink_UI(<?php echo $res['gid']; ?>,'group_<?php echo $res['gid']?>');" style="padding: 0 2px 0 1px; ">+</a> 						<a class="link-look" onclick="updateGroup_UI('<?php echo $res['gid']; ?>','group_<?php echo $res['gid']?>', '<?php echo $res['GTitle']?>');" style="padding: 0 2px 0 1px; ">E</a> 						<a class="link-look" onclick="deleteGroup('<?php echo $res['gid']; ?>');" style=" padding: 0 1px 0 1px; ">X</a>					</div></td><?php } ?>					</tr></table>					<table class="link_block"><tr>					<!-- 					<div class="link_col" id="links_g<?php echo $res['gid']?>c<?php echo ($lcol-1)?>"></div> -->					<td class="link_col" id="links_g<?php echo $res['gid']?>c<?php echo $lcol?>">					<?php			} else if ( $lcol_last != $lcol ) { // if this is a new link col				//-------- BUILD sortables CALLS				$AJAX_link_sortables .= '$("#links_g'.$res['gid'].'c'.$lcol.'").sortable({ %%%, items: ".link_edit", receive: function (e, ui) { updateLinkReceive("#links_g'.$res['gid'].'c'.$lcol.'", ui) }, update: function(element, ui) { updateLinkSort ("#links_g'.$res['gid'].'c'.$lcol.'", "'.$lcol.'", "'.$res['gid'].'", ui); }, start: function(e, ui) { linksSortStart(e, "link_'.$res['id'].'"); }, stop: function(e, ui) { linksSortStop(e, "link_'.$res['id'].'"); } });'."\r\n"."\r\n".$crlf.$crlf; 				//-------- BUILD sortables CONNECTIONS				$AJAX_link_sortable_connections .= ', "#links_g'.$res['gid'].'c'.$lcol.'"';				// echo $lcol_close; 				?>					<td class="link_col" id="links_g<?php echo $res['gid']?>c<?php echo $lcol?>">					<?php			}					if ( $res['URL'] != '' ) {				//-------- BUILD sortable's objects DATA				$AJAX_sizes .= " $('#link_".$res['id']."').css({ 'pageuiWidth': $('#link_".$res['id']."').width() });\n"									//	the link row will always change / be new		?>						<div class="link_edit" id="link_<?php echo $res['id']?>">						<!-- class="link" id="link_<?php echo $res['id'] ?>" -->							<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td style="width: 95%">							<div class="link_text"><a href="#" onclick="javascript:updateLink_UI('<?php echo $res['id']; ?>', 'link_<?php echo $res['id']?>', getScroll() );"><?php echo $res['title']?></a></div>							</td><td valign="top" style="width: 16px">							<div class="link_UI_element tmenu">								<a class="link-look" style=" padding: 0 1px 0 1px; " onclick="deleteLink('<?php echo $res['id']; ?>');">X</a>							</div>							</td></tr></table>						</div><?php			// these IFs allow any wrap-up code, given something new					}				if ( $lcol_last != $lcol ) { ?>				<?php			}				if ( $gcol_last != $gcol ) { ?>				<?php			}				if ( $grow_last != $grow ) { 				?><?php			} //				$errtxt .= '<br />lcol: '.$lcol.' lcol-last: '.$lcol_last.' title: '.$res['title'];							$grow_last = $grow;				$gcol_last = $gcol;				$lcol_last = $lcol;				$gid_last = $res['gid'];				$grow_close = 'dif'.$crlf;				$gcol_close = 'dif'.$crlf;				$lcol_close = '				</td>'.$crlf;			} 			?>			</td>			<td class="link_col_t" id="links_g<?php echo $gid_last ?>c<?php echo ($lcol_last+1) ?>"></td>		</tr></table><?php			$AJAX_link_sortables .= '$("#links_g'.$gid_last.'c'.($lcol_last+1).'").sortable({ %%%, items: ".link_edit", receive: function (e, ui) { updateLinkReceive("#links_g'.$res['gid'].'c'.$lcol.'", ui) }, update: function(element, ui) { updateLinkSort ("#links_g'.$gid_last.'c'.($lcol_last+1).'", "n'.($lcol_last+1).'", "'.$gid_last.'", ui); }, start: function(e, ui) { linksSortStart(e, "link_'.$res['id'].'"); }, stop: function(e, ui) { linksSortStop(e, "link_'.$res['id'].'"); } });'."\r\n"."\r\n".$crlf.$crlf; 		//-------- BUILD sortables CONNECTIONS		$AJAX_link_sortable_connections .= ', "#links_g'.$gid_last.'c'.($lcol_last+1).'"';						if ( $sys == 0 ) { ?>			</td></tr></table><table class="group_row_new" id="groupsRow_<?php echo ('n'.$grow_last) ?>"><tr><td><?php			$AJAX_group_sortables .= '$("#groupsRow_'.('n'.$grow_last).'").sortable({ %%%, items: ".group_block", dropOnEmpty: true, update: function(element, ui) { updateGroupSort ("#groupsRow_'.('n'.$grow_last).'", "'.('n'.$grow_last).'", ui); }, start: function(e, ui) { groupsSortStart(ui); }, stop: function(e, ui) { groupsSortStop(); }, over: function(e, ui) { groupsSortOver( e, ui); }, out: function(e, ui) { groupsSortOut(ui) }, change: function(e, ui) { groupsSortChange( e, ui); }, receive: function(e, ui) { groupsSortReceive( e, ui); } });'."\r\n"."\r\n".$crlf.$crlf; 			$AJAX_group_sortable_connections .= ', "#groupsRow_'.('n'.$grow_last).'"';		}?>				</td></tr></table><?php	}}PrintGroups($result);PrintGroups($resultSys, 1);// here we finalize the connection strings, then insert them into the .sortable calls$AJAX_link_sortable_connections = 'connectWith: ['.substr($AJAX_link_sortable_connections, 2).']'; $AJAX_link_sortables = str_replace("%%%", $AJAX_link_sortable_connections, $AJAX_link_sortables);$arr = explode(",", substr($AJAX_group_sortable_connections, 2));$AJAX_group_sortable_connections = 'connectWith: ['.substr($AJAX_group_sortable_connections, 2).']';	$AJAX_group_sortables = str_replace("%%%", $AJAX_group_sortable_connections, $AJAX_group_sortables);if ( strstr($_SESSION['cur_page'], 'edit') || strstr($_SESSION['cur_page'], 'intro3') ) { ?><script type="text/javascript"><!--var scrollPos = 0;var updateState = 0;var updateStateGroup = 0;var addStateGroup = 0;var execGroupSort = 0;var user_id = <?php echo $_SESSION['user_id'] ?>;var page_id = <?php echo $page_id ?>;var old_f = document.onkeydown;var gg_id = 0;var gTitle = '';if ( screen.width <= 1100 ) { 	var pScreenMax = screen.width - 550;} else if ( ( screen.width > 1100 ) && ( screen.width <= 1700 ) ) { 	var pScreenMax = screen.width - 550 - 390;  // Math.round(screen.width * 0.66);} else if ( screen.width > 1700 ) { 	var pScreenMax = screen.width - 550 - 390;  // Math.round(screen.width * 0.66);}// cron flagsvar runGroupUpdate = '';var ranGroupReceive = 0;var ranGroupUI = '';var runLinkUpdate = '';var ranLinkReceive = 0;var ranLinkUI = '';var cron_task;var gNodes;// nascent code for loading a UI holder once & first, to avoid scroll issues with dynamic loading (of a .parent=body .append with position:absolute).  perhaps one day this will be used again//	$("body").append('<div id="links_UI_holder"><'+'/div>');//	$("#UI_holder").load( "links_edit_front.php" );//	$("#UI_holder").css( { display: "none" } );g_orig = Array ();/**************** LINK UI ******************/function updateLink_UI( l_id, element, scrd ) {	if ( updateState == 0 ) {			ClearUIs();				scr = getScroll();		$.post("links_edit_front.php", { link_id: l_id },		  function(data){//		  	$(data).appendTo("#"+element);			$(data).prependTo("body");		  	// positioning			var dim = $("#"+element).offset();		  	$("#links_UI").css( { display: "none", top: dim.top, left: dim.left  } );  		  	// ergonomics		  	$("#new_link_form").css( { visibility: "hidden" } );		  	$("#links_UI").show(290, function() { $("#new_link_form").css( { visibility: "visible" } ); });		  	$("#new_link_form").fadeIn();		  							// this is the only procedure that seems to reset the scroll within a proc			$('html,body').animate({scrollTop: scr}, 1);		 }); 		updateState = 1;	} else {		updateLink_UI_Clear();	}	}function updateLink_UI_Clear() {	$("#links_UI").fadeOut('fast', function() { $("#links_UI").remove(); });	updateState = 0;}function addLink_UI( group_id, element ) {	if ( updateState == 0 ) {		ClearUIs();		scr = getScroll();				$.post("links_edit_front.php?new=1&group_id="+group_id,		  function(data){			$(data).prependTo("body");		  			  	// positioning			var dim = $("#"+element).offset();//			$("#notify").text( element + ' ' + dim.top + ' ' + dim.left);		  	$("#links_UI").css( { display: "none", top: (dim.top+10)+'px', left: (dim.left+10)+'px' } );		  			  	// ergonomics		  	$("#new_link_form").css( { visibility: "hidden" } );		  	$("#links_UI").show(290, function() { $("#new_link_form").css( { visibility: "visible" } ); });		  	$("#new_link_form").fadeIn();			// this is the only procedure that seems to reset the scroll within a proc			$('html,body').animate({scrollTop: scr}, 1);		 }); 		updateState = 1;	} else {		$("#links_UI").fadeOut('fast', function() { $("#links_UI").remove(); });		updateState = 0;	}}var limit = '1,2';/********************* LINK MANAGEMENT ********************/function updateLinkSort( nameL, col, gid, ui ) {//	$('#notify').text( $(nameL).sortable('toArray').join("&") );	runLinkUpdate = $(nameL).sortable('toArray').join("&").replace(/_/g,"[]=") + ',' + gid + ',' + col;	if ( !ranLinkUI.sender ) { 		ranLinkUI = ui;	}}function doLinkSort ( a, gid, col ) {	$('#notify_on').load('config/links.php?AJAX=1&proc=updateSort&col=' + col + '&page_id=' + page_id + '&group_id=' + gid + '&' + a, function() { 		refreshContent();	});}function updateLinkReceive ( link_gc, ui ) {	ranLinkReceive = 0;	ranLinkUI = ui;//	console.debug("here: %o", ui.item );}function deleteLink( link_id ) {	var b = confirm("Sure you want to delete this link?");	if ( b == true) {		$('#notify_on').load('config/links.php?AJAX=1&proc=delete&link_id=' + link_id, function() { 			refreshContent();		});			} else {	  // return	}			}/********************* LINK SORTING ********************/function linksSortStart( event, element ) {//	alert('s');	event.stopPropagation();	$(".link_col").css({ 'border' : "1px #DAD5B8 solid", 'margin':"0 2px 0 2px", 'padding':"0px 2px 0px 2px" });	$(".link_col_t").css({ border:"1px #DAD5B8 solid", margin:"0 2px 0 2px", padding: "0 5px 0 5px;", "min-width":"50px", "max-width":"50px"  });	$(element+' a').bind("click", StopFollow);}function linksSortStop( event, element ) {	event.stopPropagation();	$(".link_col").css({ border:"none", margin:"0 3px 0 3px", padding:"1px 2px 1px 2px" });	$(".link_col_t").css({ border:"none", margin:"0 3px 0 3px", padding:"0px", "min-width":"0px", "max-width":"0px"  });	$(element+' a').unbind("click", StopFollow );}/********************* GROUP UI ********************/function updateGroup_UI( g_id, element, Title ) {	gg_id = g_id; // set the currently-edited group_id beyond the function (globally)	gTitle = Title;	if ( updateStateGroup == 0 ) {			ClearUIs();		scr = getScroll();		$('#group_'+g_id+'_name').load('DHTML/updateGroups.php?group_id=' + g_id + '&name='+escape(Title));		// this is the only procedure that seems to reset the scroll within a proc		$('html,body').animate({scrollTop: scr}, 1);		updateStateGroup = 1;	} else {		$('#group_'+g_id+'_name').html(Title);		gg_id = 0;				updateStateGroup = 0;	}}	function addGroup_UI(  ) {	if ( addStateGroup == 0 ) {		ClearUIs();		$.post("groups_edit_front.php?new=1",		  function(data){//		  	$(data).appendTo("#links_groups_edit");			$(data).prependTo("body");		  			  	// positioning			var dim = $("#links_groups_edit").offset();		  	$("#groups_UI").css( { display: "none", top: dim.top+10, left: dim.left+10 } );		  			  	// ergonomics		  	$("#new_group_form").css( { visibility: "hidden" } );		  	$("#groups_UI").show(290, function() { $("#new_group_form").css( { visibility: "visible" } ); });		  	$("#new_group_form").fadeIn();		 }); 		addStateGroup = 1;	} else {		$("#groups_UI").fadeOut('fast', function() { $("#groups_UI").remove(); });		addStateGroup = 0;	}}/********************* GROUP MANAGEMENT ********************/function deleteGroup( g_id ) {	var b = confirm("Sure you want to delete this group?\nAll its links will go, too!");	if ( b == true) {		$('#notify_on').load('config/groups.php?AJAX=1&proc=delete&user_id=' + user_id + '&page_id=' + page_id + '&group_id=' + g_id, function() { 			refreshContent();		});			} else {	  // return	}		}		function updateGroupSort( nameL, grow, ui ) {	// delays set of cron flag to allow receive: to fire first//	alert('1');	if ( !ranGroupUI.sender ) { 		ranGroupUI = ui;	}	console.debug('new test',ui);//	console.debug('got it: %o', ui.element);	var a =  $(nameL).sortable('serialize');	//	alert (ui.item[0].parentNode.id.match(/[n]?[0-9]+/));	runGroupUpdate =  a + ',' + grow;/*	if ( ui.item[0].parentNode.id.match(/[n]+/) == 'n' ) {		console.debug("got it: %o", ui);//		groupsSortReceive (1, ui );	} else {		runGroupUpdate =  a + ',' + grow;	}*/	}function doGroupSort( a, grow ) {		$('#notify_on').load('config/groups.php?AJAX=1&proc=updateSort&grow=' + grow + '&' + a, function() { 			refreshContent();	});}function NEW_updateGroupSort( nameL, grow ) {	// add the groupsort data to a queue	$('#notify_on').load('config/groups.php?AJAX=1&proc=updateSort&grow=' + grow + '&' + $(nameL).sortable('serialize'), function() { 			refreshContent();	});	if ( execGroupSort == 0) {		}}/********************* GROUP SORTING ********************/function groupsSortStart( ui ) {//	$("#"+ui.item[0].id).css({"z-index":"80"});	$(".group_row").css({ "border":"1px #ccc solid" });	$(".group_row_new").css({ "height": "18px", "margin": "1px 0 1px 0", "border":"1px #ccc solid" });}function groupsSortStop(  ) {	$(".group_row").css({ "border":"none" });	$(".group_row_new").css({ "height": "18px", "margin": "1px 0 1px 0", "border":"1px #fff solid" });		$(".group_row").hover( function() { });}function groupsSortOver( ui ) {//	console.debug('over: ', ui);	}function groupsSortOut( ui ) {//	console.debug('out: ', ui);}function groupsSortChange( e, ui ) {	}function groupsSortReceive( e, ui ) {	gNodes = 'group=';/*	re=include after migration to jQueryUI 1.7	if ( minRowWidth( ui.element[0].id ) > pScreenMax ) {		console.debug('row too big');		$('#notify').text( 'The row is too full for another group' );		$(ui.sender).sortable('cancel');	} else {*/	var gbArr = $("#"+ui.item[0].parentNode.id+" .group_block");	console.debug(' groups sort receive ',gbArr);	for ( d=0; d < gbArr.length; d=d+1 ) {		console.debug(' groups sort receive ' + d + ' ', gbArr);		gNodes = gNodes + gbArr[d].id.match(/[n]?[0-9]+/) + '-';	}/*	for ( d=0; d<ui.item[0].parentNode.cells.length; d=d+1 ) {		gNodes = gNodes + ui.item[0].parentNode.cells[d].id.match(/[n]?[0-9]+/) + '-';	}*/	gNodes = gNodes.substring(0, gNodes.length-1);	gNodes = gNodes + '&send_row=' + ui.sender[0].id.match(/[n]?[0-9]+/) + '&';	gNodes = gNodes + 'rcv_row=' + ui.item[0].parentNode.id.match(/[n]?[0-9]+/) + '&';	gNodes = gNodes + 'group_id=' + ui.item[0].id.match(/[n]?[0-9]+/) + '';		console.debug( 'gnodes ', gNodes );	// initials cron flag	ranGroupReceive = 1;//	$('#notify').load('config/groups.php?AJAX=1&proc=updateReceive&' + gNodes, function() { refreshContent(); });//	alert( '2' );}/********************* UTILITIES ********************/function outputarray(element, index, array) {	$('#notify_d_this_page_errtxt').text( $('#notify_d_this_page_errtxt').text() +  "Element "+index+" contains the value "+element+"\n" );}function refreshContent() {	$('#links_groups_edit').load('groups_block_list.php');			//	$('#links_flat_list').load('links_flat_list.php');	$('#notify_d_last_page_errtxt').load('config/almanac.php?uid=<?php echo $_SESSION["user_id"]?>&limit=' + limit + '&AJAX=1');}			function combinedGroupSort() {	// future home of the procedure to record sort updates for a(n external) batch update process	execGroupSort = 0;}			function StopFollow() {	return false;}        function scrollTo( scrf ) {//	alert(scrf);	$("html,body").scrollTop( scrf );}function ClearUIs () {	if ( updateState == 1 ) {	// link UI		updateLink_UI_Clear();	}	if ( updateStateGroup == 1 ) {	// edit group name UI		$('#group_'+gg_id+'_name').html(gTitle);		updateStateGroup = 0;	}	if ( addStateGroup == 1 ) {		// add group UI		addGroup_UI();		addStateGroup = 0;	}	if ( updateStatePages == 1 ) {		// page UI	}}function getScroll () {	if ( window.pageYOffset > $("html,body").scrollTop() ) {		return window.pageYOffset;	} else {		return $("html,body").scrollTop() ;	}}function minRowWidthByCol ( row_id ) {	gbs = $("#"+row_id+" .group_block");	minRW = 0;	for (e=0; e<gbs.length; e++) {//		console.debug( e + ' of ' + gbs.length + ' ' + gbs.eq(e).attr('id') + ' is ' + minGroupWidthByCol( gbs.eq(e).attr('id') ));		minRW = minRW + minGroupWidthByCol( gbs.eq(e).attr('id') );	}//	console.debug('minRW ', minRW );	return minRW + 20;}function minGroupWidthByCol ( group_id ) {	gcs = $("#"+group_id+" .link_col");	minGW = gcs.length * 64;	return minGW + 12;}function minRowWidth ( row_id ) {	gbs = $("#"+row_id+" .group_block");	minRW = 0;//	console.debug( gbs.length );	for (d=0; d<gbs.length; d++) {//		console.debug( d + ' of ' + gbs.length + ' ' + gbs.eq(d).attr('id') );		minRW = minRW + minGroupWidth( gbs.eq(d).attr('id') );	}	return minRW + 20;}function minGroupWidth ( group_id ) {	gcs = $("#"+group_id+" .link_col");	minGW = 0;	for (i=0; i<gcs.length; i++) {		t=0;		lc = $("#"+gcs.eq(i).attr('id')+" .link_edit");		for (j=0; j<lc.length; j++) {			ll = minLinkWidth( lc.eq(j).attr('id') );			if ( t < ll ) { t = ll }		}		minGW = minGW + t + 15;//		console.debug( group_id + ' ' +  (t+15) );	}//	console.debug( group_id + ': ' + minGW );	return minGW + 12;}function minLinkWidth ( link_id ) {	return $("#"+link_id+' .link_text a').width() + 12;}/********************* KEY CONTROL ********************/$(document).keydown( function( e ) { 	switch (e.which) { 	case 27: // escape		ClearUIs();		document.onkeydown = old_f;		break;//	case 13: // enter	default:		// 	}});/********************* PERSISTANT ********************/function UpdateCron ( ){	if ( ranGroupReceive == 1 ) {//		console.debug( ranGroupUI.element[0].id + ': ' + (minRowWidthByCol( ranGroupUI.element[0].id ) - minGroupWidthByCol( ranGroupUI.item[0].id )) + ' + ' + ranGroupUI.item[0].id + ': ' + minGroupWidthByCol( ranGroupUI.item[0].id ) + ' < ' + pScreenMax  );		runGroupUpdate = '';		// check to row over-flow, and prevent update		if ( (minRowWidthByCol( ranGroupUI.element[0].id ) + minGroupWidthByCol( ranGroupUI.item[0].id )) < pScreenMax ) {			$('#notify_on').load('config/groups.php?AJAX=1&proc=updateReceive&' + gNodes, function() { refreshContent(); });		} else {			$('#notify').text( 'The row is too full for another group ' + (minRowWidthByCol( ranGroupUI.element[0].id )) + ':'+ranGroupUI.element[0].id+ ' ' + minGroupWidthByCol( ranGroupUI.item[0].id ) + ':' + ranGroupUI.item[0].id + ' ' + pScreenMax );			refreshContent();		}		ranGroupReceive = 0;	} else if ( runGroupUpdate != '' ) {//		console.debug('group update it: ', ranGroupUI);//		alert('dumped the update flag');		a = runGroupUpdate.split(','); 		doGroupSort( a[0], a[1] );		runGroupUpdate = '';	}	if ( ranLinkReceive == 1 ) {//		console.debug( ranGroupUI.element[0].id + ': ' + (minRowWidth( ranGroupUI.element[0].id ) - minGroupWidth( ranGroupUI.item[0].id )) + ' + ' + ranGroupUI.item[0].id + ': ' + minGroupWidth( ranGroupUI.item[0].id ) + ' < ' + pScreenMax  );		runLinkUpdate = '';		// check to row over-flow, and prevent update		if ( (minRowWidthByCol( ranLinkUI.element[0].id ) - minGroupWidthByCol( ranLinkUI.item[0].id )) < pScreenMax ) {			$('#notify_on').load('config/links.php?AJAX=1&proc=updateSort&col=' + col + '&page_id=' + page_id + '&group_id=' + gid + '&' + a, function() { 				refreshContent();			});		} else {			$('#notify').text( 'The row is too full to add this link' );			refreshContent();		}		ranLinkReceive = 0;	} else if ( runLinkUpdate != '' ) {		a = runLinkUpdate.split(',');		var gr = $("#group_"+a[1]);//		console.debug ("gr ", gr);		if ( $("#"+ranLinkUI.item[0].parentNode.id ).hasClass("link_col_t") ) {			new_col = 76;		} else {			new_col = 0;		}		//		console.debug('update it: ', ranLinkUI);//		console.debug( 'row: ' + minRowWidthByCol( gr.parent().attr("id") ) + '+'+new_col +' < ' + pScreenMax );		if ( (minRowWidthByCol( gr.parent().attr("id") )+new_col < pScreenMax ) ) {			doLinkSort( a[0], a[1], a[2] );		} else {			$('#notify').text( 'The row is too full to add this link. Your titles are already cropped! :)' );			refreshContent();		}		runLinkUpdate = '';	}}$(document).ready(function(){<?php 	// lastly, we echo these into place... 		echo $AJAX_link_sortables; 	echo $AJAX_group_sortables; 	echo $AJAX_group_originals;//	echo $AJAX_sizes;?>	var initCFEpos = $('#frame_pages').offset().top + $('#frame_pages').height();		$('#content_floater_edit').css( { position: 'absolute', top: (initCFEpos + getScroll()) + 'px' } );	menuYloc = $("#content_floater_edit").offset().top ;	menuXloc = $("#frame_banner").offset().left ;		$(window).scroll(function(){		var offset = initCFEpos + getScroll();		offset = offset + 'px';				$('#content_floater_edit').animate( { top:offset }, { duration:300, queue:false });	});		// sets up cron-like task	cron_task = setInterval ( "UpdateCron()", 500 );		$('.link_edit').click(function(e){e.stopPropagation();}); });//var initCFEpos = $(window).height() - $('#content_floater_edit').height() - 30;window.cl=function(b,c){if(document.images){var a=encodeURIComponent||escape;(new Image).src="scribe.php?link_id="+c+"&u="+a(b)}return true};//--></script><?php}	if ( ($_SESSION['DEBUG']=='1') and ( strstr($_SESSION['cur_page'], 'edit') ) ) { ?>	<script type="text/javascript"><!--	$('#notify_d_page_id').text("<?php echo $page_id;?> (from groups_block_list)");	$('#notify_d_this_page_errtxt').html( $('#notify_d_this_page_errtxt').html() +  "<?php echo $errtxt;?>" + "<br /><?php echo $page_id;?> (from groups_block_list)" );//--></script><?php	}?></div>