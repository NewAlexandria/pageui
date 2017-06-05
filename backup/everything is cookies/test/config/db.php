<?php
	//connect to DB 
	$db = mysql_connect ("localhost", "dominavi", "p@G3uiAT") or die ('We are currently experiencing a downtime.  Please feel free to inquire as to when the system will become available again. <br/><br/>' . mysql_error());


	if (substr($_SERVER["SERVER_NAME"], 0, 3)=="127") {
		mysql_select_db ("dominavi_pageui_beta", $db);  // make active
	} else {
		mysql_select_db ("dominavi_pageui_beta", $db);  // make active
	}	

?>