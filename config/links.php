<?php
session_start();

include_once('keys.php');
include_once('db.php');

require_once('links_lib.php');

$errtxt = ''; 

$proc = $_REQUEST['proc'];
$errtxt .= '<br />proc: '.$proc;

$DEBUG = 0;

/// check for DB attacks (including form field length assumptions, and log

$link_id = ReturnSecureString($_REQUEST['link_id']);
$URL = ReturnSecureString($_REQUEST['URL']);
$group_id = ReturnSecureString($_REQUEST['group_id']);
$title = ReturnSecureString($_REQUEST['title']);
$col = ReturnSecureString($_REQUEST['col']);
$row = ReturnSecureString($_REQUEST['row']);
$new_group = ReturnSecureString($_REQUEST['new_group']);
if ( $_GET['page_id'] ) {
	$page_id = ReturnSecureString($_GET['page_id']);
} else {
	$page_id = ReturnSecureString($_SESSION['page_id']);
}
$user_id = ReturnSecureString($_SESSION['user_id']);
$errtxt .= '<br/>page_id: '.$page_id.' sess page_id: '.$_SESSION['page_id'].' get page_id: '.$_GET['page_id'];

if ( $DEBUG == 1 ) {
	$errtxt .= '<br />link_id: '.$link_id;
	$errtxt .= '<br />URL: '.$URL;
	$errtxt .= '<br />group_id: '.$group_id;
	$errtxt .= '<br />title: '.$title;
	$errtxt .= '<br />col: '.$col;
	$errtxt .= '<br />row: '.$row;
	$errtxt .= '<br />new group: '.$new_group;
}

$errtxt .= '<br />URI: '.$_SERVER["REQUEST_URI"];
/// SEC check that the user seeks to update / delete only links belonging to them


/// Ideally, each of the functions called here would instead pass data to SQL stored procedures.  These would execute more quickly, prevent poisoned input, and scale easier.  The SQL should return multiple variables for errtxt and success_status, and may need to bundle these as JSON / etc to accomplish it via the MySQL driver.  They were coded here in PHP for rapid prototyping

/// All the proces should take all their used data as inputs, and should not assume global scope variables.  It's insecure, and more breakable.  Some as of writing this note.
switch ( $_REQUEST['proc'] )
{
    case "add":
        // validate link structure (http:// and whole URI format), variable presence
//		$em = parse_url ( $email, PHP_URL_SCHEME );
		$scheme_end = strpos($URL, "://");
		if ( $scheme_end == 0 ) {
			$URL = "http://".$URL;
			$em = 'http';
		} else {
	    	$em = substr ( $URL, 0, $scheme_end );
	    }

        if ( $title == '' ) {
        	/// make up a title and move on without missing the link_add
        	Leave("You must give the link a title.", $page);
        	exit;
        } else if ( $URL == '' ) {
        	Leave("Links need their most essential component: the URL!", $page);
        	exit;
        } else if ( !preg_match("/(https?|ftps?|itms|gopher|telnet|nntp)+/",$em) ) {
        	Leave("The URL looks invalid, check the beginning looks like http:// or another web protocol.", $page);
        	exit;
        	
        	// the above condition checks the presence of a scheme in the URI.
        	
			// this looks for scheme and web address 
			// "/^([[:alpha:]]+:\/\/{1})((\w+\.){1,})\w{2,}$/i"

			// This looks for the whole enchilada.  PHP also has parse_url() "/^((((https?|ftps?|itms|gopher|telnet|nntp)://)|(mailto:|news:))(%[0-9A-Fa-f]{2}|[-()_.!~*';/?:@&=+$,A-Za-z0-9])+)([).!';/?:,]blank:)?$/";
		
        } else if ( ($group_id == '/new/') & ($new_group == '') ) {
        	/// add to unsorted instead, and notify
        	Leave("You must specify a group name.", $page);
        	exit;
        }


		/// check duplicate link
	
		$errtxt .= '<br/>posted group id: '.$group_id.'<br/>';	
			
		LinkAdd_Single ($link_id, $page_id, $user_id, $URL, $title, $new_group, $group_id, $row, $col);

		Leave("Another node in the web...", $page, $_REQUEST['jsoncallback']);
        break;
    case "update":
        LinkUpdate_Single ($user_id, $page_id, $link_id, $URL, $title, $group_id, $row, $col, $new_group ) ;
		Leave("Link information updated", $page);
        break;
    case "updateSort":
        UpdateLinkSort( $user_id, $_REQUEST['link'], $col, $group_id, $page_id );
        Leave("Link order shifted", $page);
        break;
    case "delete":
        LinkDelete ($link_id, ReturnSecureString($_SESSION['user_id']) ); 
		Leave("Link removed");
        break;
    default:
    	$errtxt .= '<br/>no input';
        Leave("~ Input Required ~", $page);
        break;
}


?>
