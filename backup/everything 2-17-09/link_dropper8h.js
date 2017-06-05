// our thanks to the many facets of the web that lit the way to this code
// - Zachary Jones

var bigpage = 'test';
var first = 1;

function doFirst() {
	jQuery.getJSON( "http://www.pageui.com/test/link_dropper.php?URL=" + encodeURIComponent( location.href ) + "&title=" + encodeURIComponent( parent.document.title ) + "&format=json&jsoncallback=?", function(data){
		bigpage = data.bigpage;
		addFrame();
	});
}

function loadFrame(link) {
	jQuery.getJSON( link + "&format=json&jsoncallback=?", function(data){
		jQuery('#dropper_frame').html('<div>' + data.bigpage + '</div>');
	});
}

function addFrame() {
	jQuery('<div id="dropper_frame"></div>').appendTo("body");
	jQuery('#dropper_frame').fadeOut(0, function () {
		jQuery("#dropper_frame").attr("style", "width:90%;height:140px;border:0;position:fixed;top:20px;left:5%;z-index:10000003;visibility:visible;display:block;background-color:#BAD7C7;min-width:640px;font-size:12px;");
		
		loadFrame( "http://www.pageui.com/test/link_dropper.php?URL=" + encodeURIComponent( location.href ) + "&title=" + encodeURIComponent( parent.document.title ) );
//		jQuery('#dropper_frame').html('<div>'+bigpage+'</div>');
	});
	jQuery('#dropper_frame').fadeIn("slow");
}

function closeFrame() {
  jQuery("#dropper_frame").fadeOut("slow", function () {   
	  var puiframe = document.getElementById('dropper_frame');
	  puiframe.parentNode.removeChild(puiframe);
  });	
}

if (document.getElementsByTagName('frameset').length > 0 ){
  alert("We're sorry.\nPageUI Dropper doesn't work at\nthis time on pages that use frames.");
} else if(!document.getElementById('dropper_frame')){
	if ( first == 1 ) {
		setTimeout("addFrame()", 1500);
	} else { addFrame() }
} else { closeFrame() }

