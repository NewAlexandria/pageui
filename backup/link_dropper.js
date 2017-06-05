
    var opacity = 0.0;
    var timer;
    function setOpacity(element_id, opacity) {
        element = document.getElementById(element_id);
        opacity = (opacity == 1) ? 0.99999 : opacity;
        element.style.opacity = opacity;
        element.style.filter = "alpha(opacity:"+opacity*100+")";
    }
    function timedFade(element){
      timer = setTimeout("triggerFade('"+element+"', "+opacity+")", 10);
    }
    function triggerFade(element, this_opacity){
      if(this_opacity <= .95){
        setOpacity(element, this_opacity);
        timedFade(element);
        opacity += 0.05;
      }else{
        clearTimeout(timer);
      }
    }


	function submitenter(myfield,e)
	{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	
	alert('es');
	
	if (keycode == 13)
	{
//	myfield.submit();
	self.focus();
//	window.close();
	alert('es');
	
//	closeFrame();
	return false;
	}
	else
	return true;
	}
	
    	function addFrame(){
/*
    	  var overlay = document.createElement('div');
          overlay.id = "greybase";
      	  overlay.setAttribute('style', 'position:fixed;top:0;left:0;width:100%;height:100%;z-index:9999999;color:#fff;background-color:#000;');
      	var loader = document.createElement('div');
          loader.id = "loader";
      	  loader.setAttribute('style', 'position:fixed;top:50%;left:50%;width:75px;height:75px;margin:-37px 0 0 -37px;z-index:9999999;');
          loader.innerHTML = '<img src="http://ma.gnolia.com/images/loading_icon.gif" />';

		var close_button = document.createElement('a');
          close_button.onclick = closeFrame;
          close_button.innerHTML = '<img src="http://www.pageui.com/images/close.gif" alt="close" title="close" style="border:0;padding:0;background:none;margin:0;"/>';
          close_button.href = "javascript:void(0);";    
          close_button.id = "close_button";
      	  close_button.setAttribute('style', 'position:fixed;top:6%;right:6%;z-index:10000001;margin:3px 30px 0 0;font-size:9px;border:1px solid #9EB847;padding:0;background:0;height:15px;width:15px;');
*/      	  
      	var ifr = document.createElement('iframe');
      	  ifr.setAttribute('id', 'pageui_dropper_frame');
      	  ifr.setAttribute('name', 'pageui_frame');
          ifr.setAttribute('style', 'width:460px;height:220px;border:0;position:fixed;top:15%;left:60%;z-index:1;visibility:visible;display:block;text-align:left;');
        ifr.style.opacity = 0;

//      	document.body.appendChild(overlay);
//     	document.body.appendChild(loader);
//        document.body.appendChild(close_button);
      	document.body.appendChild(ifr);
      	ifr.src = "http://www.pageui.com/link_dropper.php?URL="+encodeURIComponent(location.href)+"&title="+encodeURIComponent(parent.document.title);
      	timedFade("pageui_dropper_frame");
      }
      
      function closeFrameInner(bis){
      	
//      	var sss = document.getElementById('new_link_form'); 
//      	var ifr = bit; 
//      	alert('sss.id'); 
//		bit.parentNode.parentNode.parentNode.parentNode.removeChild(bit.parentNode.parentNode.parentNode);
	}
      
      function closeFrame(){
      	
      	var ifr = document.getElementById('pageui_dropper_frame');
//      	var close_button = document.getElementById('close_button');
//      	var overlay = document.getElementById('greybase');
 //     	var loader = document.getElementById('loader');
      	ifr.parentNode.removeChild(ifr);
//      	close_button.parentNode.removeChild(close_button);
//      	overlay.parentNode.removeChild(overlay);
//      	loader.parentNode.removeChild(loader);
      }
      
      
      if(document.getElementsByTagName('frameset').length > 0 ){
        alert("Please pardon.\nWe're still working on\nupport for pages with frames.");
      }else if(!document.getElementById('pageui_dropper_frame')){
      
        addFrame();
//        alert('s');
      }else{
      	closeFrame();
      }

      
/*      
function HttpRequest(url){
var pageRequest = false //variable to hold ajax object
/*@cc_on
   @if (@_jscript_version >= 5)
      try {
      pageRequest = new ActiveXObject("Msxml2.XMLHTTP")
      }
      catch (e){
         try {
         pageRequest = new ActiveXObject("Microsoft.XMLHTTP")
         }
         catch (e2){
         pageRequest = false
         }
      }
   @end
// old place of end-block-comment  @/

if (!pageRequest && typeof XMLHttpRequest != 'undefined')
   pageRequest = new XMLHttpRequest()

if (pageRequest){ //if pageRequest is not false
   pageRequest.open('GET', url, false) //get page synchronously 
   pageRequest.send(null)
   alert('aa'+pageRequest.responseText)
   history.back()
   
   embedpage(pageRequest)
   }
}


function embedpage(request){
//if viewing page offline or the document was successfully retrieved online (status code=2000)
if (window.location.href.indexOf("http")==-1 || request.status==200)
   
   var addNotify = new Object()
   addNotify.setBgColor('white');
   addNotify.setSize(130,130);
   addNotify.setLocation(250,50);

   document.addChild(addNotify);
//   document.write(request.responseText)
}

HttpRequest("http://127.0.0.1/~equinox/pageui/config/_test.php") //include "external.htm" onto current page


function loadScript(scriptURL) {
 var scriptElem = document.createElement('SCRIPT');
 scriptElem.setAttribute('language', 'JavaScript');
 scriptElem.setAttribute('src', scriptURL);
 document.body.appendChild(scriptElem);
}

loadScript('http://216.203.40.101/projects/tutorials/'
          + 'creating_huge_bookmarklets/helloworld.js');

*/