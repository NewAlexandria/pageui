<?php // pseudo-checks the loading of $resPagesif (! $resPages) {	$sqlPages = "SELECT P.* FROM Pages P INNER JOIN UsersPages UP ON P.id = UP.page_id WHERE UP.user_id = ".$_SESSION['user_id']." ORDER BY UP.sort ASC";	$resPages = mysql_query($sqlPages);		$errtxt .= $sql; 	$errtxt .= '<br/>rows (p): '.mysql_num_rows($resPages).'<br/>'; } // sets page in _Sessionif ( $_REQUEST['s_page_id'] ) $_SESSION['page_id'] = $_REQUEST['s_page_id'];?>	<div class="pages">	<hr size="0.5" />	<ul compact type="none">	<?php		if (mysql_num_rows($resPages)>0) {			while ($r = mysql_fetch_assoc($resPages)) { 						// determine whether to draw ' selected' label			if ( $r['id'] == $_SESSION['page_id'])  {				$sel = 'menu_element_active';			} else {				$sel = 'menu_element';			}			?>		<li class="<?php echo $sel?>"><a href="<?php echo $site_uri.$site_path; ?>home.php?s_page_id=<?php echo $r['id']?>"><?php echo $r['Name']?></a></li>			<?php 	}		} ?>		</ul></div><div id="tools_UI">	<div style="font-size: 12pt; padding: 2px 4px 2px 4px; font-weight: bold; color: green; background-color: #ddd; border: 1px #eee solid; width: 14px; text-align: center;"><a href="javascript:makeRequest('DHTML/addPages.html','','tools_UI');">+</a></div>	</div><!-- <div id="tools_UI">	<form action="config/pages.php" method="post" id="new_page_form" name="new_page_form">	New Page:  	<input name="pages_submit" type="submit" value="Add" id="pages_submit" />	<input name="name" id="pages_name" type="text" size="15" maxlength="60" tabindex="1" value="<?php echo $name ?>" />				<input name="proc" type="hidden" value="add" />	<input name="page" type="hidden" value="edit.php" />	</form>		</div> -->  <script type="text/javascript">// <![CDATA[	function submitenter(myfield,e)	{	var keycode;	if (window.event) keycode = window.event.keyCode;	else if (e) keycode = e.which;	else return true;			if (keycode == 13)	{		get(document.getElementById('new_link_form'));				return false;	}		else		return true;	}   var http_request = false;   function makeRequest(url, parameters, target) {      http_request = false;      if (window.XMLHttpRequest) { // Mozilla, Safari,...         http_request = new XMLHttpRequest();         if (http_request.overrideMimeType) {         	// set type accordingly to anticipated content type            //http_request.overrideMimeType('text/xml');            http_request.overrideMimeType('text/html');         }      } else if (window.ActiveXObject) { // IE         try {            http_request = new ActiveXObject("Msxml2.XMLHTTP");         } catch (e) {            try {               http_request = new ActiveXObject("Microsoft.XMLHTTP");            } catch (e) {}         }      }      if (!http_request) {         alert('Cannot create XMLHTTP instance');         return false;      }      http_request.onreadystatechange = alertContents();      http_request.open('POST', url + parameters, true);      http_request.send(null);   }   function alertContents() {//	  alert(http_request.responseText);      if (http_request.readyState == 4) {         if (http_request.status == 200) {            //alert(http_request.responseText);            res = http_request.responseText;            document.getElementById('tools_UI').innerHTML = http_request.responseText;         } else {            alert('There was a problem with the request.');         }      }   }   	function get(obj) {	  var getstr = "?";	  for (i=0; i<obj.getElementsByTagName("input").length; i++) {			if (obj.getElementsByTagName("input")[i].type == "text") {			   getstr += obj.getElementsByTagName("input")[i].name + "=" + 					   escape(obj.getElementsByTagName("input")[i].value) + "&";			}			if (obj.getElementsByTagName("input")[i].type == "hidden") {			   getstr += obj.getElementsByTagName("input")[i].name + "=" + 					   escape(obj.getElementsByTagName("input")[i].value) + "&";			}			if (obj.getElementsByTagName("input")[i].type == "checkbox") {			   if (obj.getElementsByTagName("input")[i].checked) {				  getstr += obj.getElementsByTagName("input")[i].name + "=" + 					   escape(obj.getElementsByTagName("input")[i].value) + "&";			   } else {				  getstr += obj.getElementsByTagName("input")[i].name + "=&";			   }			}			if (obj.getElementsByTagName("input")[i].type == "radio") {			   if (obj.getElementsByTagName("input")[i].checked) {				  getstr += obj.getElementsByTagName("input")[i].name + "=" + 					   escape(obj.getElementsByTagName("input")[i].value) + "&";			   }		 }  		 if (obj.getElementsByTagName("select")[i] ) {			var sel = obj.getElementsByTagName("select")[i];			getstr += sel.name + "=" + escape(sel.options[sel.selectedIndex].value) + "&";		 }		 	  }	  makeRequest('config/links.php', getstr);	}	// ]]></script>