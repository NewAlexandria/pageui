 
    <style type="text/css">

    body {
      background-color: white;
      color: black;
      font-family: Arial, sans-serif;
      font-size: 13px;
    }
    
    h2
    {
    	font-size: 18px;
    	font-family: lucida, sans-serif;
    	padding: 0px;
    	margin: 0 10px 0 0;
    }

	#searcher { margin: 5px 10px 0 10px; }
	
    td {
      vertical-align : top;
    }

    td.search-form
    {
    	width: 300px;
    	vertical-align: middle;
    }

    td.search-options {
      padding-left : 20px;
    }

    #results .header
    {
    	font-size: 16px;
    	font-weight: bold;
    	margin-bottom: .25em;
    	margin-top: 1em;
    	background-color: #D6E1FD;
    	margin-left: 10px;
    	padding: 5px;
    }

	.gsc-results
	{
	}

    #results .gs-result
    {
    	margin-bottom: .5em;
    	width: auto;
    	margin-left: 20px;
    }

    #results div.gs-watermark {
      display : none;
    }


    </style>
    <!-- Replace with  -->
    <script src="http://www.google.com/jsapi?key=ABQIAAAAmu4fvhDLB74GSgJ8PAJDMhRczWgZXMmFboovjxxxpdWWntGnoBQj7pAqGo1C29LGz3Y3FW8zcuXeYg" type="text/javascript"></script>

    <script type="text/javascript">
    //<![CDATA[
    google.load('search', '1');
    
    function RawSearchControl() {
      // latch on to key portions of the document
      this.searcherform = document.getElementById("searcher");
      this.results = document.getElementById("results");
      this.cursor = document.getElementById("cursor");
      this.searchform = document.getElementById("searchform");

      // create map of searchers as well as note the active searcher
      this.searchers = new Array();
	
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
	
	$sql = "SELECT G.title as group_title, GP.row AS GP_row, GP.col AS GP_col, L.col AS L_col, L.row AS L_row, L.id AS link_id, L.URL, L.title, GP.group_id, GP.page_id, P.Name as page_name FROM Groups G INNER JOIN GroupsPages AS GP ON GP.group_id = G.id INNER JOIN Pages P ON GP.page_id = P.id LEFT OUTER JOIN Links AS L ON ( L.user_id = GP.user_id AND L.group_id = GP.group_id AND L.page_id = GP.page_id ) WHERE GP.user_id = ".$_SESSION['user_id']." AND L.URL IS NOT NULL ".$conditions." ORDER BY L.user_id, L.page_id, GP.row, GP.col, L.col, L.row";
	
	$result = mysql_query($sql);
	$errtxt .= $sql.'<br/>'.mysql_num_rows($result); 

	$title = str_replace('"', '\"', $_REQUEST['q']);
	
	// if corrupt accounts; needs to trigger admin notification
	if (mysql_num_rows($result)>0) {
		while ( $r = mysql_fetch_assoc($result) ) {
			$title = str_replace('"', '&quot;', $r['title']);
	?>
		  var searcher = new google.search.WebSearch();
		  searcher.setUserDefinedLabel("<?php echo $title; ?>");
		  searcher.PageuiTitle = "<?php echo $title; ?>";
		  searcher.PageuiURL = "<?php echo $r['URL']; ?>";
		  searcher.PageuiPageName = "<?php echo $r['page_name']; ?>";
		  searcher.PageuiGroupTitle = "<?php echo $r['group_title']; ?>";
		  searcher.setSiteRestriction("<?php echo $r['URL']; ?>");
		  searcher.setNoHtmlGeneration();
   			searcher.setResultSetSize(google.search.Search.LARGE_RESULTSET);

		  searcher.setSearchCompleteCallback(this,
											 RawSearchControl.prototype.searchComplete,
											 [searcher]
											 );
		  this.searchers["<?php echo $title; ?>"] = searcher;
	<?php
			$searcher_execs .= '        this.searchers["'.$title.'"].execute(form.input.value);'."\n";
	
			$first_execs .= '        this.searchers["'.$title.'"].execute("'.$_REQUEST['q'].'");'."\n";
		}
	} else {
	?>
		  // wire up a raw GwebSearch searcher
		  var searcher = new google.search.WebSearch();
		  searcher.setUserDefinedLabel('All of Google');
		  searcher.PageuiTitle = 'You have no links, so we will search all of Google';
		  searcher.setSiteRestriction("http://www.google.org");
		  searcher.setNoHtmlGeneration();
		  searcher.setSearchCompleteCallback(this,
											 RawSearchControl.prototype.searchComplete,
											 [searcher]
											 );
		  this.searchers["All of Google"] = searcher;
	<?php
			$searcher_execs .= "        this.searchers['All of Google'].execute(form.input.value);\n";
	
			$first_execs .= '        this.searchers["All of Google"].execute("'.$q.'");'."\n";
	
	}
	?>
      // now, create a search form and wire up a submit and clear handler
      this.searchForm = new google.search.SearchForm(true, this.searchform);
      this.searchForm.setOnSubmitCallback(this,
                                          RawSearchControl.prototype.onSubmit);
      this.searchForm.setOnClearCallback(this,
                                          RawSearchControl.prototype.onClear);

	this.searchForm.input.value = "<?php echo $q ?>";
	<?php echo $first_execs; ?>

    }

    /**
     * figure out which searcher is active by looking at the radio
     * button array
     */
////  FLAGGED FOR REMOVAL     
    RawSearchControl.prototype.computeActiveSearcher = function() {
      for (var i=0; i<this.searcherform["searcherType"].length; i++) {
        if (this.searcherform["searcherType"][i].checked) {
          this.activeSearcher = this.searcherform["searcherType"][i].value;
          return;
        }
      }
    }

    RawSearchControl.prototype.onSubmit = function(form) {
//      this.computeActiveSearcher();

      // always clear last stuff from the page
      this.clearResults();

      if (form.input.value) {
		// ADD REFERENCE TO EVERY SEARCHER ON THE PAGE
		<?php echo $searcher_execs ?>
      }

      // always indicate that we handled the submit event
      return false;
      
/*      // update the 'search all of google' link
      var google_link = document.getElementById("allGoogle");
      removeChildren(google_link);
	  var more = createLink('http://www.google.com/search?oe=utf8&ie=utf8&source=uds&q=<?php echo $q ?>', 'Search all of Google', GSearch.LINK_TARGET_SELF);
	  google_link.appendChild(more);
*/
    }

    RawSearchControl.prototype.onClear = function(form) {
      this.clearResults();
    }

    RawSearchControl.prototype.searchComplete = function(searcher) {

////  FLAGGED FOR REMOVAL.   NEED A PRIOR FUNCTION THAT CLEARS OLD DATA, MAYBE IN .onSubmit
      // always clear old from the page
//      this.clearResults();

      // if the searcher has results then process them
      if (searcher.results && searcher.results.length > 0) {

/*        // print the result titles
        var div = createDiv("Result Titles", "header");
        this.results.appendChild(div);
        for (var i=0; i<searcher.results.length; i++) {
          var result = searcher.results[i];
          var titleLine = result.title;

          // add in lat,lng for local results
          if (result.GsearchResultClass == GlocalSearch.RESULT_CLASS) {
            titleLine += " (" + result.lat + ", " + result.lng + ")";
          }
          if (result.html) {
            titleLine += " ** html is present **";
          }
          div = createDiv(titleLine);
          this.results.appendChild(div);
        }
*/
        // now manually generate the html that we disabled
        // initially and display it
        var divMain = createDiv('', '', searcher.PageuiTitle);
        this.results.appendChild(divMain);
        
        var page_url = searcher.PageuiURL;
        if ( searcher.PageuiURL.length > 75 ) {
        	var page_url = searcher.PageuiURL.substring(0,75) + '...';
        }
        var div = createDiv(searcher.PageuiTitle + ' <span style="font-size: 12px; color: #446; font-style: italic;">( ' + page_url + ' ) ' + searcher.PageuiPageName + '::' + searcher.PageuiGroupTitle + '</span>', "header");
        divMain.appendChild(div);
        for (var i=0; i<searcher.results.length; i++) {
          var result = searcher.results[i];
          searcher.createResultHtml(result);
          if (result.html) {
            div = result.html.cloneNode(true);
          } else {
            div = createDiv("** failure to create html **");
          }
          divMain.appendChild(div);
        }

        var divCursor = createDiv('', 'gsc-cursor-box');
        divMain.appendChild(divCursor);

        // now, see if we have a cursor, and if so, create the 
        // cursor control
        if (searcher.cursor) {
/*
          var cursorNode = createDiv(null, "gsc-cursor");
          for (var i=0; i<searcher.cursor.pages.length; i++) {
            var className = "gsc-cursor-page";
            if (i == searcher.cursor.currentPageIndex) {
              className = className + " gsc-cursor-current-page";
            }
            var pageNode = createDiv(searcher.cursor.pages[i].label, className);
            pageNode.onclick = methodClosure(this, this.gotoPage, 
                                             [searcher, i]); 
            cursorNode.appendChild(pageNode);
          }
          divCursor.appendChild(cursorNode);
*/          
          var more = createLink(searcher.cursor.moreResultsUrl,
                                '<i>go to Google.com for more results &nbsp;&raquo;</i>',
                                GSearch.LINK_TARGET_SELF,
                                "gsc-trailing-more-results");
          divCursor.appendChild(more);
        }
      }
    }
    
    RawSearchControl.prototype.gotoPage = function(searcher, page) {
      searcher.gotoPage(page);
    }

    RawSearchControl.prototype.clearResults = function() {
      removeChildren(this.results);
      removeChildren(this.cursor);
    }

    /**
     * Static DOM Helper Functions
     */
    function removeChildren(parent) {
      while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
      }
    }
    function createDiv(opt_text, opt_className, opt_id) {
      var el = document.createElement("div");
      if (opt_text) {
        el.innerHTML = opt_text;
      }
      if (opt_className) { el.className = opt_className; }
      if (opt_id) { el.id = opt_id; }
      return el;
    }

    function methodClosure(object, method, opt_argArray) {
      return function() {
        return method.apply(object, opt_argArray);
      }
    }

    function createLink(href, opt_text, opt_target, opt_className, opt_divwrap) {
      var el = document.createElement("a");
      el.href = href;
      if (opt_text) {
        el.innerHTML = opt_text;
      }
      if (opt_className) {
        el.className = opt_className;
      }
      if (opt_target) {
        el.target = opt_target;
      }
      if (opt_divwrap) {
        var div = this.createDiv(null, opt_className);
        div.appendChild(el);
        el = div;
      }
      return el;
    }


    // register to be called at OnLoad when the page loads
    google.setOnLoadCallback(OnLoad, true);

    function OnLoad() {
      new RawSearchControl();

    }
    
      	
    //]]>
    </script>

    <form id="searcher">
      <table>
		<tr><td><h2>PageUI Search, beta</h2></td>
        <td class="search-form">
          <div id="searchform">Loading</div>
        </td>
<!-- 
        <td class="search-options">
          <input name="searcherType" value="web" type="radio" checked><label>web</label>
          &nbsp;<br/>
          <input name="searcherType" value="local" type="radio"><label>local</label>
        </td>
 -->
<!-- 
 		<td style="padding: 5px 0 0 20px" id="allGoogle"><a href="http://www.google.com/search?oe=utf8&ie=utf8&source=uds&q=<?php echo $q ?>">Search all of Google</a></td>
 -->
 		</tr>
      </table>
    </form>
    <div class="gsc-results">
      <div id="results"></div>
      <div id="cursor" class="gsc-cursor-box"></div>
      <div style="margin: 30px; font-style: italic;">nothing else to show....</div>
    </div>
	
<!-- <div id="branding">Loading...</div> -->
        