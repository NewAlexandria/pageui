<?phpsession_start();/// SEC this really really really should have a confirmation UI, consisting of a form input for a text-key that is matched against a hash in their account (like how the maintain cookie is done with createKey).if ( $_REQUEST['1']  == '' ) {	$_SESSION['DEBUG'] = '';} else {	$_SESSION['DEBUG'] = $_REQUEST['1'];	$one = '1=1&';}if ( $_REQUEST['wipe_maintain'] == '1' ) {	setcookie('maintain_key', '0', time()-3600, '/' );	setcookie('maintain', '0', time()-3600, '/' );		echo 'wipe maintain done<br/>';}				?><a href="tinsel.php?<?php echo $one ?>wipe_maintain=1">Wipe my cookies</a>