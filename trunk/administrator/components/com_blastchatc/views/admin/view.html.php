<?php
/**
 * @version		$Id: view.html.php 2011-07-21 15:24:18Z $
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
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HelloWorlds View
 */
class BlastChatCViewAdmin extends JView
{
        /**
         * HelloWorlds view display method
         * @return void
         */
        function display($tpl = null) 
        {
                // Get data from the model
                $item = $this->get('Item');
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                // Set the toolbar
                $this->addToolBar();
                
                // Display the template
                parent::display($tpl);
                
                // Set the document
                $this->setDocument();
        }

        /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
                JToolBarHelper::title(JText::_('COM_BLASTCHATC_MANAGER_BLASTCHATC'), 'blastchatc');
                JToolBarHelper::preferences('com_blastchatc');
        }
        /**
         * Method to set up the document properties
         *
         * @return void
         */
        protected function setDocument() 
        {
                $document = JFactory::getDocument();
                $document->setTitle(JText::_('COM_BLASTCHATC_ADMINISTRATION'));
        }
}