<?php
session_start();

require_once('../config/keys.php');

// screen for whether they are logged in
if ($_SESSION['user_id']=="") {

	if ($_COOKIE['maintain'] != "") {
		header("Location: config/login.php?x=login&user_id=".$_COOKIE['maintain']);
	} else {
		header("Location: ".$home);	
		exit;
	}
}

require_once('../config/db.php');
$username = $_SESSION['username'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>PageUI User's Guide</title>
	<meta name="generator" content="BBEdit 9.0" />
	<link rel="stylesheet" rev="stylesheet" href="../beta.css" />
</head>
<body>

<script type="text/javascript">
<!-- 

-->
</script>

<table style=" width: 100%;" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td id="frame_header">
	<?php require_once('../header.php'); ?>
	</td>

	<td id="frame_content" rowspan="2">
		We'll hook you up, soon.  Until then, email <a href="mailto:zak@pageui.com">zak@pageui.com</a>
	
	</td>

	<td id="frame_banner" rowspan="2">
		
	<?php require_once('../banner.php'); ?>
	</td>
</tr>
<tr>
	<td id="frame_pages" >
		<div id="login">
			<?php  require_once('../config/login_front.php');  ?>
		</div>

		<div class="search">
			<?php  require_once('../search_cell.php');  ?>
		</div>

		<div id="frame_pages_include">
		<?php require_once('../pages.php'); ?>
		</div>
	</td>
</tr>
<tr>
	<td id="frame_footer" colspan="3">
	<?php require_once('../footer.php'); ?>
	</td>
</tr>
</table>


</body>
</html>
