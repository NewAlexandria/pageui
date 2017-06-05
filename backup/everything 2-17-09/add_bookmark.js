// ma.gnolia.com Roots

javascript:(function(){var%20s=document.createElement(%22script%22);s.charset=%22UTF-8%22;s.src=%22http://127.0.0.1/~equinox/pageui/link_dropper.js%22;document.body.appendChild(s)})();

// see Ma.gnolia code below


// bookmarket that opens a new window

javascript:(function(){f='http://127.0.0.1/~equinox/pageui/config/links.php?proc=add&URL='+encodeURIComponent(window.location.href)+'&group_id=1&title='+encodeURIComponent(document.title)+'&v=5&';a=function(){if(!window.open(f+'noui=1&jump=doclose','deliciousuiv5','location=yes,links=no,scrollbars=no,toolbar=no,width=550,height=550'))location.href=f+'jump=yes'};if(/Firefox/.test(navigator.userAgent)){setTimeout(a,0)}else{a()}})()



// bookmarket that calls a javascript 
/*
Failed completely on IE 6.0. Worked perfectly on Firefox.
# posted by ÊCavorticus : 11:14 AM
To get it working in IE, I found that you need to wrap the last statement in a void();

void(document.body.appendChild(a));

It now works for me in IE 6 and 7.
*/
javascript:function loadScript(scriptURL) { var scriptElem = document.createElement('SCRIPT'); scriptElem.setAttribute('language', 'JavaScript'); scriptElem.setAttribute('src', scriptURL); document.body.appendChild(scriptElem);} loadScript('http://127.0.0.1/~equinox/pageui/link_dropper.js');


// biiger bookmarklet including request object
javascript:var pageRequest=false; if (!pageRequest &&;20typeof XMLHttpRequest!='undefined'); pageRequest=new XMLHttpRequest(); if (pageRequest){pageRequest.open('GET','http://127.0.0.1/~equinox/pageui/link_dropper.js',false); pageRequest.send(null); alert('aa'+pageRequest.responseText);}



// google's code
javascript:(function(){var a=window,b=document,c=encodeURIComponent,d=a.open("http://127.0.0.1/~equinox/pageui/link_dropper.php?op=edit&output=popup&URL="+c(b.location)+"&title="+c(b.title),"pageui_popup","left="+((a.screenX||a.screenLeft)+10)+",top="+((a.screenY||a.screenTop)+10)+",height=420px,width=450px,resizable=1,alwaysRaised=1");a.setTimeout(function(){d.focus()},300)})();

javascript:(function(){var%20a=window,b=document,c=encodeURIComponent,d=a.open(%22http://www.pageui.com/link_dropper.php?op=edit&output=popup&URL=%22+c(b.location)+%22&title=%22+c(b.title),%22pageui_popup%22,%22left=%22+((a.screenX||a.screenLeft)+10)+%22,top=%22+((a.screenY||a.screenTop)+10)+%22,height=420px,width=450px,resizable=1,alwaysRaised=1%22);a.setTimeout(function(){d.focus()},300)})();



// create a pop with an element inserted
javascript:(function(){var element=document.createElement('script'); element.setAttribute('src','http://weblog.infoworld.com/udell/gems/quote.js'); document.body.appendChild(element); })()

/* 
*  Copyright 2006-2007 Dynamic Site Solutions.
*  Free use of this script is permitted for non-commercial applications,
*  subject to the requirement that this comment block be kept and not be
*  altered.  The data and executable parts of the script may be changed
*  as needed.  Dynamic Site Solutions makes no warranty regarding fitness
*  of use or correct function of the script.  Terms for use of this script
*  in commercial applications may be negotiated; for this, or for other
*  questions, contact "license-info@dynamicsitesolutions.com".
*
*  Script by: Dynamic Site Solutions -- http://www.dynamicsitesolutions.com/
*  Last Updated: 2007-06-17
*/

//IE5+/Win, Firefox, Netscape 6+, Opera 7+, Safari, Konqueror 3, IE5/Mac, iCab 3

var addBookmarkObj = {
  linkText:'Bookmark This Page',
  addTextLink:function(parId){
    var a=addBookmarkObj.makeLink(parId);
    if(!a) return;
    a.appendChild(document.createTextNode(addBookmarkObj.linkText));
  },
  addImageLink:function(parId,imgPath){
    if(!imgPath || isEmpty(imgPath)) return;
    var a=addBookmarkObj.makeLink(parId);
    if(!a) return;
    var img = document.createElement('img');
    img.title = img.alt = addBookmarkObj.linkText;
    img.src = imgPath;
    a.appendChild(img);
  },
  makeLink:function(parId) {
    if(!document.getElementById || !document.createTextNode) return null;
    parId=((typeof(parId)=='string')&&!isEmpty(parId))
      ?parId:'addBookmarkContainer';
    var cont=document.getElementById(parId);
    if(!cont) return null;
    var a=document.createElement('a');
    a.href=location.href;
    if(window.opera) {
      a.rel='sidebar'; // this makes it work in Opera 7+
    } else {
      // this doesn't work in Opera 7+ if the link has an onclick handler,
      // so we only add it if the browser isn't Opera.
      a.onclick=function() {
        addBookmarkObj.exec(this.href,this.title);
        return false;
      }
    }
    a.title=document.title;
    return cont.appendChild(a);
  },
  exec:function(url, title) {
    // user agent sniffing is bad in general, but this is one of the times 
    // when it's really necessary
    var ua=navigator.userAgent.toLowerCase();
    var isKonq=(ua.indexOf('konqueror')!=-1);
    var isSafari=(ua.indexOf('webkit')!=-1);
    var isMac=(ua.indexOf('mac')!=-1);
    var buttonStr=isMac?'Command/Cmd':'CTRL';

    if(window.external && (!document.createTextNode ||
      (typeof(window.external.AddFavorite)=='unknown'))) {
        // IE4/Win generates an error when you
        // execute "typeof(window.external.AddFavorite)"
        // In IE7 the page must be from a web server, not directly from a local 
        // file system, otherwise, you will get a permission denied error.
        window.external.AddFavorite(url, title); // IE/Win
    } else if(isKonq) {
      alert('You need to press CTRL + B to bookmark our site.');
    } else if(window.opera) {
      void(0); // do nothing here (Opera 7+)
    } else if(window.home || isSafari) { // Firefox, Netscape, Safari, iCab
      alert('You need to press '+buttonStr+' + D to bookmark our site.');
    } else if(!window.print || isMac) { // IE5/Mac and Safari 1.0
      alert('You need to press Command/Cmd + D to bookmark our site.');    
    } else {
      alert('In order to bookmark this site you need to do so manually '+
        'through your browser.');
    }
  }
}

function isEmpty(s){return ((s=='')||/^\s*$/.test(s));}

function dss_addEvent(el,etype,fn) {
  if(el.addEventListener && (!window.opera || opera.version) &&
  (etype!='load')) {
    el.addEventListener(etype,fn,false);
  } else if(el.attachEvent) {
    el.attachEvent('on'+etype,fn);
  } else {
    if(typeof(fn) != "function") return;
    if(typeof(window.earlyNS4)=='undefined') {
      // to prevent this function from crashing Netscape versions before 4.02
      window.earlyNS4=((navigator.appName.toLowerCase()=='netscape')&&
      (parseFloat(navigator.appVersion)<4.02)&&document.layers);
    }
    if((typeof(el['on'+etype])=="function")&&!window.earlyNS4) {
      var tempFunc = el['on'+etype];
      el['on'+etype]=function(e){
        var a=tempFunc(e),b=fn(e);
        a=(typeof(a)=='undefined')?true:a;
        b=(typeof(b)=='undefined')?true:b;
        return (a&&b);
      }
    } else {
      el['on'+etype]=fn;
    }
  }
}

dss_addEvent(window,'load',addBookmarkObj.addTextLink);

// to make multiple links, do something like this:
/*
dss_addEvent(window,'load',function(){
  var f=addBookmarkObj.addTextLink;
  f();
  f('otherContainerID');
});
*/

// below is an example of how to make an image link with this
// the first parameter is the ID. If you pass an empty string it defaults to
// 'addBookmarkContainer'.
/*
dss_addEvent(window,'load',function(){
  addBookmarkObj.addImageLink('','/images/add-bookmark.jpg');
});
*/


////  Ma.gnolia code

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

    	function addFrame(){
    	  var overlay = document.createElement('div');
          overlay.id = "greybase";
      	  overlay.setAttribute('style', 'position:fixed;top:0;left:0;width:100%;height:100%;z-index:9999999;color:#fff;background-color:#000;');
      	var loader = document.createElement('div');
          loader.id = "loader";
      	  loader.setAttribute('style', 'position:fixed;top:50%;left:50%;width:75px;height:75px;margin:-37px 0 0 -37px;z-index:9999999;');
          loader.innerHTML = '<img src="http://ma.gnolia.com/images/metamag/loading_icon.gif" />';
        var close_button = document.createElement('a');
          close_button.onclick = closeFrame;
          close_button.innerHTML = '<img src="http://ma.gnolia.com/images/metamag/close.gif" alt="close" title="close" style="border:0;padding:0;background:none;margin:0;"/>';
          close_button.href = "javascript:void(0);";    
          close_button.id = "close_button";
      	  close_button.setAttribute('style', 'position:fixed;top:6%;right:6%;z-index:10000001;margin:3px 30px 0 0;font-size:9px;border:1px solid #9EB847;padding:0;background:0;height:15px;width:15px;');
      	var ifr = document.createElement('iframe');
      	  ifr.setAttribute('id', 'metamag_frame');
      	  ifr.setAttribute('name', 'magframe');
          ifr.setAttribute('style', 'width:90%;height:90%;border:0;position:fixed;top:5%;left:5%;z-index:10000000;visibility:visible;display:block;');

        overlay.style.opacity = 0;

      	document.body.appendChild(overlay);
      	document.body.appendChild(loader);
        document.body.appendChild(close_button);
      	document.body.appendChild(ifr);
      	ifr.src = "http://ma.gnolia.com/meta/get?url="+encodeURIComponent(location.href)+"&title="+encodeURIComponent(parent.document.title);
      	timedFade("greybase");
      }
      function closeFrame(){
      	var ifr = document.getElementById('metamag_frame');
      	var close_button = document.getElementById('close_button');
      	var overlay = document.getElementById('greybase');
      	var loader = document.getElementById('loader');
      	ifr.parentNode.removeChild(ifr);
      	close_button.parentNode.removeChild(close_button);
      	overlay.parentNode.removeChild(overlay);
      	loader.parentNode.removeChild(loader);
      }
      if(document.getElementsByTagName('frameset').length > 0 ){
        alert("We're sorry.\nMa.gnolia Roots doesn't work\non pages that use frames.");
      }else if(!document.getElementById('metamag_frame')){
        addFrame();
      }else{
      	closeFrame();
      }