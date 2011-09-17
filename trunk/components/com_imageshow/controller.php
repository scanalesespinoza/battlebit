<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: controller.php 6636 2011-06-08 04:48:09Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.controller' );
class ImageShowController extends JController {

	function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask( 'add',  'display' );
		$this->registerTask( 'edit', 'display' );
		$this->registerTask( 'apply', 'save' );
	}
	
	function display($cachable = false, $urlparams = false) 
	{		
		switch($this->getTask())
		{
				
			default:			
				JRequest::setVar( 'layout', 'default' );
				JRequest::setVar( 'view', 'show' );	
				JRequest::setVar( 'model', 'show' );				
		}
		
		parent::display();	
	}
	
}
?>