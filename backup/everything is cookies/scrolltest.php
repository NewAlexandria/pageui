<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Scrolly</title>
	<meta name="generator" content="BBEdit 9.0" />
	<link rel="stylesheet" rev="stylesheet" href="beta.css" />
	<script src="jquery-1.2.5.pack.js"></script>
	<script src="jquery-ui-personalized-1.5.2.min.js"></script>

</head>
<body>


<p>an opening </p>


<p>sections </p>
<div onclick="gos(120)">sections </div>

<p>sections </p>

<p>sections </p>

<p>sections </p>

<p>sections </p>

<p>sections </p>

<p>sections </p>

<p>sections </p>

<p>sections </p>

<p>sections </p>

<p>sections </p>

<p>sections </p>

<p>sections </p>

<p>sections </p>

<p>sections </p>

<p>sections </p>


<p>sections </p>

<p>sections </p>

<p>sections </p>

<p>sections </p>

<p>sections </p>

<p>sections </p>

<p>sections </p>



<script type="text/javascript" language="javascript">
// <![CDATA[
	$("p").text("sections ra");
	
	$("p").bind("click", function(e){
      var str = $('html,body').scrollTop();
      $("p").text("scroll at: " + str);
      
      alert(window.pageYOffset);
alert(document.body.scrollTop);
st = $('html,body').scrollTop(); // jQuery call
alert(st);

    });	
    
    function gos(v) {
    	$('html,body').scrollTop(v);
    }
// ]]>
</script>


</body>
</html>
