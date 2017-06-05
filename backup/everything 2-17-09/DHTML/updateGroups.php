<?php
session_start();
?>
<div style="height: 14px;">
	<form action="javascript:updateGroup();" method="post" id="update_group_form" name="update_group_form">
		<input name="proc" id="form_proc" type="hidden" value="update" />
		<input name="page" id="form_page" type="hidden" value="groups_block_list.php" />
		<input name="page_id" id="form_page_id" type="hidden" value="<?php echo $_COOKIE['page_id']; ?>" />
		<input name="user_id" id="form_user_id" type="hidden" value="<?php echo $_SESSION['user_id']; ?>" />
		<input name="group_id" id="form_group_id" type="hidden" value="<?php echo $_REQUEST['group_id']; ?>" />

		<table width="100%" cellpadding="0" cellspacing="0" >
	
		<tr><td>
			<input name="title" id="form_title" type="text" size="14" maxlength="60" tabindex="1" value="<?php echo $_REQUEST['name']; ?>" class="groups_update_UI" style="font-size: 9px; background-color: #e8e8e8; color: #5F7F59;" />
		</td>	</tr>
	
		</table>
	</form>
</div>
	
<script type="text/javascript">
<!--

  	function updateGroup( ) {
		var getstr;
		getstr = $("#update_group_form input").serialize() ;

//		getstr = $("#update_group_form > :input[type != submit]").map(function(){
//			return $(this).attr("name") + "=" + $(this).val();
//		}).get().join("&") ;
		
//		alert('config/groups.php?AJAX=1&group_id=' + g_id + 'name=' + $('#groups_name').val() + '&' + getstr);

//		alert( getstr ) ;
		
		$('#notify').load('config/groups.php?AJAX=1&' + getstr, function() { 
			$('#links_groups').load('groups_block_list.php?mode=edit'); 
		});
    }
    $('#form_title').focus();



//-->
</script>