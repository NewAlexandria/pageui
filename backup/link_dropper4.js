//if ( (parseInt(navigator.appVersion)==4) && (agt.indexOf('mozilla')!=-1) && (agt.indexOf('spoofer')==-1) && (agt.indexOf('compatible') == -1) ) {
function msieversion()
{
  var ua = window.navigator.userAgent
  var msie = ua.indexOf ( "MSIE " )

  if ( msie > 0 )      // If Internet Explorer, return version number
	 return parseInt (ua.substring (msie+5, ua.indexOf (".", msie )))
  else                 // If another browser, return 0
	 return 0

}


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
	if(this_opacity <= .75){
	  setOpacity(element, this_opacity);
	  timedFade(element);
	  opacity += 0.05;
	}else{
	  clearTimeout(timer);
	}
}


// browser switch
var agt=navigator.userAgent.toLowerCase();


function addFrame() {
	var bVer = '';
	if ( (parseInt(navigator.appVersion)>=4) && (agt.indexOf('msie') != -1) ) {
		bVer = 'msie';
	} else {
		bVer = 'other';
	}

  var overlay = document.createElement('div');
	overlay.id = "greybase";
//  var loader = document.createElement('div');
//	loader.id = "loader";
//    loader.innerHTML = '<img src="http://ma.gnolia.com/images/metamag/loading_icon.gif" />';	  
  var close_button = document.createElement('a');
	close_button.onclick = closeFrame;
	close_button.innerHTML = '<img src="http://ma.gnolia.com/images/metamag/close.gif" alt="close" title="close" style="border:0;"/>';
	close_button.href = "javascript:void(0);";    
	close_button.id = "close_button";
  var ifr = document.createElement('iframe');



	  if ( bVer == 'msie' ) {
		  overlay.style.position = "absolute";
		  overlay.style.top = document.documentElement.scrollTop+"px";
		  overlay.style.left = "0";
		  overlay.style.width = document.documentElement.offsetWidth+"px";
		  overlay.style.height = document.documentElement.offsetHeight+"px";
		  overlay.style.zIndex = "9999999";
		  overlay.style.backgroundColor = "#000";

/*
		  loader.style.position = "absolute";
		  loader.style.top = ((document.documentElement.offsetHeight*.5)+document.documentElement.scrollTop)+"px";
		loader.style.left = ((document.documentElement.offsetWidth*.5)+document.documentElement.scrollLeft)+"px";
		  loader.style.width = "75px";
		  loader.style.height = "75px";
		  loader.style.zIndex = "9999999";
		loader.style.marginTop = "-37px";
		loader.style.marginLeft = "-37px";
*/		
		  close_button.style.position = 'absolute';
		  close_button.style.top = ((document.documentElement.offsetHeight*.06)+document.documentElement.scrollTop)+"px";
		  close_button.style.right = '6%';
		  close_button.style.width = '15px';
		  close_button.style.height = '15px';
		  close_button.style.zIndex = '10000001';
		  close_button.style.margin = '4px 30px 0 0';
		  close_button.style.fontSize = '9px';
		  close_button.style.lineHeight = '2em';
		  close_button.style.border = '0';

		  ifr.id = 'metamag_frame'
		  ifr.style.width = '90%';
		  ifr.style.height = (document.documentElement.offsetHeight*0.9)+"px";
		  ifr.style.border = '0';
		  ifr.style.position = 'absolute';
		  ifr.allowTransparency = 'true';
		  ifr.style.top = ((document.documentElement.offsetHeight*.05)+document.documentElement.scrollTop)+"px";
		  ifr.style.left = '5%';
		  ifr.style.zIndex = '10000000';
		  ifr.style.background = 'none';
		  ifr.style.display = 'block';
		  ifr.style.visibility = 'visible';
		  ifr.style.backgroundColor = 'transparent';

		overlay.style.filter = "alpha(opacity:0)";
		
	  } else {
      	  overlay.setAttribute('style', 'position:fixed;top:0;left:0;width:100%;height:100%;z-index:9999999;color:#fff;background-color:#000;');	  

//      	  loader.setAttribute('style', 'position:fixed;top:50%;left:50%;width:75px;height:75px;margin:-37px 0 0 -37px;z-index:9999999;');

      	  close_button.setAttribute('style', 'position:fixed;top:30px;right:6%;z-index:10000001;margin:3px 30px 0 0;font-size:9px;border:1px solid #9EB847;padding:0;background:0;height:15px;width:15px;');	  

      	  ifr.setAttribute('id', 'metamag_frame');
      	  ifr.setAttribute('name', 'magframe');
          ifr.setAttribute('style', 'width:90%;height:220px;border:0;position:fixed;top:20px;left:5%;z-index:10000000;visibility:visible;display:block;');	  

        overlay.style.opacity = 0;	

	  }


  document.body.appendChild(overlay);
//  document.body.appendChild(loader);
  document.body.appendChild(close_button);
  ifr.src = "http://127.0.0.1/~equinox/pageui/link_dropper.php?URL="+encodeURIComponent(location.href)+"&title="+encodeURIComponent(parent.document.title);
  document.body.appendChild(ifr);

	if ( bVer == 'msie' ) {
	  window.onscroll = function(){ifr.style.top = ((document.documentElement.offsetHeight*.05)+document.documentElement.scrollTop)+"px"; 
	  close_button.style.top = ((document.documentElement.offsetHeight*.06)+document.documentElement.scrollTop)+"px"; 
	  overlay.style.top = document.documentElement.scrollTop+"px"; 
//	  loader.style.top = ((document.documentElement.offsetHeight*.5)+document.documentElement.scrollTop)+"px";}
	}
	
  timedFade("greybase");

}


function closeFrame(){
  var ifr = document.getElementById('metamag_frame');
  var close_button = document.getElementById('close_button');
  var overlay = document.getElementById('greybase');
//  var loader = document.getElementById('loader');
  ifr.parentNode.removeChild(ifr);
  close_button.parentNode.removeChild(close_button);
  overlay.parentNode.removeChild(overlay);
//  loader.parentNode.removeChild(loader);
}

if(document.getElementsByTagName('frameset').length > 0 ){
  alert("We're sorry.\nPageUI doesn't work on \npages that use frames.\nPlease add the link manually.");
}else if(!document.getElementById('metamag_frame')){
  addFrame();
}else{
  closeFrame();
}
