<?php
	$db = '';
	function openDB() {
		global $db;
		//connect to DB.  131074 is the konstant equivalent for CLIENT_MULTI_STATEMENTS, CLIENT_MULTI_RESULTS.  see: http://www.artfulsoftware.com/infotree/tip.php?id=802
		$db = mysql_connect ("localhost", "dominavi", "p@G3uiAT", false, 131074) or die ('We are currently experiencing a downtime.  Please feel free to inquire as to when the system will become available again. <br/><br/>' . mysql_error());
	
	
		if (substr($_SERVER["SERVER_NAME"], 0, 3)=="127") {
			mysql_select_db ("dominavi_pageui_beta", $db);  // make active
		} else {
			mysql_select_db ("dominavi_pageui_beta", $db);  // make active
		}	
	}
	
	function refreshDB() {
		global $db;
		mysql_close ( $db );
		openDB();
	}
	
	openDB();
?>