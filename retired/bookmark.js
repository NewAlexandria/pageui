function CreateBookmarkLink() {	
	title = "PageUI"; 
	url = "http://www.pageui.com/home.php";
	
	if (window.sidebar) { // Mozilla Firefox Bookmark
		window.sidebar.addPanel(title, url,"");
	} else if( window.external ) { // IE Favorite
		window.external.AddFavorite( url, title); }
	else if(window.opera && window.print) { // Opera Hotlist
		return true; 
	}

}
