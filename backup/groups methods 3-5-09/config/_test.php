<?php
// session_start();

// setcookie("user", 'sss', time()-3600);

// include_once('keys.php');
// include_once('db.php');

if ( $_REQUEST['x'] == '' ) {

	$iDaystoExpire = 60;
	
	
//	echo date('Y-m-d', mktime(0, 0, 0, date("m"), date("d")+$iDaystoExpire, date("Y")) );
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
	  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" type="text/javascript"></script>

	  <script type="text/javascript" src="../jquery-ui-personalized-1.6rc6.min.js"></script>

<!-- 
http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js
http://ajax.googleapis.com/ajax/libs/jqueryui/1.5.2/jquery-ui.min.js

	  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" type="text/javascript"></script>

	  <script type="text/javascript" src="../jquery-ui-personalized-1.6rc6.min.js"></script>

	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
	<script type="text/javascript" src="http://dev.jquery.com/view/tags/ui/latest/ui/ui.core.js"></script>
	<script type="text/javascript" src="http://dev.jquery.com/view/tags/ui/latest/ui/ui.sortable.js"></script>
 -->
    <script type="text/javascript">
        $(document).ready(function() {
            //Just so we don't actually go anywhere
            $("#Test a").click(function() {
//                alert(this.href);
                return false;
            });

            $("div#TT").sortable({});
            
            $("div#Test").sortable({
            	connectWith: ["#Test2"],
      			start: function() {
                    $("body").css("background-color", "#000");
                    $("#Test a").bind("click", StopFollow);
                },
                stop: function() {
                    $("body").css("background-color", "#777");
                    $("#Test a").unbind("click", StopFollow);
                }
            });

            $("div#Test2").sortable({
            	connectWith: ["#Test"],
      			start: function() {
                    $("body").css("background-color", "#000");
                    $("#Test2 a").bind("click", StopFollow);
                },
                stop: function() {
                    $("body").css("background-color", "#777");
                    $("#Test2 a").unbind("click", StopFollow);
                }
            });            

        })
        function StopFollow() {
            return false;
        }
    </script> 
    <style type="text/css">
        a { color: #ffffff; }
        div { background: #727EA3; color: #FFF; width: 100px; margin: 5px; font-size: 10px; font-family: Arial; padding: 3px; 
        div .s { list-style: none; background-color: #fff; width: 180px;  }
        }
    </style>

</head>
<body>

<div id="TT"> 
	<div id="Test" class="s">
		<div><a href="javascript:alert('sdg');" onclick="">Widget</a></div>
		<div><a href="http://www.google.com" onclick="alert('sdg');">Google</a></div>
		<div><a href="http://www.yahoo.com">Yahoo</a></div>
		<div><a href="http://www.live.com">MSN Live</a></div>
	</div>
	
	
	<div id="Test2" class="s">
		<div><a href="http://www.digg.com" onclick="alert('sdg');">Digg</a></div>
		<div><a href="http://www.nature.com">Nature</a></div>
		<div><a href="http://www.science.com">Science</a></div>
	</div>
</div>

</body>
</html>