<?php
/**
 * @version		$Id: default.php 2011-07-21 15:24:18Z $
 * @package		BlastChat Client
 * @author 		BlastChat
 * @copyright	Copyright (C) 2004-2010 BlastChat. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @HomePage 	<http://www.blastchat.com>

 * This file is part of BlastChat Client.

 * BlastChat Client is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * BlastChat Client is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with BlastChat Client.  If not, see <http://www.gnu.org/licenses/>.
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
//$params_component = &JComponentHelper::getParams( 'com_blastchatc' );
//print_r($params_component);

$app = &JFactory::getApplication('site');
$website = &$app->getParams("com_blastchatc");

/* disable cache */
$cache = & JFactory::getCache();
$cache->setCaching(false); // make sure cache is off

$document =& JFactory::getDocument();

if (!file_exists(JPATH_ROOT.DS.'components'.DS.'com_blastchatc'.DS.'api.blastchatc.php')) {
	echo "Missing file \"api.blastchatc.php\"";
	return;
}
require_once(JPATH_ROOT.DS.'components'.DS.'com_blastchatc'.DS.'api.blastchatc.php');
bc_sendHeaders($document);

/* check - server to server communication for public access
*  keepsession - keep user session active with your website
*  signoff - chatter logout/signoff from chat
*  return null (stop precessing this file)
 */
$bc_task = htmlspecialchars(JRequest::getString('bc_task', null), ENT_QUOTES, "UTF-8");
if ($bc_task) {
	switch ($bc_task) {
		case 'updatelist':
			if (file_exists(JPATH_ROOT.DS.'modules'.DS.'mod_blastchatwhoisonline'.DS.'mod_blastchatwhoisonline_api.php')) {
				require_once(JPATH_ROOT.DS.'modules'.DS.'mod_blastchatwhoisonline'.DS.'mod_blastchatwhoisonline_api.php');
			} else {
				echo "BlastChat WhoIsOnline dynamic support not available.";
			}
			$module = null;
			$module = bc_loadModule();
			if ($module) {
				$params = new JParameter( $module->params );
				$output = bc_loadModuleOutput($params, $module, true);
				echo "allowed".$output;
			}
			break;
		case 'keepsession':
			//this is to keep user's session alive with your website
			//if (bc_userUpdate()) {
				echo "[{\"success\":\"true\"}]";
			//} else {
				//echo "[{\"success\":\"false\"}]";
			//}
			break;
		case 'check':
			//check access to blastchat client being public
			echo "[{\"success\":\"true\"}]";
			break;
		case 'banner':
			require_once('components/com_blastchatc/banner.blastchatc.php');
			bc_loadBanner();
			break;
		case 'signoff':
			//user signedoff from chat, mark him as logged off
			$mainframe =& JFactory::getApplication();
			$mainframe->setUserState( "bc_isrunningd", 0 );
			bc_userSignOff();
			break;
		case 'signon':
			$mainframe =& JFactory::getApplication();
			$mainframe->getUserStateFromRequest( "bc_isrunningd", 'isrund', 0 );
			break;
	}
	return;
}

//require_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_blastchatc'.DS.'class.blastchatc.php');
//$lang = & JFactory::getLanguage();
//$backward_lang = $lang->getBackwardLang();

// Get the languages file if it exists
//if (file_exists(JPATH_ROOT.DS.'components'.DS.'com_blastchatc'.DS.'languages'.DS.$lang->getTag().'.php')) {
	//include_once(JPATH_ROOT.DS.'components'.DS.'com_blastchatc'.DS.'languages'.DS.$lang->getTag().'.php');
//}
//if (file_exists(JPATH_ROOT.DS.'components'.DS.'com_blastchatc'.DS.'languages'.DS.$backward_lang.'.php')) {
	//include_once(JPATH_ROOT.DS.'components'.DS.'com_blastchatc'.DS.'languages'.DS.$backward_lang.'.php');
//}
//if ($backward_lang != 'english' && file_exists(JPATH_ROOT.DS.'components'.DS.'com_blastchatc'.DS.'languages'.DS.'english.php')) {
	//include_once(JPATH_ROOT.DS.'components'.DS.'com_blastchatc'.DS.'languages'.DS.'english.php');
//}

$mosConfig_live_site = bc_getLiveSite();
//strip http or https from this website URL
//if you need this to be something else, adjust bc_getLiveSite function in api.blastchatc.php file
$bc_site = $mosConfig_live_site;
$bc_site = strtolower($bc_site);
$bc_site = str_replace("http://", "", $bc_site);
$bc_site = str_replace("https://", "", $bc_site);

$bc_site_other = "";
if (strpos($bc_site, "www.") === false) {
	$bc_site_other = "www." . $bc_site;
} else {
	$bc_site_other = str_replace("www.", "", $bc_site);
}

$bc_version = bc_getVersion();

$myss = bc_getSessionData();

$detachme = JRequest::getInt('dt', 2); //overwrite admin backend configuration to open chat as detached or undetached
$isdetached = JRequest::getInt('isd', 0); //overwrite admin backend configuration to open chat as detached or undetached
$loadchat = JRequest::getInt('loadchat', 0); //overwrite admin backend configuration to open chat as detached or undetached
$loadchaterror = JRequest::getInt('bcerr', 0); //overwrite admin backend configuration to open chat as detached or undetached

$detached = JRequest::getInt('dt', 2); //overwrite admin backend configuration to open chat as detached or undetached

$interface = $website->get("intf");
$interface = htmlspecialchars(JRequest::getString('if', $interface), ENT_QUOTES, "UTF-8"); //call chat to load special interface vi - visually impaired, md - mobile device
if ($interface != '' && $interface != 'vi' && $interface != 'md') {
	$interface = '';
}

$rid = $website->get("rid");
$rid = htmlspecialchars(JRequest::getString('rid', ($rid && $rid != "0" ? $rid : '0')), ENT_QUOTES, "UTF-8");
$bc_Itemid = JRequest::getInt('Itemid', null);

$db =& JFactory::getDBO();

//getBlastChat data of your website from blastchatc table
//$website = null;
//$website = new josBC_website($db);
//$website->loadByURL( $bc_site );
//if (!$website->url) {
	//$website->loadByURL( $bc_site_other );
	//if ($website->url) {
		//$bc_site = $bc_site_other;
	//}
//}
if (!$website || !$website->get("url") || !$website->get("privkey")) {
	//if there is no information stored for your website in blastchatc table, or some is missing
	echo "Error 0002 : ".JText::_("COM_BLASTCHATC_BLASTCHATC_CONTACTWEBMASTER")."<br>";
	echo "Register your website - ".$bc_site." or ".$bc_site_other;
	return;
}

$bctype = $website->get("type");
$bctype = htmlspecialchars(JRequest::getString('bctype', $bctype ? $bctype : 'chat'), ENT_QUOTES, "UTF-8"); //call chat to load special interface vi - visually impaired, md - mobile device
if ($bctype != 'chat' && $bctype != 'shout') {
	$bctype = 'chat';
}

if (!$loadchat && $detachme == 2) {
	//overwrite not requested, load admin backend configuration for detached feature
	$detachme = $website->get("detached") == "1" && $website->get("type") != 'shout' ? 1 : 0;
}

if ($loadchaterror == 1) {
		$loadchaturl = "index.php?option=com_blastchatc&tmpl=component&dt=0&loadchat=1";
		if ($interface == 'md') {
			$loadchaturl .= "&if=md";
		}
		if ($website->get("type") == 'shout') {
			$loadchaturl .= "&bctype=shout";
		}
		
		$errordiv = "<div style='text-align: center;'>";
		echo $errordiv;
		echo JText::_("COM_BLASTCHATC_BLASTCHATC_ANOTHERINSTANCE") . "<br>" . sprintf(JText::_("COM_BLASTCHATC_BLASTCHATC_ANOTHERINSTANCEWRONG"), '<a href="'.$loadchaturl.'">'.JText::_("COM_BLASTCHATC_BLASTCHATC_ANOTHERINSTANCEWRONG_HERE").'</a>');
		echo "</div>";
	return;
} else if ($loadchaterror == 2) {
		$loadchaturl = "index.php?option=com_blastchatc&tmpl=component&dt=0&loadchat=1";
		$errordiv = "<div style='text-align: center;'>";
		echo $errordiv;
		echo JText::_("COM_BLASTCHATC_BLASTCHATC_ERROR_NOPOPUP1") . "<br><a href=\"".$loadchaturl."\">".JText::_("COM_BLASTCHATC_BLASTCHATC_OPENUNDETACHED")."</a>";
		echo "</div>";
	return;
}

//get sec_code from blastchatc_users table for this user
$sec_code = '';
$bc_groupid = 0;
if (isset($myss)) {
	//bc_userSignIn($myss);
} else {
	echo "Session variable not defined by system, adjust file api.blastchatc.php, function bc_getSessionData().";
	exit;
}

//GMT time for authentication purposes
$time_key = gmdate('Y-m-d H:i:s');

if (!$loadchat && $detached == 2) {
	//overwrite not requested, load admin backend configuration for detached feature
	$detached = $website->get("detached") == "1" && $website->get("type") != 'shout' ? 1 : 0;
}

$bc_template = bc_getCurrentTemplate();
$pub_key = md5( $time_key.$website->get("privkey") ); //this will be recreated upon connection on blastchat server side using time_key and blastchat stored privkey for your website, secutiry feature
$sec_code = md5( $pub_key.$myss->username.$myss->userid ); //this will be recreated upon connection on blastchat server side
$avatar = bc_getAvatarPath($myss);
$gender = bc_getGender($myss);

//Create request for connection to blastchat server (iframe source)
$request = "http://www.blastchat.com/index2.php?option=com_blastchat"
."&task=client" //variable for internal blastchat use
."&ctask=".$website->get("type") //variable for internal blastchat use
."&if=".$interface //variable for internal blastchat use
."&dt=".(($detached || $isdetached || $interface == 'md') ? 1 : 0) //detached window
."&iid=".$website->get("intraid")//unique identifier that will be used to identify your website
."&pk=".$pub_key
."&tk=".$time_key //used in public key generation, send for authentication purposes
."&sc=".$sec_code
."&uid=".$myss->userid //local userid of the user connecting to blastchat, if 0 user is considered a guest
."&ugid=".$bc_groupid //local user group id of the user connecting to blastchat, if 0 user has no goup assigned (currently only single group support)
."&ug=".$gender //user's gender
."&nick=".urlencode($myss->username) //local username of the user connecting to blastchat, if empty user is considered a guest, urlencode for foreign characters (correction can be done in admin area of blastchat configuration)
."&rid=".$rid //force to go directly into this room id (you can find room id in chat admin area
."&bcid=".($bc_Itemid ? $bc_Itemid : "") //current value of Itemid
."&bcv=3.6" //BlastChat Client version
."&avt=".urlencode($avatar) //URI path to avatar image from user profile
."&p=".$bc_version->PRODUCT
."&r=".$bc_version->RELEASE
."&d=".$bc_version->DEV_LEVEL
;
if (! $website) {
	$document->setTitle("BlastChat @ ".$website->get("url"));
}
$document->addScript(JURI::base()."components/com_blastchatc/js/common.js?v=3.6");

$errorwidth = $website->get("width");
if (strpos($website->get("width"), "%") === false) {
	$errorwidth = $website->get("width") . "px";
}
$errorheight = $website->get("height");
if (strpos($website->get("height"), "%") === false) {
	$errorheight = $website->get("height") . "px";
}
$errordiv = "<div style='text-align: center; padding-bottom: 10px; width: $errorwidth; height: $errorheight;'>";
$errordiv_d = "<div style='text-align: center; padding-bottom: 10px;'>";
$mainframe =& JFactory::getApplication();
$isrunning = intval($mainframe->getUserStateFromRequest( "bc_isrunningd", "isrund", 0 ));
$u =& JURI::getInstance();

$framename = "blastchatc";
if ($website->get("type") == 'chat') {
	$framename = "blastchatc";
}

if (!$loadchat) {
	$loadchaturl = $request;
	if ($detachme) {
		$loadchaturl = "index.php?option=com_blastchatc&tmpl=component&dt=".$detachme."&loadchat=1&bctype=".$website->get("type");
	}
	$loadchaturlerror = "index.php?option=com_blastchatc&tmpl=component&dt=".$detachme."&loadchat=1&bcerr=1&bctype=".$website->get("type");
	if ($interface == 'md') {
		//$mainframe->setUserState( "bc_isrunningd", 1 );
	?>
		<script type="text/javascript">
		<!--
			document.location = "<?php echo $isrunning ? $loadchaturlerror.'&dt=0&if=md' : $request;?>";
		//-->
		</script>
		<?php
	} else {
		?>
		<script type="text/javascript">
		<!--
			var bc_title = "BlastChat";
			var bc_windowsrc = "<?php echo $isrunning ? "" : $loadchaturl;?>";
			var bc_windowsrcerror = "<?php echo $loadchaturlerror;?>";
			if ( typeof( blastchatc_var ) != 'undefined' )  {
				//there is another one already loaded on this page
				bc_windowsrc = "";
			}
			var blastchatc_var = true;
		//-->
		</script>
		<iframe NAME="<?php echo $framename;?>" ID="<?php echo $framename;?>" SRC="" HEIGHT="<?php echo $website->get("height");?>" WIDTH="<?php echo $website->get("width");?>" FRAMEBORDER="<?php echo $website->get("frame_border") == 1 ? "1" : "0";?>" marginwidth="<?php echo $website->get("mwidth");?>" marginheight="<?php echo $website->get("mheight");?>" SCROLLING="NO">
		</iframe>
		<script type="text/javascript">
			<!--
			if (bc_windowsrc != "") {
				window.frames["<?php echo $framename;?>"].location = bc_windowsrc;
			} else {
				window.frames["<?php echo $framename;?>"].location = bc_windowsrcerror;
			}
			//-->
		</script>
<!-- !!! Do not remove, tamper with, obstruct visibility or obstruct readability of following code unless you have received written permission to do so by owner of BlastChat !!! -->
<div align="center" style="width:100%; font-size: 10px; text-align:center; margin: 5px 0px 0px 0px; padding: 0px 0px 0px 0px;">Powered by <a href="http://www.blastchat.com" target="_blank" title="BlastChat - free chat for your website">BlastChat</a></div>
		<?php
	}
	return;
} else {
	if ($isdetached) {
		$mainframe->setUserState( "bc_isrunningd", 1 );
	} else {
		$mainframe->setUserState( "bc_isrunningd", 0 );
	}
	if ($detachme == 1) {
		$loadchaturl = $request; //"index.php?option=com_blastchatc&tmpl=component&isd=1&loadchat=1";
		$loadchaturlerror1 = "index.php?option=com_blastchatc&tmpl=component&isd=1&bcerr=1";
		$loadchaturlerror2 = "index.php?option=com_blastchatc&tmpl=component&isd=1&bcerr=2";
		//spawn new window and reload document.location to point to message informing about pop-up blocker and give option to load chat in place
?>
		<script type="text/javascript">
		<!--
			var bc_title = "BlastChat";
			var bc_windowsrc = "<?php echo $isrunning ? "" : $loadchaturl;?>";
			var bc_windowsrcerror1 = "<?php echo $loadchaturlerror1;?>";
			var bc_windowsrcerror2 = "<?php echo $loadchaturlerror2;?>";
			if ( typeof( blastchatc_var ) != 'undefined' )  {
				//there is another one already loaded on this page
				bc_windowsrc = "";
			}
			var blastchatc_var = true;
		//-->
		</script>
		<script type="text/javascript">
		<!--
		var bc_window = window.open('',bc_title,"WIDTH=<?php echo $website->get('dwidth');?>, HEIGHT=<?php echo $website->get('dheight');?>, location=no, menubar=no, status=no, toolbar=no, scrollbars=no, resizable=yes");
		if (bc_window) {
			if (bc_window.location.href.indexOf("blastchat.com") < 0) {
				if (bc_windowsrc != "") {
					bc_window.document.location = bc_windowsrc;
					document.location = bc_windowsrcerror2;
				} else {
					bc_window.document.location = bc_windowsrcerror1;
				}
			}
		}
		//-->
		</script>
<?php		
	} else {
		//prepare $request and reload document.location
		?>
		<script type="text/javascript">
			<!--
			document.location = "<?php echo $request;?>";
			//-->
		</script>		
		<?php
	}
}