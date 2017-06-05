
    <!-- Replace with  -->
    <script src="http://www.google.com/jsapi?key=ABQIAAAAmu4fvhDLB74GSgJ8PAJDMhRczWgZXMmFboovjxxxpdWWntGnoBQj7pAqGo1C29LGz3Y3FW8zcuXeYg" type="text/javascript"></script>

<!-- www.pageui.com:  ABQIAAAAmu4fvhDLB74GSgJ8PAJDMhRczWgZXMmFboovjxxxpdWWntGnoBQj7pAqGo1C29LGz3Y3FW8zcuXeYg   -->

<!-- test.pageui.com:  ABQIAAAAmu4fvhDLB74GSgJ8PAJDMhRoMpEEZFX-fmxYDxoMQg7tXyeuaxT1pJj83rEMoThoXBIIPavWQeK5Dw   -->

<!-- pageui.com: ABQIAAAAmu4fvhDLB74GSgJ8PAJDMhSc7GHdH9YueOAsoFiDuoZcuzNe_xQtOypMPfuwYqCb7weCuyjKMn8HXw  -->

<!-- *.pageui.com: ABQIAAAAmu4fvhDLB74GSgJ8PAJDMhREffCrRCKLTcfpk7b0IgWsPi5taxT3JaUv81qxTXvrilzuWOGxFiycKg  -->

    <script type="text/javascript">
    //<![CDATA[
    google.load('search', '1');
    
    function OnLoad() {
/*
      // Dynamically load CSS to override defaults
      var css = document.createElement('link');
      css.href = '../../css/gsearch_green.css';
      css.type = 'text/css';
      css.rel = 'stylesheet';
      document.getElementsByTagName('head')[0].appendChild(css);
*/      

      // Create a search control
      var searchControl = new google.search.SearchControl();

<?php
if ( $_REQUEST['q'] ) {
	$q = $_REQUEST['q'];
} else {
	$q = "carnegie";
}

if ( $_REQUEST['page_search'] ) {
	$conditions = "AND GP.page_id = ".$_SESSION['page_id'];
} else {
	$conditions;
}

$sql = "SELECT G.title, GP.row AS GP_row, GP.col AS GP_col, L.col AS L_col, L.row AS L_row, L.id AS link_id, L.URL, L.title, GP.group_id, GP.page_id FROM Groups G INNER JOIN GroupsPages AS GP ON GP.group_id = G.id INNER JOIN Pages P ON GP.page_id = P.id LEFT OUTER JOIN Links AS L ON ( L.user_id = GP.user_id AND L.group_id = GP.group_id AND L.page_id = GP.page_id ) WHERE GP.user_id = ".$_SESSION['user_id']." AND L.URL IS NOT NULL ".$conditions." ORDER BY L.user_id, L.page_id, GP.row, GP.col, L.col, L.row";

$result = mysql_query($sql);
$errtxt .= $sql.'<br/>'.mysql_num_rows($result); 


// if corrupt accounts; needs to trigger admin notification
if (mysql_num_rows($result)>0) {
	while ( $r = mysql_fetch_assoc($result) ) {
?>
      var siteSearch = new google.search.WebSearch();
      siteSearch.setUserDefinedLabel("<?php echo $r['title']; ?>");
      siteSearch.setUserDefinedClassSuffix("siteSearch");
      siteSearch.setSiteRestriction("<?php echo $r['URL']; ?>");
      searchControl.addSearcher(siteSearch);
<?php
	}
} else {
?>

	searchControl.addSearcher(new google.search.WebSearch());

<?php
} 
?>


      // Establish a keep callback.  draws a "copy" field that onClick pass a GResult object
      searchControl.setOnKeepCallback(this, DummyClipSearchResult, null);

      // tell the searcher to draw itself and tell it where to attach
      searchControl.draw(document.getElementById("searchcontrol"));
//      searchControl.setNoResultsString('...');

      // execute an inital search
      searchControl.execute("<?php echo $q; ?>");
      

    }

//	google.search.Search.getBranding(document.getElementById("branding"));

    function DummyClipSearchResult(result) {}
	
    google.setOnLoadCallback(OnLoad, true);
    //]]>
    </script>

    <div id="searchcontrol"/>
	</div>
	
<!-- <div id="branding">Loading...</div> -->
        