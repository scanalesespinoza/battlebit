<?php
/**
 * @version		$Id: script.php 2011-07-21 15:24:18Z $
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
 
/**
 * Script file of HelloWorld component
 */
class com_blastchatcInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{
		// $parent is the class calling this method
		$parent->getParent()->setRedirectURL('index.php?option=com_blastchatc&view=register');
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		// $parent is the class calling this method
		echo '<p>' . JText::_('COM_BLASTCHATC_UNINSTALL_TEXT') . '</p>';
	}
 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		// $parent is the class calling this method
		echo '<p>' . JText::_('COM_BLASTCHATC_UPDATE_TEXT') . '</p>';
	}
 
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		echo '<p>' . JText::_('COM_BLASTCHATC_PREFLIGHT_' . $type . '_TEXT') . '</p>';
	}
 
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		echo '<p>' . JText::_('COM_BLASTCHATC_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
		
		$params = JComponentHelper::getParams('com_blastchatc');
		
		//$app = &JFactory::getApplication('site');
		//$params = &$app->getParams("com_blastchatc");

		$url = $params->get("url");
		if (empty($url)) {
			$bc_site = substr_replace(JURI::root(), '', -1, 1);
			$bc_site = strtolower($bc_site);
			$bc_site = str_replace("http://", "", $bc_site);
			$bc_site = str_replace("https://", "", $bc_site);
			$params->set("url", $bc_site);
			$params->set("intraid", md5( $bc_site.uniqid(microtime(), 1 ) ));
			$params->set("privkey", md5( uniqid(microtime(), 1 ).$bc_site ));
			$params->set("type", "chat");
			$params->set("intf", "st");
			$params->set("width", "100%");
			$params->set("height", "480");
			$params->set("frame_border", "0");
			$params->set("mwidth", "0");
			$params->set("mheight", "0");
		}
		
		$db = JFactory::getDbo();
		$db->setQuery('UPDATE #__extensions SET params = ' .
			$db->quote( $params ) .
			' WHERE name = "com_blastchatc"' );
			$db->query();
	}
}