<?phpsession_start();// begin buffering output of the page.  later we'll handle where to output.ob_start();// "zephyr" is a document for relaying the most recent / current system messages and notifications.  //The default return is the message stored in the session if ( $_REQUEST['channel'] == "" ) {	echo $_SESSION['msg'];} elseif ( $_REQUEST['channel'] == "msg" ) {// otherwise, zephyr will pull from the database table of UserMessages, which raises awareness about their system usage, and other communications.} // originally, zephyr's creation serves to relay the most recent / current errtxt stored in the session.  It works in tandem with other AJAX calls to deliver errtxt generated from recent UI operations back to the UI's debug elementsif ( $_SESSION['DEBUG'] == 1 ) {	if ( $_REQUEST['limit'] == '' ) {		$limit = '1,1';	} else { 		$limit = $_REQUEST['limit'];	}?><script type="text/javascript"><!--//$('#notify_d_last_page_errtxt').load('almanac.php?uid=<?php echo $_SESSION["user_id"]?>&limit=<?php echo $limit?>&AJAX=1');//$('#notify_d_last_page_errtxt').html( '<BR/><?php echo $_SESSION["errtxt"] ?>');//--></script><?php}// now that we have the full page, flush it to text or build the JSON and respond with it.if ( $_REQUEST['jsoncallback'] ) {	$arr = array( 'bigpage'=> ob_get_contents() );	$json_page = json_encode ( $arr );	// now that it's stored, dump everything generated	ob_clean();		header('Cache-Control: no-cache, must-revalidate');	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');	header('Content-type: application/x-javascript');	// print the constructed JSON varbiable to the output buffer	echo $_REQUEST['jsoncallback']."(".$json_page.")";		// push it all to the client	ob_end_flush();	} else {	// or, just push it all to the client	ob_end_flush();	}?>