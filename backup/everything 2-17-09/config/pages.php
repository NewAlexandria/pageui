<?php
session_start();

include_once('keys.php');
include_once('db.php');

require_once('pages_lib.php');

$DEBUG = 0;

$page_id = $_SESSION['page_id'];
$user_id = $_SESSION['user_id'];
// check duplicate page name
if ( $_REQUEST['name'] == "" ) {
	$name = "";
} else {
	$name = ReturnSecureString( $_REQUEST['name'] );
}


/// Ideally, each of the functions called here would instead pass data to SQL stored procedures.  These would execute more quickly, prevent poisoned input, and scale easier.  The SQL should return multiple variables for errtxt and success_status, and may need to bundle these as JSON / etc to accomplish it via the MySQL driver.  They were coded here in PHP for rapid prototyping

$errtxt .= '<br />proc: '.$_REQUEST['proc'];
$errtxt .= '<br />'.$_SERVER['REQUEST_URI'];

switch ( $_REQUEST['proc'] )
{
    case "add":
        AddPages($user_id, $page_id, $name);
		Leave("New page created");
        break;
    case "update":
        UpdatePage();
		Leave("Page name updated");
        break;
    case "updateOrder":
        UpdatePageOrder();
        Leave("Page positions shifted");
        break;
    case "delete":
        DeletePage( ReturnSecureString($_REQUEST['page_id']) );
		Leave("Page delete");
        break;
    default:
    	$errtxt .= '<br/>no input';
	Leave("~ Input Required ~");
        break;
}

?>