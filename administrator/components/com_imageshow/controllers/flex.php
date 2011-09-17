<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: flex.php 6638 2011-06-08 08:24:35Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.controller' );

class ImageShowControllerFlex extends JController {

	function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	function display($cachable = false, $urlparams = false) 
	{		
		$layout = JRequest::getVar('layout');
		JRequest::setVar( 'layout', $layout);
		JRequest::setVar( 'view', 'flex' );
		parent::display();	
	}
}
