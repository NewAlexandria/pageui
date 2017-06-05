<?php
// session_start();

// setcookie("user", 'sss', time()-3600);

// include_once('keys.php');
// include_once('db.php');

if ( $_REQUEST['x'] == '' ) {

	$iDaystoExpire = 60;
	
	
	echo date('Y-m-d', mktime(0, 0, 0, date("m"), date("d")+$iDaystoExpire, date("Y")) );


//ÊcreateÊaÊnewÊcURLÊresource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, 'http://www.pageui.com/test/link_dropper.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);

// grabÊURLÊandÊpassÊitÊtoÊtheÊbrowser
$output = curl_exec($ch);

//ÊcloseÊcURLÊresource,ÊandÊfreeÊupÊsystemÊresources
curl_close($ch);




 $arr = array ('src'=>$output);
 $json_page = json_encode ( $arr );

?>

<?php echo $json_page; ?>

<br /><br />
<a href="_test.php?x=set&val=1">set</a><br />
<a href="_test.php?x=set">set</a><br />
<a href="_test.php?x=set">set</a><br />

session 'page_Id': <?php echo $_COOKIE['page_id']; ?><br/>
session 'x': 
<?php echo $_SESSION['val'];

} else if ( $_REQUEST['x'] == 'set' ) {
	$_SESSION['val'] = $_REQUEST['val'];
} else if ( $_REQUEST['x'] == 'sets' ) {
	
} else if ( $_REQUEST['x'] == 'setss' ) {
	
}