				<div style="width: 100%"><a href="<?php echo $site_uri.$site_path.$page; ?>?s_page_id=<?php echo $r['id']?>" style="margin-left: 3px"><?php echo $r['Name']?></a></div>				<div class="link_UI_element pmenu" id="edit_<?php echo $r['id']; ?>">				<a href="javascript:updatePage_UI('<?php echo $r['id']; ?>','<?php echo $r['Name']?>');" style="display:inline-block; padding: 0 2px 0 1px; ">E</a> 				<a href="javascript:deletePage('<?php echo $r['id']; ?>');" style="display:inline-block; padding: 0 1px 0 1px; ">X</a></div>				</td>