<?php
session_start();

// setcookie("user", 'sss', time()-3600);



include('keys.php');
include('db.php');

$iDaystoExpire = 60;


echo date('Y-m-d', mktime(0, 0, 0, date("m"), date("d")+$iDaystoExpire, date("Y")) );



// Print an individual cookie
// echo '<br />'.$_COOKIE['user_id'].'<br />';
// echo $_COOKIE['user'];
// echo $HTTP_COOKIE_VARS["user"];

// Another way to debug/test is to view all cookies
// var_dump($_COOKIE);
// echo $_COOKIE['maintain'].'<br/>';

// var_dump($_SESSION);
// echo 'sa'.$_SESSION['user_id'].'asd';
// echo createKey(100);

$keyset = "abcdefghijklmABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for ($i=0; $i<25; $i++) {
// 		echo substr($keyset, rand(0, count($keyset)-1), 1).'<br/>';
}

//include('jsonrpc/jsonrpc.inc');
//include('jsonrpc/json_extension_api.inc');

/*

	$sql = "SELECT * FROM Users";
	$sql = "SELECT L.*, G.Title FROM Links as L INNER JOIN Groups as G ON L.group_id = G.id INNER JOIN GroupsPages as GP ON G.id = GP.group_id WHERE L.user_id = ".$_SESSION['user_id'];
	$result = mysql_query($sql);

	$res = mysql_fetch_assoc($result);
	$j = json_encode($res);
	$k = json_decode($j);

echo '<br/>session user id:<br/>'.$_SESSION['user_id'];


echo '<br/>mysql variable:<br/>';
var_dump($res);
echo '<br/>username var:<br/>';
echo $res['username'];
echo '<br/>json encode:<br/>';
echo $j;
echo '<br/>json decode:<br/>';
var_dump($k);

echo '<br/><br/><br/>';

$login = fopen('http://127.0.0.1/~equinox/pageui/config/login.php?x=show', 'r');
echo stream_get_contents($login);

echo '<br/><br/><br/>';

$sql = "SELECT G.id, G.Row, G.Col FROM Groups G INNER JOIN Links L ON G.id = L.group_id WHERE G.Row < 100 AND L.user_id = ".$_SESSION['user_id'].' ORDER BY G.Row, G.Col';
echo '<br/>: '.$sql;

$result = mysql_query($sql);
echo '<br />num row: '.mysql_num_rows($result);

if (mysql_num_rows($result) > 0) {
	mysql_data_seek($result, (mysql_num_rows($result) - 1) );
	$resGroupMax = mysql_fetch_assoc($result);
	echo  '<br />id: '.$resGroupMax['id'];
	echo  '<br />row: '.$resGroupMax['Row'];
	echo  '<br />col: '.$resGroupMax['Col'];

}


*/

?>