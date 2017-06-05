<?php

require_once('db.php');

// set the server string

// _SERVER["SCRIPT_NAME"] = '/~equinox/pageui/z.php'

$site_uri = 'http://'.$_SERVER["SERVER_NAME"].'/';

if (substr($_SERVER["SERVER_NAME"], 0, 3)=="127") {
	$site_path = '~equinox/pageui/';
} else {
	$site_path = '';
}

// set the home page
$home = "index2.php";

$crlf = Chr(13).Chr(10);

$unsorted_row = '100';


/************* Routing *****************/

function Leave( $msg = '', $json = '') {
	global $errtxt, $page, $site_uri, $site_path;
	
	// insert link history
	$sql = "INSERT INTO admin_DEBUG (user_id, errtxt, created_at) VALUES (".$_SESSION['user_id'].", '".ReturnSecureString($errtxt)."', '".date("Y/m/d H:m:s")."')";
	$result = mysql_query($sql);
	$errtxt .=  '<br />'.$sql;
	
	// only append if admin user
	if ($_SESSION['DEBUG'] == 1) {
		$_SESSION['errtxt'] = $errtxt;
	}
	
	$uri_append = '';
	if ( $json != '' ) {
		$uri_append = '?jsoncallback='.$json;
	}
		

	// determine next page	
	if ($page) { 
		$page = $site_uri.$site_path.$_REQUEST['page'];
	} else if ($_REQUEST['page']) { 
		$page = $site_uri.$site_path.$_REQUEST['page'];
	} else { 
		$page = $site_uri.$site_path."edit.php";
	}
	
	
	$_SESSION['msg'] = $msg;
	
	
	// redirect
	if ($_REQUEST['AJAX'] == 1) {
		header("Location: zephyr.php".$uri_append);	
	} else {	
//		echo 'test'.$_REQUEST['AJAX'] .'dbg: '.$_SESSION['DEBUG'] ;
		header("Location: ".$page.$uri_append);	
		exit;
	}

}

/**************  Security  **************/


function ReturnSecureString ( $str ) {
//	$str = str_replace ( "'", "''", $str );

//	$str = mysql_real_escape_string ( $str );
	
	if ( is_array($str) ) {
		for ( $i = 1; $i < count($str); $i++ )
		{
		    $str[$i] = mysql_real_escape_string( $str[$i] ); 
//		    $str[$i] = addcslashes(mysql_real_escape_string( $str[$i] ), "%_="); 
		}
		
	} else {
		$str = mysql_real_escape_string($str); 
//		$str = addcslashes(mysql_real_escape_string($str), "%_="); 
	}

	/// SEC place other security screening here.  probabl best to used stored procs in general.

// var_dump(filter_var('bob@example.com',ÊFILTER_VALIDATE_EMAIL));
// var_dump(filter_var('example.com',ÊFILTER_VALIDATE_URL,ÊFILTER_FLAG_SCHEME_REQUIRED));
	
	return $str;
}


function createKey($amount){
	$keyset = "abcdefghijklmABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$randkey = "";
	for ($i=0; $i<$amount; $i++)
		$randkey .= substr($keyset, rand(0,count($keyset)-1), 1);
	return $randkey;	
}

	
function FormatURL ( $URL ) {
	/// check to see if what precedes "://" isn't http, https, or ftp.  

// $urlregex_protocol = "^(http|https|ftp)\:\/\/";

//$urlregex_domainÊ=Ê"[a-z0-9+\\$_-]+(\\.[a-z0-9\$_-]+)+";ÊÊ//Êhttp://x.xÊ=Êminimum 

/*
//ÊSCHEME 
/// also include "mailto:"
$urlregexÊ=Ê"^(https?|ftp)\:\/\/"; 

//ÊUSERÊANDÊPASSÊ(optional) 
$urlregexÊ.=Ê"([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; 

//ÊHOSTNAMEÊORÊIP 
$urlregexÊ.=Ê"[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*";ÊÊ//Êhttp://xÊ=ÊallowedÊ(ex.Êhttp://localhost,Êhttp://routerlogin) 
//$urlregexÊ.=Ê"[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)+";ÊÊ//Êhttp://x.xÊ=Êminimum 
//$urlregexÊ.=Ê"([a-z0-9+\$_-]+\.)*[a-z0-9+\$_-]{2,3}";ÊÊ//Êhttp://x.xx(x)Ê=Êminimum 
//useÊonlyÊoneÊofÊtheÊabove 

//ÊPORTÊ(optional) 
$urlregexÊ.=Ê"(\:[0-9]{2,5})?"; 
//ÊPATHÊÊ(optional) 
$urlregexÊ.=Ê"(\/([a-z0-9+\$_-]\.?)+)*\/?"; 
//ÊGETÊQueryÊ(optional) 
$urlregexÊ.=Ê"(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?"; 
//ÊANCHORÊ(optional) 
$urlregexÊ.=Ê"(#[a-z_.-][a-z0-9+\$_.-]*)?\$"; 

//Êcheck 
 ifÊ(eregi($urlregex_protocol,Ê$URL))Ê{echoÊ"good protocol";}ÊelseÊ{echoÊ"bad";}Ê 
 ifÊ(eregi($urlregex_domain,Ê$URL))Ê{echoÊ"good domain";}ÊelseÊ{echoÊ"bad";}Ê 
*/

}

?>