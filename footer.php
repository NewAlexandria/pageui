<div class="footer"	>	<span>~ Aloha ~</span></div><?php//echo "$GLOBALS['HTTP_SESSION_VARS']['DEBUG']: ".$GLOBALS['HTTP_SESSION_VARS']['DEBUG']." $GLOBALS['HTTP_SESSION_VARS']['errtxt']: ".$GLOBALS['HTTP_SESSION_VARS']['errtxt'];if ($_SESSION['DEBUG']=='1') { /// BAD session debug should not be done without server-side validation?>	<div class="notify_d" style=" max-width:1200px">	<div style="float: left;">user_id: <span id="notify_d_user_id"><?php echo $_SESSION['user_id'] ?></span></div>	<div style="text-align: center;">page_id: <span id="notify_d_page_id"><?php echo $_COOKIE['page_id'].' ('.$_SESSION['page_id'].')'; ?></span></div>	<div style="float: right;">debug: <?php echo $_SESSION['DEBUG'] ?></div>	<div>maintain: <?php echo $_COOKIE['maintain']; ?></div>	<div style="clear: both; margin-bottom: 5px; background-color: #eee; padding: 3px; overflow: scroll; max-width:1000px">last page errtxt: <div id="notify_d_last_page_errtxt"><?php echo $err;  ?></div></div>	<div>this page errtxt: <span id="notify_d_this_page_errtxt"><?php echo $errtxt;  ?></span></div>	<a href="<?php echo $site_uri.$site_path; ?>z.php">php info</a>	</div><?php 		$_SESSION['errtxt'] = '';} ?>