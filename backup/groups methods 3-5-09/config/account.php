<?phpsession_start();require_once('keys.php');require_once('db.php');// screen for whether they are logged inif ($_SESSION['user_id']=="") {	if ($_COOKIE['maintain'] != "") {		header("Location: config/login.php?x=login&user_id=".$_COOKIE['maintain']);	} else {		header("Location: ".$home);			exit;	}}$sql = "SELECT * FROM Users WHERE id = ".ReturnSecureString($_SESSION['user_id']);$errtxt .= '<br />1'.$sql;$result = mysql_query ( $sql );$r = mysql_fetch_assoc ( $result );$email = $r['email'];$name_first = $r['name_first'];$name_last = $r['name_last'];if ( $r['tutorials'] == 'y' ) {	$tutorials = 'checked="checked"';}if ( $r['homepage_set'] == 'y' ) {	$homepage_set = 'checked="checked"';}if ( $r['bookmarker_set'] == 'y' ) {	$bookmarker_set = 'checked="checked"';}$errtxt .= '<br/>tutorials: '. $r['tutorials'];$errtxt .= '<br/>homepage: '. $r['homepage_set'];$errtxt .= '<br/>bookmarker: '. $r['bookmarker_set'];?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>	<title>Your PageUI Links</title>	<meta name="generator" content="BBEdit 8.7" />	<link rel="stylesheet" rev="stylesheet" href="../beta.css" />	<link rel="stylesheet" rev="stylesheet" href="../theme/ui.all.css" />	  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" type="text/javascript"></script>	  <script type="text/javascript" src="../jquery-ui-personalized-1.6rc6.min.js"></script>	  <style type="text/css" title="text/css">	  /* <![CDATA[ */		#tabs		{				font-size: 12px;			margin: 0 20px 20px 20px;		}				#tabs div {					}		#accordion		{			font-size: 12px;			margin: 0 20px 20px 20px;		}				#accordion div {			max-height: 800px; overflow: scroll;		}	  /* ]]> */	  </style>	  	  <script>	  $(document).ready(function(){		$('#tabs').tabs();		$('#accordion').accordion({				header: "h3"			});	  });	  </script></head><body><table style=" width: 100%;" cellpadding="0" cellspacing="0" border="0"><tr>	<td valign="top" id="left_col">		<div id="frame_header">		<?php require_once('../header.php'); ?>		</div>		<?php if ( $_SESSION['show_home'] == 'on' ) { include_once('../set_homepage.php'); } ?>		<?php if ( $_SESSION['show_dropper'] == 'on' ) { include_once('../set_bookmarker.php'); } ?>				<div id="frame_pages">			<div id="login">				<?php  require_once('login_front.php');  ?>			</div>				<div class="search">				<?php  require_once('../search_cell.php');  ?>			</div>				<div id="frame_pages_include">			<?php require_once('../pages.php'); ?>			</div>		</div><!-- 		<div id="content_floater">			<a href="edit.php">Edit</a>		</div> -->	</td>	<td id="frame_content" >			<div id="tabs">			<ul>				<li><a href="#fragment-1"><span>Account</span></a></li>				<li><a href="#fragment-2"><span>Bookmarking Tool</span></a></li><!-- 				<li><a href="#fragment-3"><span>Homepage Setup</span></a></li> -->			</ul>						<div id="fragment-1">				More Soon.				<form action="accounts.php" method="POST" id="account_update">					<div class="inputs">						<div>Account Information</div>						<table border="0" cellspacing="0" cellpadding="0">							<tr><td>Username:</td><td><input name="username" id="email" type="text" size="25" maxlength="100" tabindex="1" value="<?php echo $email; ?>" /></td></tr>							<tr><td>Password:</td><td><input name="password" id="password" type="password" size="25" maxlength="40" tabindex="2" /></td></tr>							<tr><td>Password:<br />(confirm)</td><td valign="top"><input name="password_confirm" id="password_confirm" type="password" size="25" maxlength="40" tabindex="3" /></td></tr>							<tr><td>Email:</td><td><input name="email" id="email" type="text" size="25" maxlength="100" tabindex="4" value="<?php echo $email; ?>" /></td></tr>						</table>					</div>					<div class="inputs">						<div style="width:100px">Preferences</div>						<table border="0" cellspacing="0" cellpadding="0">							<tr><td>Show Tutorials:</td><td><input name="tutorials" id="tutorials" type="checkbox" tabindex="5" <?php echo $tutorials ?> /></td></tr>							<tr><td>Show Homepage Link:</td><td><input name="homepage_set" id="homepage_set" type="checkbox" tabindex="6" <?php echo $homepage_set ?> /></td></tr>							<tr><td>Show Bookmark Tool Link:</td><td><input name="bookmarker_set" id="bookmarker_set" type="checkbox" tabindex="7" <?php echo $bookmarker_set ?> /></td></tr>						</table>					</div>					<!-- 					<div class="inputs">						<table border="0" cellspacing="0" cellpadding="0">							<div>Personal Information</div>							<tr><td>First Name:</td><td><input name="name_first" id="name_first" type="text" size="25" maxlength="45" tabindex="1" value="<?php echo $name_first; ?>" /></td></tr>							<tr><td>Last Name:</td><td><input name="name_last" id="name_last" type="text" size="25" maxlength="45" tabindex="1" value="<?php echo $name_last; ?>" /></td></tr>						</table>					</div> -->					<button type="submit" id="Update Account">Update Account</button>					<input name="proc" type="hidden" value="update" />					<input name="page" type="hidden" value="config/account.php" />				</form>								<div style="margin: 20px 0 0 0;"><a href="accountclose.php">Close My Account</a></div>			</div>						<div id="fragment-2">				<p>Make your life easier with the PageUI bookmark tool!  Drag this link to your browser's link bar: </p> 				<a href="javascript:(function(){var%20pageui_s=document.createElement('script');pageui_s.setAttribute('src','http://www.pageui.com/jquery-1.2.5.pack.js');document.getElementsByTagName('head')[0].appendChild(pageui_s);var%20pageui_b=document.getElementsByTagName('body')[0];%20var%20pageui_s=document.createElement('script');pageui_s.charset='UTF-8';pageui_s.src='http://www.pageui.com/test/link_dropper8h.js';pageui_b.appendChild(pageui_s);})();" style="background-color:#eef; border:2px groove #55a; padding: 2px 5px 2px 5px; margin-top:5px; color:black; font-family:sans-serif; font-size:10pt; text-decoration:none;">Add to PageUI</a>								<!-- javascript:(function(){var%20pageui_s=document.createElement('script');pageui_s.setAttribute('src','http://www.pageui.com/jquery-1.2.5.pack.js');document.getElementsByTagName('head')[0].appendChild(pageui_s);var%20pageui_b=document.getElementsByTagName('body')[0];%20var%20pageui_s=document.createElement('script');pageui_s.charset='UTF-8';pageui_s.src='http://www.pageui.com/test/link_dropper8h.js';pageui_b.appendChild(pageui_s);})(); -->				<p>IE Users: Right click the above link and select "Add to Favorites" or "Bookmark this Link" (depending upon your current browser).				</p>			</div>			<!-- 			<div id="fragment-3" style="height: 500px;">				<p style="margin: 4px 0 10px 10px; font-weight: bold;">Making your PageUI links your homepage is easy!</p>				<div id="accordion" style="height: 500px;">					<h3><a href="#">Internet Explorer</a></h3>					<div>						You should just be able to <a href="javascript:this.style.behavior='url(#default#homepage)';this.setHomePage('http://www.pageui.com/home.php');">click here to make PageUI your homepage</a>						<p>Manual Instructions to Change Your Home Page</p>						<p>1.						Go to the Web page you want to make your home page.</p>						<p>2.						On the Internet Explorer Tools menu, click Internet Options.</p>						<p>3.						In the Internet Options box, on the General tab, enter the link http://www.pageui.com/home.php  (If you navigate to the home page itself then you may click the Use Current button.)</p>												<img src="../images/homepage/HomePage1.gif" width="315" height="191" />												<p>4.						Click OK.								</p>					</div>					<h3><a href="#">Firefox</a></h3>					<div>						<p>Click on TOOLS on the menu bar at the top of the Firefox screen, then click on OPTIONS						see fig 1.1 below for a screenshot: </p>												<img src="../images/homepage/tools-options.gif" width="433" height="297" /> 												<p>After clicking options you will see the Firefox Options dialogue box as shown below in fig 1.2</p>												<img src="../images/homepage/change-homepage.gif" width="479" height="428" />						 						<p>Type the address of the webpage that you would like to use as your new homepage into the box provided (see Fig 1.2 above) and click the OK button. 												If you do this from your PageUI home page just click the Use Current Page button then simply click on the OK button.   Otherwise, enter http://www.pageui.com/home.php								</p>					</div>					<h3><a href="#">Safari for PC</a></h3>					<div>						<p>Change Default Home Page in Safari for Windows</p>						<img src="../images/homepage/wer_thumb.png" width="300" height="375" />						<p>Click on Edit then choose Preferences.  Here you can change the default homepage from Apple's default. Just clear out the address in the Home page box.</p>						<img src="../images/homepage/sa_thumb1.png" width="509" height="499" />						<p>Enter the page http://www.pageui.com/home.php </p>						<img src="../images/homepage/confirm_thumb.png" width="500" height="188" />											</div>										<h3><a href="#">Safari for Mac</a></h3>					<div>						<p>Click on Safari in your Safari menu, located at the top of your screen. When the drop-down menu appears, choose Preferences.</p>						<img src="../images/homepage/safaritabs2.jpg" width="400" height="326" />						<p>Select General from the Preferences menu, which is now overlaying your browser window. Once General is selected, you will notice a section labeled Home Page in the main window of the Preferences dialog.</p>						<p>Directly to the right of the "Home Page" label is an edit field containing your current home page URL. Change this field to read: http://www.pageui.com/home.php.</p>						<p>Directly below this edit field you will see a button labeled Set to Current Page. If you prefer, you may navigate to your PageUI home page, then click this button to make it your home page.</p>						<p>Once you have completed your changes, close the Safari Preferences dialog by clicking the red circle/x located in the top left hand corner of the box.</p>						<img src="../images/homepage/safhomepage3.jpg" width="250" height="238" />					</div>				</div>			</div>			 -->		</div>	</td>	<td id="frame_banner">			<?php require_once('../banner.php'); ?>	</td></tr><tr>	<td id="frame_footer" colspan="3">	<?php require_once('../footer.php'); ?>	</td></tr></table></body></html>