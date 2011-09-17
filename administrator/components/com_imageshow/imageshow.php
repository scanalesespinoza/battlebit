<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: imageshow.php 6637 2011-06-08 08:21:13Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined( '_JEXEC') or die( 'Restricted access');
global $mainframe;
JHTML::_('behavior.mootools');
$mainframe  = JFactory::getApplication();
$user 		= JFactory::getUser();
require_once(JPATH_COMPONENT.DS.'controller.php');
require_once(JPATH_COMPONENT.DS.'defines.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'media.php');
require_once(JPATH_COMPONENT.DS.'classes'.DS.'jsn_is_factory.php');
JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
$controllerName = JRequest::getCmd('controller');

global $objectLog;
$application  = JFactory::getApplication();
$templateName = $application->getTemplate();
if ($templateName == 'aplite')
{
	JHTML::stylesheet('jsn_apilefix.css','administrator/components/com_imageshow/assets/css/');	
}
$objShowcaseTheme 		= JSNISFactory::getObj('classes.jsn_is_showcasetheme');
$objectLog 				= JSNISFactory::getObj('classes.jsn_is_log');
$objectCheckJooPhoca 	= JSNISFactory::getObj('helpers.checkjoopho', 'CheckJoomPhocaHelper');
$objectCheckJooPhoca->checkComInstalled();
$objShowcaseTheme->enableAllTheme();

if ($controller = JRequest::getWord('controller')) 
{
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) 
	{
		require_once $path;
	} 
	else 
	{
		$controller = '';
	}
}

$classname	= 'ImageShowController'.$controller;
$controller	= new $classname();
$controller->execute( JRequest::getVar( 'task'));
$controller->redirect();