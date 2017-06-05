<?php session_start();

include_once('../config/keys.php');
include_once('../config/db.php');
require_once('../config/login_lib.php');

if ( validateUser( $_REQUEST['user_id'], $_REQUEST['key']) ) { 

	if ( $_REQUEST['page_search'] ) {
		$conditions = "AND GP.page_id = ".$_SESSION['page_id'];
	} else {
		$conditions='';
	}
	
	$sql = "SELECT G.title, GP.row AS GP_row, GP.col AS GP_col, L.col AS L_col, L.row AS L_row, L.id AS link_id, L.URL, L.title, GP.group_id, GP.page_id FROM Groups G INNER JOIN GroupsPages AS GP ON GP.group_id = G.id INNER JOIN Pages P ON GP.page_id = P.id LEFT OUTER JOIN Links AS L ON ( L.user_id = GP.user_id AND L.group_id = GP.group_id AND L.page_id = GP.page_id ) WHERE GP.user_id = ".$_REQUEST['user_id']." AND L.URL IS NOT NULL ".$conditions." ORDER BY L.user_id, L.page_id, GP.row, GP.col, L.col, L.row";
	
	$result = mysql_query($sql);
	$errtxt .= '<br/>'.$sql.'<br/>'.mysql_num_rows($result); 

echo '<?xml version="1.0" encoding="UTF-8" ?>';
?>
<GoogleCustomizations>
  <CustomSearchEngine>
  <Title>CSE for PageUI User <?php echo $_REQUEST['user_id']; ?></Title>
  <Description>All links.</Description>
  </CustomSearchEngine>

  <!-- Annotations label the sites to be used in the Search Engine -->
  <Annotations>
<?php

// if corrupt accounts; needs to trigger admin notification
if (mysql_num_rows($result)>0) {
	while ( $r = mysql_fetch_assoc($result) ) {
	if ( ! stripos ( $r['URL'], '&' ) ) {
?>
    <Annotation about="<?php echo str_replace('&', '&amp;', $r['URL']); ?>">
      <Label name="<?php echo $r['page_id']; ?>" />
      <Label name="<?php echo $_REQUEST['user_id']; ?>" />
    </Annotation>
<?php
	}}
}
?>
  </Annotations>

  <!-- Include some annotations living at an external URLs -->
<!-- 
  <Include href="http://www.google.com/cse/samples/veggie_annos1.xml" type="Annotations"/>
  <Include href="http://www.google.com/cse/samples/veggie_annos2.xml" type="Annotations"/>
 -->
</GoogleCustomizations>

<?php } else { ?>

failed credentials

<?php } ?>