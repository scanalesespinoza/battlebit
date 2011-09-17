<?php
/**
 * @version		$Id: api.blastchatc.php 2011-07-21 15:24:18Z $
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

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/*headers to force browser not to use cache
*/
function bc_sendHeaders($document) {
	//Headers are sent to prevent browsers from caching.. IE is still resistent sometimes
	$nowDate = date ( "D, d M Y H:i:s", time() );
	$document->setMetaData("robots", "noindex, nofollow");
	$document->setMetaData("expires", $nowDate." GMT", true);
	$document->setMetaData("cache-control", "no-store, no-cache, must-revalidate", true);
	$document->setMetaData("pragma", "no-store no-chache", true);
	$document->setMetaData("Content-Type", "text/html; charset=UTF-8", true);
}

/*
adjust this function to return proper group id for current user
$uid - current user id
return - current user group id, integer OR ra string in format "1,2,3" in case your system can assign multiple groups to single member
*/
function bc_getUserGroup($uid = 0) {
	$myss = bc_getSessionData();

	$gid = 0;
	if ($uid) {
		/*replace following code with database call to retreive current user group id
		//example
		$db =& JFactory::getDBO();
		$query = "SELECT groupid FROM #__xxx "
		." WHERE userid=$myss->userid";
		$db->setQuery($query);
		$gid = $db->loadResult();
		if (!$gid)
		$gid = 0;

		//or example
		global $my;
		$query = "SELECT gid FROM #__xxx "
		." WHERE userid=$my->id";
		$db->setQuery($query);
		$gid = $db->loadResult();
		if (!$gid)
		$gid = 0;

		//or combine examples as you need
		*/
		$gid = $myss->gid;
	}
	return $gid;
}

/*
adjust this function to return proper current session information for current user
$version - variable hodling information about current CMS
return $myss - variable holding session data
$myss->guest - 1 if user is guest (not member), 1 if user is logged in member of your website
$myss->session_id - unique session identifier for current user
$myss->userid - unique user id if logged in member, if user is guest then this should be set to 0 (zero)
$myss->username - unique username of currently logged in member, set to empty string "" if guest
*/
function bc_getSessionData() {
	$session =& JFactory::getSession();
	$myss =& JTable::getInstance( 'session', 'JTable' );
	$myss->load($session->getId());
	return $myss;
}

/* Function updates current user timestamp for display purposes of the module
* return null
*/
function bc_userUpdate() {
	$db =& JFactory::getDBO();

	$myss = bc_getSessionData();
	$bc_time = time();

	//update upon chat entry
	if ($myss->username && $myss->username == $db->getEscaped($myss->username)) {
		//replace mysql_real_escape_string() with $db->getEscaped()
		$query = "UPDATE #__session "
		." SET bc_lastUpdate='$bc_time' "
		." WHERE username LIKE '".$db->getEscaped($myss->username)."' ";
		;
	} else {
		$query = "UPDATE #__session "
		." SET bc_lastUpdate='$bc_time' "
		." WHERE session_id='$myss->session_id' ";
		;
	}
	$db->setQuery($query);
	$db->query();

	if ($myss->userid) {
		$idle_time = htmlspecialchars(JRequest::getString('idle_time', ''), ENT_QUOTES, "UTF-8");
		$rid = JRequest::getInt('rid', 0); 
		$rsid = JRequest::getInt('rsid', 0); 
		$rname = htmlspecialchars(JRequest::getString('rname', ''), ENT_QUOTES, "UTF-8");

		//update upon chat entry
		$query = "UPDATE #__blastchatc_users "
		." SET bc_rid=$rid "
		." , bc_rsid=$rsid "
		." , bc_rname='".$db->getEscaped($rname)."' "
		." , bc_idle='".$db->getEscaped($idle_time)."' "
		." WHERE bc_userid=$myss->userid ";
		;
		$db->setQuery($query);
		$db->query();
	}
	return true;
}

/*
return gender, 0 - unknown, 1 - male, 2 - female
*/
function bc_getGender($myss) {
	$gender = 0;
	//code required here to retreive gender from different 3rd party extensions
	return $gender;
}

/*
return avatar image file URL as a string in format of http://yourodmain/pathtofile/filename
*/
function bc_getAvatarPath($myss) {
	$db =& JFactory::getDBO();
	$avatar = "";
	/**
	 * Import JomSocial libraries, if it is installed.
	*/
	if( file_exists(JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php') ) {
		require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
	}
	if (class_exists('CFactory')) {
	  $jsUser =& CFactory::getUser();
		$avatar = htmlspecialchars($jsUser->getAvatar(), ENT_QUOTES, "UTF-8");
	}
	/* end of JomSocial integration */
	
	/**
	 * Community Builder integration
	*/
	if (! $avatar) {
		if( file_exists(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_comprofiler' . DS . 'comprofiler.class.php') ) {
			if ($myss->userid) {
				$query = "SELECT avatar FROM #__comprofiler WHERE user_id=".$myss->userid." AND avatarapproved=1";
				$db->setQuery($query);
				$row = $db->loadResult();
				if ($row) {
					$avt_path = bc_getLiveSite()."/images/comprofiler/";
					$avatar = htmlspecialchars($avt_path.$row, ENT_QUOTES, "UTF-8");
				} else {
					//provide default avatar as we did not find avatar defined for user
					if( file_exists(JPATH_ROOT . DS . 'images' . DS . 'comprofiler' . DS . 'nophoto_n.png') ) {
						$avt_path = bc_getLiveSite()."/images/comprofiler/nophoto_n.png";
					} else if( file_exists(JPATH_ROOT . DS . 'components' . DS . 'com_comprofiler' . DS . 'plugin' . DS . 'templates' . DS . 'dark' . DS . 'images' . DS . 'nophoto_n.png') ) {
						$avt_path = bc_getLiveSite()."/components/com_comprofiler/plugin/templates/dark/images/avatar/nophoto_n.png";
					} else if( file_exists(JPATH_ROOT . DS . 'components' . DS . 'com_comprofiler' . DS . 'plugin' . DS . 'templates' . DS . 'light' . DS . 'images' . DS . 'nophoto_n.png') ) {
						$avt_path = bc_getLiveSite()."/components/com_comprofiler/plugin/templates/light/images/avatar/nophoto_n.png";
					}
					$avatar = htmlspecialchars($avt_path, ENT_QUOTES, "UTF-8");
				}
			} else {
				//need code for guest
			}
		}
	}
	/**
	 * Kunena integration
	*/
	if (! $avatar) {
		if( file_exists(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_kunena' . DS . 'admin.kunena.php') ) {
			if ($myss->userid) {
				$query = "SELECT avatar FROM #__kunena_users WHERE userid=".$myss->userid;
				$db->setQuery($query);
				$row = $db->loadResult();
				if ($row) {
					$avt_path = bc_getLiveSite()."/media/kunena/avatars/resized/size144/users/";
					$avatar = htmlspecialchars($avt_path.$row, ENT_QUOTES, "UTF-8");
				} else {
					//provide default avatar as we did not find avatar defined for user
				}
			} else {
				//need code for guest
				$avt_path = bc_getLiveSite()."/media/kunena/avatars/nophoto.jpg";
				$avatar = htmlspecialchars($avt_path, ENT_QUOTES, "UTF-8");
			}
		}
	}
	return $avatar;
}

/*
update current user session data (used on user signin to chat)
*/
function bc_userSignIn($myss) {
	$db =& JFactory::getDBO();

	$bc_time = time();

	//update upon chat entry
	if ($myss->username && $myss->username == $db->getEscaped($myss->username)) {
		//replace mysql_real_escape_string() with $db->getEscaped()
		$query = "UPDATE #__session "
		." SET bc_lastUpdate='$bc_time' "
		." WHERE username LIKE '".$db->getEscaped($myss->username)."' ";
		;
	} else {
		$query = "UPDATE #__session "
		." SET bc_lastUpdate='$bc_time' "
		." WHERE session_id='$myss->session_id' ";
		;
	}
	$db->setQuery($query);
	$db->query();

	if ($myss->userid) {
		$bc_date = date("Y-m-d H:i:s");
		$query = "UPDATE #__blastchatc_users "
		." SET bc_lastEntry='$bc_date', bc_rid=0, bc_rsid=0, bc_rname=null, bc_idle=null "
		." WHERE bc_userid=$myss->userid "
		;
		$db->setQuery($query);
		$db->query();
		if (intval($db->getAffectedRows()) < 1) {
			$query = "INSERT INTO #__blastchatc_users "
			." ( bc_userid, bc_lastEntry ) VALUES ( $myss->userid, '$bc_date' ) "
			;
			$db->setQuery($query);
			if (!$db->query()) {
				//echo "Failed to insert userdata - " .$db->getErrorMsg();
				return;
			}
		}
	}
}

/*
update current user session data (used on user signoff from chat)
*/
function bc_userSignOff() {
	$db =& JFactory::getDBO();

	$myss = bc_getSessionData();

	//update upon chat entry
	if ($myss->username && $myss->username == $db->getEscaped($myss->username)) {
		//replace mysql_real_escape_string() with $db->getEscaped()
		$query = "UPDATE #__session "
		." SET bc_lastUpdate=NULL "
		." WHERE username LIKE '".$db->getEscaped($myss->username)."' ";
		;
	} else {
		$query = "UPDATE #__session "
		." SET bc_lastUpdate=NULL "
		." WHERE session_id='$myss->session_id' ";
		;
	}
	$db->setQuery($query);
	$db->query();

	if ($myss->userid) {
		$query = "UPDATE #__blastchatc_users "
		." SET bc_rid=0, bc_rsid=0, bc_rname='', bc_idle=''"
		." WHERE bc_userid=$myss->userid "
		;
		$db->setQuery($query);
		$db->query();
	}
}

/*
returns URL of your website (self referenced URL)
*/
function bc_getLiveSite() {
	/*
	//return URL of your website (no trailing slash at the end), examples: http://www.xxx.com or http://www.xxx.com/something
	return "http://www.yourwebsite.com";
	*/

	/*
	//another possible way to autodetect URI
	$request = & new JURI();
	$site = $request->current();
	if (!$site) {
	$site = split("/index". $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
	$site = $site[0];
	}
	return $site;
	*/
	return substr_replace(JURI::root(), '', -1, 1);
}

function bc_getVersion() {
	//prepare variables dependent on system used
	// $_VERSION - variable holding CMS information
	// $_VERSION->PRODUCT - product used
	// $_VERSION->RELEASE - release number of product used
	// $_VERSION->DEV_LEVEL  - development number of product used
	$bc_version = new JVersion();
	return $bc_version;
}

function bc_getCurrentTemplate() {
	return JApplication::getTemplate();
}
?>