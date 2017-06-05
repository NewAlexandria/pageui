		<div id="homepage_set">
		<?php	
/*			$UI_controls = ' onmouseup="alert('."'#notify'".")".'"'.' onmouseout="alert('."'#notify')".'"'.' onmousedown="alert('."'#notify')".'" ';
			$UI_controls = ' onmousedown="document.getElementById('."'notify'".").innerHTML('up');".'"'.' onmouseout="$('."'#notify').text('out');".'"'.' onmouseup="$('."'#notify').text('down');".'" ';
*/		
				$agent = $_SERVER['HTTP_USER_AGENT'];
				// test for MS Internet Explorer
				if (eregi("msie",$agent) && !eregi("opera",$agent)) { 
				   $val = explode(" ",stristr($agent,"msie"));
					$bd['version'] = $val[1];
					if ( substr($bd['version'], 0, 1) == '6' ) { ?>
				<a  href="javascript:document.body.style.behavior='url(#default#homepage)';document.body.setHomepage('http://www.pageui.com/home.php');" style="font-size: 9pt; color: #000; text-decoration: none;" <?php echo $UI_controls ?>>Make this your start page</a>
		<?php		} else { ?>
				<a href="javascript:document.body.style.behavior='url(#default#homepage)';document.body.setHomepage('http://www.pageui.com/home.php');" style="font-size: 9pt; color: #000; text-decoration: none;" <?php echo $UI_controls ?>>Make this your start page</a>
		<?php
					}
				// test for Safari
				} elseif(eregi("safari", $agent)) { ?>
				<a href="http://www.pageui.com/homepage.php" style="color: #000; text-decoration: none;" <?php echo $UI_controls ?>>Set your home page</a>
		<?php
				} elseif ( (eregi("Firefox", $agent)) or (eregi("mozilla",$agent)) ) { ?>
				<a href="http://www.pageui.com/homepage.php" style="color: #000; text-decoration: none;" <?php echo $UI_controls ?>>Set your home page</a>
		<?php       
				} else { ?>
				<a href="http://www.pageui.com/homepage.php" style="color: #000; text-decoration: none;" <?php echo $UI_controls ?>>Set your start page</a>
				
		<?php		
				}
		?>
		</div>