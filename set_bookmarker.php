		<div id="bookmarker_set">
			<a href="javascript:(function(){var%20pageui_s=document.createElement('script');pageui_s.setAttribute('src','http://www.pageui.com/jquery-1.2.5.pack.js');document.getElementsByTagName('head')[0].appendChild(pageui_s);var%20pageui_b=document.getElementsByTagName('body')[0];%20var%20pageui_s=document.createElement('script');pageui_s.charset='UTF-8';pageui_s.src='http://www.pageui.com/link_dropper8h.js';pageui_b.appendChild(pageui_s);})();" style="padding: 2px 5px 2px 5px; margin-top:5px; color:black; font-family:sans-serif; font-size:10pt; text-decoration:none;">
		<?php
				$agent = $_SERVER['HTTP_USER_AGENT'];
				// test for MS Internet Explorer
				if (eregi("msie",$agent) && !eregi("opera",$agent)) { 
				   $val = explode(" ",stristr($agent,"msie"));
					$bd['version'] = $val[1];
					if ( substr($bd['version'], 0, 1) == '6' ) { ?>
			PageUI LinkSaver</a>
		<?php		} else { ?>
			PageUI LinkSaver</a>
		<?php
					}
				// test for Safari
				} elseif(eregi("safari", $agent)) { ?>
			PageUI LinkSaver</a>
		<?php
				} elseif ( (eregi("Firefox", $agent)) or (eregi("mozilla",$agent)) ) { ?>
			PageUI LinkSaver</a>
		<?php       
				} else { ?>
			PageUI LinkSaver</a>
		<?php		
				}
		?>
		</div>