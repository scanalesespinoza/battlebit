<!--
/**
 * @version		$Id: common.js 2010-01-01 15:24:18Z $
 * @package		BlastChat Client
 * @author 		BlastChat
 * @copyright	Copyright (C) 2004-2010 BlastChat. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @HomePage 	<http://www.blastchat.com>

 * This file is part of BlastChat Client module.

 * BlastChat Client module is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * BlastChat Client module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with BlastChat Client module.  If not, see <http://www.gnu.org/licenses/>.
 */

var bc_httpUpdater = null;
var bc_getURL = null;
var bc_refreshtime = 0;
var bc_iscountdown = 0;
var bc_countdown = 0;
var bc_countdownunit = 1000;
var bc_countdown_type = 0;
var bc_countdown_count = 0;
var bc_counttimetimer = null;

function bc_call(bc_url)
{
	var myRequest = false;
	// For browsers: Safari, Firefox, etc. use one XML model
	if (window.XMLHttpRequest) {
	    myRequest = new XMLHttpRequest();
	    if (myRequest.overrideMimeType) {
	         myRequest.overrideMimeType('text/xml');
	    }
	} else if (window.ActiveXObject) {
	    // For browsers: IE, version 6 and before, use another model
	    try {
	         myRequest = new
	              ActiveXObject("Msxml2.XMLHTTP");
	    } catch (e) {
	         try {
	              myRequest = new
	                   ActiveXObject("Microsoft.XMLHTTP");
	         } catch (e) {}
	    }
	}
	// Make sure the request object is valid
	if (!myRequest) {
	    alert('Error: Cannot create XMLHTTP object');
	    return false;
	}
	// Open the URL request
	myRequest.open('GET', bc_url, true);
	// Send request
	myRequest.send(null);
}

//initiates the XMLHttpRequest object
function bc_getHTTPObject() {
  var xmlhttp;
  /*@cc_on
  @if (@_jscript_version >= 5)
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp = false;
      }
    }
  @else
  xmlhttp = false;
  @end @*/
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    try {
      xmlhttp = new XMLHttpRequest();
    } catch (e) {
      xmlhttp = false;
    }
  }
  return xmlhttp;
}

document.getElementsByClassName = function (cl) {
	var retnode = [];
	var myclass = new RegExp('\\b' + cl + '\\b');
	var elem = this.getElementsByTagName('*');
	for (var i = 0; i < elem.length; i ++) {
		var classes = elem[i].className;
		if (myclass.test(classes)) {
			retnode.push(elem[i]);
		}
	}
	return retnode;
}

function bc_directd(dwidth, dheight) {
	var	bc_title = "BlastChat";
	var bc_windowsrc = "index.php?option=com_blastchatc&tmpl=component&isd=1&loadchat=1";
	if ( typeof( blastchatc_var ) != 'undefined' )  {
		//there is another one already loaded on this page
		bc_windowsrc = "index.php?option=com_blastchatc&tmpl=component&isd=1&loadchat=1&bcerr=1";
	}
	var blastchatc_var = true;
	
	var bc_window = window.open('',bc_title,"WIDTH="+dwidth+", HEIGHT="+dheight+", location=no, menubar=no, status=no, toolbar=no, scrollbars=no, resizable=yes");
	if (bc_window && bc_window.location.href.indexOf('blastchat.com') == -1) {
		bc_window.location = bc_windowsrc;
	} else if (bc_window) {
		bc_window.focus();
	}
}

function bc_writeit(text,objId) {
	//alert(text);
	if (document.layers) { //Netscape 4
		myObj = eval('document.' + objId);
		myObj.document.open();
		myObj.document.write(text);
		myObj.document.close();
	} else 	if ((document.all && !document.getElementById) || navigator.userAgent.indexOf("Opera") != -1) { //IE 4 & Opera
		myObj = eval('document.all.' + objId);
		myObj.innerHTML = text;
	} else if (document.getElementById) { //Netscape 6 & IE 5
		myObj = document.getElementById(objId);
		myObj.innerHTML = text;
	} else {
		alert('This website uses DHTML. We recommend you upgrade your browser.');
	}
}

//initiates the first data query
function bc_updateList() {
	clearTimeout(bc_counttimetimer);
	if (bc_httpUpdater.readyState == 4 || bc_httpUpdater.readyState == 0) {
		var t = new Date();
		bc_httpUpdater.open("GET", bc_getURL + "&t=" + t.getTime(), true);
		bc_httpUpdater.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		bc_httpUpdater.onreadystatechange = bc_handlehttpUpdater;
		bc_httpUpdater.send('');
	}
}

//deals with the servers reply to requesting new content
function bc_handlehttpUpdater() {
	if (bc_httpUpdater.readyState == 4) {
		if (bc_httpUpdater.responseText.substr(0, 7) == 'allowed') {
			var newcontent = bc_httpUpdater.responseText.substr(7, bc_httpUpdater.responseText.length);
			var results = newcontent.split('|||');
			
			var gcount = parseInt(results[0]);
			var mcount = parseInt(results[1]);
			var gccount = parseInt(results[2]);
			var mccount = parseInt(results[3]);
			if (gcount > -1) {
				bc_writeit(gcount, "bc_module_guest_count");
				bc_writeit(gcount, "bc_module_guests_count");
			}
			if (mcount > -1) {
				bc_writeit(mcount, "bc_module_member_count");
				bc_writeit(mcount, "bc_module_members_count");
			}
			if (gccount > -1) {
				bc_writeit(gccount, "bc_module_chatguest_count");
				bc_writeit(gccount, "bc_module_chatguests_count");
			}
			if (mccount > -1) {
				bc_writeit(mccount, "bc_module_chatmember_count");
				bc_writeit(mccount, "bc_module_chatmembers_count");
			}
			
			if (gcount > 0 || mcount > 0) {
				document.getElementById("bc_module_online").style.display = "block";
				if (gcount == 0 || gcount > 1) {
					document.getElementById("bc_module_online_guest").style.display = "none";
					document.getElementById("bc_module_online_guests").style.display = "inline";
				} else {
					document.getElementById("bc_module_online_guest").style.display = "inline";
					document.getElementById("bc_module_online_guests").style.display = "none";
				}
				if (mcount == 0 || mcount > 1) {
					document.getElementById("bc_module_online_member").style.display = "none";
					document.getElementById("bc_module_online_members").style.display = "inline";
				} else {
					document.getElementById("bc_module_online_member").style.display = "inline";
					document.getElementById("bc_module_online_members").style.display = "none";
				}
				if (mcount == 0) {
					document.getElementById("bc_module_and").style.display = "none";
				} else {
					document.getElementById("bc_module_and").style.display = "inline";
				}
			} else {
				document.getElementById("bc_module_online").style.display = "none";
			}
			if (gccount > 0 || mccount > 0) {
				document.getElementById("bc_module_chatting").style.display = "block";
				if (gccount == 0 || gccount > 1) {
					document.getElementById("bc_module_chatting_guest").style.display = "none";
					document.getElementById("bc_module_chatting_guests").style.display = "inline";
				} else {
					document.getElementById("bc_module_chatting_guest").style.display = "inline";
					document.getElementById("bc_module_chatting_guests").style.display = "none";
				}
				if (mccount == 0 || mccount > 1) {
					document.getElementById("bc_module_chatting_member").style.display = "none";
					document.getElementById("bc_module_chatting_members").style.display = "inline";
				} else {
					document.getElementById("bc_module_chatting_member").style.display = "inline";
					document.getElementById("bc_module_chatting_members").style.display = "none";
				}
				if (mccount == 0) {
					document.getElementById("bc_module_chatand").style.display = "none";
				} else {
					document.getElementById("bc_module_chatand").style.display = "inline";
				}
			} else {
				document.getElementById("bc_module_chatting").style.display = "none";
			}
			bc_writeit(decodeURI(results[4]),'bc_module_names');
			//bc_writeit(results[4],'bc_module');
			
			var tt = document.getElementsByClassName("tool-tip");
			for (var i = 0; i < tt.length; i ++) {
				if (tt[i] && tt[i].parentNode && tt[i].parentNode.removeChild) {
					tt[i].parentNode.removeChild(tt[i]);
				}
			}
			JTooltips = null;
			JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false});
		}
		if (bc_iscountdown > 0) {
			bc_countdown = bc_refreshtime;
			bc_runcountdown();
		} else {
			clearTimeout(bc_counttimetimer);
			bc_counttimetimer = setTimeout("bc_updateList()",bc_refreshtime);
		}
	}
}

function bc_runcountdown() {
	if (bc_countdown < 0) {
		bc_countdown = 0;
	}
		if (bc_countdown_type == 0) {
			//show number
			var shownumber = bc_countdown / 1000;
			if (shownumber <= 0) {
				bc_writeit(String(shownumber), 'bc_module_countdown_present');
				bc_countdown = bc_refreshtime;
				bc_updateList();
			} else {
				bc_writeit(String(shownumber), 'bc_module_countdown_present');
				bc_countdown = bc_countdown - bc_countdownunit;
				clearTimeout(bc_counttimetimer);
				bc_counttimetimer = setTimeout("bc_runcountdown()",bc_countdownunit);
			}
		} else {
			var unit = parseInt(bc_refreshtime / bc_countdown_count);
			var now = bc_refreshtime - bc_countdown;
			var howmany = parseInt(now / unit) + 1;
			var image = '<div class="bc_module_countdown_image bc_module_countdown_image' + howmany + '"></div>';
			try {
				bc_writeit(String(image), 'bc_module_countdown_present');
			} catch (e) {
			}
			if (howmany == bc_countdown_count) {	
				bc_countdown = bc_refreshtime;
				bc_updateList();
			} else {
				bc_countdown = bc_countdown - bc_countdownunit;
				clearTimeout(bc_counttimetimer);
				bc_counttimetimer = setTimeout("bc_runcountdown()",bc_countdownunit);
			}
		}
}

function bc_start(bct, moduleid, countdown, countdown_type, countdown_count) {
		if (bct > 0) {
			bc_iscountdown = countdown;
			bc_refreshtime = bct * bc_countdownunit;
			bc_httpUpdater = bc_getHTTPObject();
			bc_getURL = "index.php?option=com_blastchatc&bc_task=updatelist&tmpl=component&format=raw&mid=" + moduleid;
			clearTimeout(bc_counttimetimer);
			if (bc_iscountdown > 0) {
				bc_countdown = bc_refreshtime;
				bc_countdown_type = countdown_type;
				bc_countdown_count = countdown_count;
				if (countdown_type == 1) {
					bc_countdownunit = parseInt(bc_refreshtime / bc_countdown_count);
				}
				bc_runcountdown();
			} else {
				bc_counttimetimer = setTimeout("bc_updateList()",bc_refreshtime);
			}
		}
}
//-->