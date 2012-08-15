<?php



?>


	var strCache = new Array();

	var lastStr;
	var trip_count = 0;

	function getScrollOffest() {

		var x,y;
		if (self.pageYOffset) // all except Explorer
		{
			x = self.pageXOffset;
			y = self.pageYOffset;
		}
		else if (document.documentElement && document.documentElement.scrollTop)
			// Explorer 6 Strict
		{
			x = document.documentElement.scrollLeft;
			y = document.documentElement.scrollTop;
		}
		else if (document.body) // all other Explorers
		{
			x = document.body.scrollLeft;
			y = document.body.scrollTop;
		}

		var pos = new Array();
		pos['x'] = x;
		pos['y'] = y;
		return pos;

	}

	function sB(e, str, link, aid) {

		var bubble = document.getElementById('bubble');
		var style = bubble.style
		style.visibility='visible';
		
		var offset = getScrollOffest(); // how much the window scrolled?

		style.top=e.clientY+10+offset['y']
		style.left=e.clientX+10+offset['x']
		document.getElementById('content').innerHTML=str;

		fillAdContent(aid, document.getElementById('content'));

	}

	function hI() {

		var bubble = document.getElementById('bubble');
		var style = bubble.style
		style.visibility='hidden';

	}

	function hideBubble(e) {

			//var bubble = document.getElementById('bubble');
			//style = bubble.style;
			

	}


	function fillAdContent(aid, bubble) {

		if (!isBrowserCompatible()) {
			return false;
		}

		// is the content cached?
		if (strCache[aid])
		{
			bubble.innerHTML = strCache[aid];
			return true;
		}

		//////////////////////////////////////////////////
		// AJAX Magic.
		//////////////////////////////////////////////////

		var xmlhttp=false;
		/*@cc_on @*/
		/*@if (@_jscript_version >= 5)
		// JScript gives us Conditional compilation, we can cope with old IE versions.
		// and security blocked creation of the objects.
		 try {
		  xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		 } catch (e) {
		  try {
		   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		  } catch (E) {
		   xmlhttp = false;
		  }
		 }
		@end @*/
		if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		  xmlhttp = new XMLHttpRequest();
		}

		

		xmlhttp.open("GET", "<?php echo BASE_HTTP_PATH;?>ga.php?AID="+aid+"<?php 
		
		echo "&t=".time(); ?>", true);

		//alert("before trup_count:"+trip_count);

		if (trip_count != 0){ // trip_count: global variable counts how many times it goes to the server
			// waiting state...

			return;
			
		}
		trip_count++;

		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4) {
				//

				
				//alert(xmlhttp.responseText);

				strCache[''+aid] = xmlhttp.responseText

				bubble.innerHTML = xmlhttp.responseText;

				trip_count--;

			
			}
			
		}

		xmlhttp.send(null)


	}

	function isBrowserCompatible() {

		// check if we can XMLHttpRequest

		var xmlhttp=false;
		/*@cc_on @*/
		/*@if (@_jscript_version >= 5)
		// JScript gives us Conditional compilation, we can cope with old IE versions.
		// and security blocked creation of the objects.
		 try {
		  xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		 } catch (e) {
		  try {
		   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		  } catch (E) {
		   xmlhttp = false;
		  }
		 }
		@end @*/
		if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		  xmlhttp = new XMLHttpRequest();
		}

		if (!xmlhttp) {
			return false
		}
		return true;

	}

