<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: updater.php 6795 2011-06-16 11:05:37Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class ImageShowControllerUpdater extends JController
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display($cachable = false, $urlparams = false)
	{
		$layout = JRequest::getString('layout', 'default');
		JRequest::setVar('layout', $layout);
		JRequest::setVar('view', 'updater');
		parent::display();
	}

	function install()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$hash    		= JUtility::getHash('JSN_IMAGESHOW_'.@$_SERVER['HTTP_USER_AGENT']);
		$session 		= JFactory::getSession();
		if($session->has($hash))
		{
			$session->clear($hash);	
		}		
		$type 			= JRequest::getVar('type');
		$elementID 		= JRequest::getInt('element_id');
		$commercial	 	= JRequest::getVar('commercial');
		$link = 'index.php?option=com_imageshow&controller=updater&type='.$type.'&element_id='.$elementID.'&commercial='.$commercial;

		$model	= $this->getModel('installer');
		switch ($type)
		{
			case 'core':
				$result = $model->installComponent();
				if (!$result)
				{
					$this->setRedirect($link);
				}
			break;
			case 'theme':
				$result = $model->install();
				if (!$result)
				{
					$this->setRedirect($link);
				}
				else
				{
					$this->setRedirect('index.php?option=com_imageshow&controller=updater');
				}
			break;
			default:
				$this->setRedirect($link);
			break;
		}
	}
}
?>