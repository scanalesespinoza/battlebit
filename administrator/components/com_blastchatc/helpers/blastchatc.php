<?php
/**
 * @version		$Id: blastchatc.php 2011-07-21 15:24:18Z $
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
defined('_JEXEC') or die;

/**
 * BlastChatC component helper.
 */
abstract class BlastChatCHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
		//JSubMenuHelper::addEntry(JText::_('COM_BLASTCHATC_SUBMENU_CONFIG'), 'index.php?option=com_blastchatc', $submenu == 'config');
		JSubMenuHelper::addEntry(JText::_('COM_BLASTCHATC_SUBMENU_ADMIN'), 'index.php?option=com_blastchatc&view=admin', $submenu == 'admin');
		JSubMenuHelper::addEntry(JText::_('COM_BLASTCHATC_SUBMENU_REGISTER'), 'index.php?option=com_blastchatc&view=register', $submenu == 'register');
		//JSubMenuHelper::addEntry(JText::_('COM_BLASTCHATC_SUBMENU_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_blastchatc', $submenu == 'categories');
		// set some global property
		//$document = JFactory::getDocument();
		//$document->addStyleDeclaration('.icon-48-blastchatc {background-image: url(../media/com_blastchatc/images/tux-48x48.png);}');
	}
}
