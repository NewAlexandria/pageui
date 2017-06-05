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

	  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.0/jquery-ui.min.js"></script>

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

            $("#TL").sortable({
            	items: '.s'
            });
            
            $("#TestL_1").sortable({
            	connectWith: ["#TestL_2"],
            	items: '.link_edit',
      			start: function() {
      				$("#TL").sortable('disable');
                    $("body").css("background-color", "#000");
                    $("#TestL_1 a").bind("click", StopFollow);
                },
                stop: function() {
      				$("#TL").sortable('enable');
                    $("body").css("background-color", "#777");
                    $("#TestL_1 a").unbind("click", StopFollow);
                }
            });

            $("#TestL_2").sortable({
            	connectWith: ["#TestL_1"],
            	items: '.link_edit',
      			start: function() {
      				$("#TL").sortable('disable');
                    $("body").css("background-color", "#000");
//                    $("#TestL_2 a").bind("click", StopFollow(this));
//                    $('.link_edit').click( function(e){ StopPerc(e); }); 
//                    $(".link_edit").bind("click", StopPerc(e) );
                },
                stop: function() {
      				$("#TL").sortable('enable');
                    $("body").css("background-color", "#777");
//                    $("#TestL_2 a").unbind("click", StopFollow);
//                    $(".link_edit").unbind("click", StopPerc(e) );
                }
            });            
			
			
			
			$(".link_edit").hover( function(e) {
				$("#TL").sortable('disable');
			}, function(e) {
				$("#TL").sortable('enable');
			});
        });
        
        function StopFollow(here) {
            return false;
        }
        
        function StopPerc(ev) {
            ev.stopPropagation(); 
			ev.preventDefault();
        }
        
    </script> 
    <style type="text/css">
        a { color: #ffffff; }
        div { background: #727EA3; color: #FFF; width: 250px; margin: 5px; font-size: 10px; font-family: Arial; padding: 3px; }
        .s
        {
        	list-style: none;
        	background-color: #0ff;
        	width: 210px;
        	padding: 20px 10px 10px 10px;
        }
        }
        #TestL_1 { list-style: none; background-color: #0ff; width: 120px;  }
        }
        #TestL_2 { list-style: none; background-color: #f0f; width: 120px;  }
        }
        #TL { border: 1px #000 solid; }
        .link_edit { background-color: #266; }
    </style>

</head>
<body>

<div id="TT"> 
	<div id="Test" class="s">
		<div class="link_edit"><a href="javascript:alert('sdg');" onclick="">Widget</a></div>
		<div class="link_edit"><a href="http://www.google.com" onclick="alert('sdg');">Google</a></div>
		<div class="link_edit"><a href="http://www.yahoo.com">Yahoo</a></div>
		<div class="link_edit"><a href="http://www.live.com">MSN Live</a></div>
	</div>
	
	
	<div id="Test2" class="s">
		<div class="link_edit"><a href="http://www.digg.com" onclick="alert('sdg');">Digg</a></div>
		<div class="link_edit"><a href="http://www.nature.com">Nature</a></div>
		<div class="link_edit"><a href="http://www.science.com">Science</a></div>
	</div>
</div>

<div id="TL"> 
	<ul id="TestL_1" class="s">
		text
		<li class="link_edit"><a href="javascript:alert('sdg');" onclick="">Widget</a></li>
		<li class="link_edit"><a href="http://www.google.com" onclick="alert('sdg');">Google</a></li>
		<li class="link_edit"><a href="http://www.yahoo.com">Yahoo</a></li>
		<li class="link_edit"><a href="http://www.live.com">MSN Live</a></li>
	</ul>
	
	
	<ul id="TestL_2" class="s">
		more
		<li class="link_edit"><a href="http://www.digg.com" onclick="alert('sdg');">Digg</a></li>
		<li class="link_edit"><a href="http://www.nature.com">Nature</a></li>
		<li class="link_edit"><a href="http://www.science.com">Science</a></li>
	</ul>
</div>

</body>
</html>