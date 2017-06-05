<?php
session_start();

// retain the debug information in a var; for printing in the footer, and so that var can be built during the execution of this page

if ($_SESSION['DEBUG']==1) { 
/// BAD session debug should not be done without server-side validation
	$err = $_SESSION['errtxt'];
	$_SESSION['errtxt'] = '';
} 

/*
// screen for whether they are logged in
if ($_SESSION['user_id']) {
	header("Location: home.php"); //.$home);	
	exit;
}
if ($_COOKIE['maintain'] != "") {
	header("Location: config/login.php?x=login&user_id=".$_COOKIE['maintain']);
}
*/
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>PageUI - Your Bookmarks, made intelligent</title>
	<meta name="generator" content="BBEdit 9.0" />
	<style type="text/css" title="text/css">
/* <![CDATA[ */
	p { margin: 0 0 20px 0;}
	.features
	{
		margin: 40px 0 80px 20px;
		padding: 0;
	}
	.feature
	{
		font-family: Lucida Grande, sans-serif;
		list-style-type: none;
		margin: 0 0 20px 0;
	}
	.feature h2
	{
		font-size: 12px;
		font-weight: bold;
		margin: 0;
		color: #222;
	}
	.feature .text
	{
		margin: 7px 10px 5px 5px;
		color: #666;
		font-size: 12px;
		line-height: 18px;
/*		background-color: #D6E1C7;*/
	}
/* ]]> */
	</style>
</head>
<body style="padding: 0px; margin: 0px; background-color: #403B33;">
<div style="background-color: #403B33; width: 100%; text-align: left; margin: 0; padding: 20px 0 0 0">
	<div style="background-color: #EDEBE6; float: right; width: 33%; margin: 0px 0 0 0; line-height: 1.1em;"><!-- F3BD64 -->
		<div style="background-color: #F3BD64; padding: 11px 11px 11px 17px;" onclick="location.href='config/login_front.php?new=y'"><a href="config/login_front.php?new=y" style="color: #403B33;text-decoration: none;">Already Have an Account? &nbsp&nbsp&nbsp Click Here</a></div>
		
		<ul class="features">
			<li class="feature">
				<h2>Simple, Intuitive Organization.</h2>
				<div class="text">Sorting links into groups is as simple as drag-and-drop.  Use different pages for work, classes, personal projects, home, and more.</div>
			</li>
			<li class="feature">
				<h2>Save bookmarks from anywhere.</h2>
				<div class="text">Add our bookmarklet to your browser and then save your favorite sites next time you visit them.</div>
			</li>
			<li class="feature">
				<h2>Search your bookmarks first.</h2>
				<div class="text">PageUI Search lets you filter google search results by your bookmarks.  See first the information on the most important pages, before you look broadly across the web.</div>
			</li>
			<li class="feature">
				<h2>Stop typing in links.</h2>
				<div class="text">Bookmarks used to be broken, and so it was easier to manually type in your favorite links.  Set the PageUI home as your start page and get to your favorite sites as easy as opening a new window.  See, we fixed it.</div>
			</li>
		</ul>
	</div>

	<div style="width: 60%; margin: 0px 10px 0 20px; background-color: #EDEBE6; padding: 20px;">
		<div style="margin: 0 0 20px 0; padding: 0 0 0 10px; text-align: left; width: 100%; font-size: 20px; font-family: sans-serif; font-weight: bold;">Welcome to "Page User Interface" <i>Beta</i></div>
		
		<img src="images/snap360.png" width="360" height="241" style="float: left; margin: 0 20px 20px 0;" />
		
		<div style="background-color: #94C7B6; padding: 10px; margin: 20px; font-size: 16pt;">Your Bookmarks Were Broken. <br/>We figured it out.  Enjoy.</div>
		<div style="padding: 15px 30px 0 10px; line-height: 22px; font-family: sans-serif;">
			<p>Long ago, way back when your choice was Netscape, web bookmarks were invented.  Then we started really using them, we all realized there were problems...</p>
			<p>They're a pain to organize, they aren't very accessible, and you always need to decide if it's worth it to go hunting <i>in there.</i></p>
			<p>We feel that the most effective way to work is <b>spatially</b>.  Things get put on the <i>top</i> shelf, or the <i>bottom</i>.  It's on <i>this side</i> of your desk, or <i>that side</i>.  Your toothbrush is <i>next to</i> the sink.  Icons may clutter the desktop, but you know where they all are.</p>
			<p>We've taken all the familiar things about drag-and-drop and integrated them into simple way to organize how you use the internet.  </p>
			<p style="font-size: 1.05em; text-align: right; font-weight: bold;">Bookmarks work again - you'll love it.</p>
		
		</div>
		
		<p style="text-align: center; line-height: 20px; background-color: #D3643B; padding: 15px; font-family: Lucida Grande, sans-serif; font-size: 18px; color: #D6E1C7;">>> 
		<a href="signup.php" style="color: #fDfBf6; ">Open Up Your Account Now</a> <<
		</p>
	</div>
	

</div>

</body>
</html>
