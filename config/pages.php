<?php
session_start();

include_once('keys.php');
include_once('db.php');

require_once('pages_lib.php');

$DEBUG = 0;

$errtxt .= '<br />sess user: '.$_SESSION['user_id'];
$errtxt .= '<br />sent user: '.$_REQUEST['user_id'];
/* 
if ( $_REQUEST['user_id'] ) {
	$user_id = ReturnSecureString($_REQUEST['user_id']);
} else {
	$user_id = ReturnSecureString($_SESSION['user_id']);
}*/
$errtxt .= '<br />safe user: '.$user_id;

$user_id = ReturnSecureString($_SESSION['user_id']);

$page_id = ReturnSecureString($_GET['page_id']);
	
// check duplicate page name
if ( $_REQUEST['name'] == "" ) {
	$name = "";
} else {
	$name = ReturnSecureString( $_REQUEST['name'] );
}

if ( $_REQUEST['page'] ) {
	$page = $_REQUEST['page'];
}


/// Ideally, each of the functions called here would instead pass data to SQL stored procedures.  These would execute more quickly, prevent poisoned input, and scale easier.  The SQL should return multiple variables for errtxt and success_status, and may need to bundle these as JSON / etc to accomplish it via the MySQL driver.  They were coded here in PHP for rapid prototyping

$errtxt .= '<br />proc: '.$_REQUEST['proc'];
$errtxt .= '<br />o pageid: '.$_GET['page_id'];
$errtxt .= '<br />pageid: '.$page_id;
$errtxt .= '<br />name: '.$_REQUEST['name'];
$errtxt .= '<br />page name: '.$_REQUEST['page'];

$errtxt .= '<br />URI: '.$_SERVER["REQUEST_URI"];

switch ( $_REQUEST['proc'] )
{
    case "add":
        AddPages($user_id, $name, $page);
		Leave("New page created", $page);
        break;
    case "update":
        UpdatePage($page_id, $user_id, $name, $page);
		Leave("Page name updated", $page);
        break;
    case "updateOrder":
        UpdatePageOrder();
        Leave("Page positions shifted", $page);
        break;
    case "delete":
        DeletePage( $page_id, $user_id );
		Leave("Page delete", $page);
        break;
    default:
    	$errtxt .= '<br/>no input';
	Leave("~ Input Required ~", $page);
        break;
}

?>