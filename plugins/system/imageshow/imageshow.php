<?php 
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: imageshow.php 6635 2011-06-08 04:38:47Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );
class plgSystemImageShow extends JPlugin{
	function plgSystemImageShow(& $subject, $config){
		parent::__construct($subject, $config);
	}
	
	function onAfterRoute()
    {
       	$application = JFactory::getApplication();
		
		$option 		= JRequest::getVar('option');
		$controller 	= JRequest::getVar('controller');
		$task 			= JRequest::getVar('task');
		
		if($application->getName() == 'administrator')
		{
			if($option == 'com_imageshow' && $controller == 'flex' && !empty($task))
			{
				include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_factory.php');
				$user = JFactory::getUser();
				if(empty($user->id))
				{
					$language = JFactory::getLanguage();
					$language->load('com_imageshow', JPATH_BASE, null, true);
					$objJSNFlex = JSNISFactory::getObj('classes.jsn_is_flex');
					echo $objJSNFlex->bindObject(false, JText::_('SHOWLIST_FLEX_LOGIN_FLEX'));
					jexit();
				}	
			}
		}          
    }

    function onAfterDispatch()
    {
    	$objFO 	= JFactory::getApplication();
		$doc 	= JFactory::getDocument();
    	JHTML::stylesheet('style.css', 'components/com_imageshow/assets/css/');
		
		if($objFO->getName() == 'site')
		{
			JHTML::_('behavior.mootools');
			JHTML::script('swfobject.js','components/com_imageshow/assets/js/');
			JHTML::script('jsn_is_extultils.js','components/com_imageshow/assets/js/');
	    	JHTML::script('jsn_is_imageshow.js','components/com_imageshow/assets/js/');
	    	
			$doc->addScriptDeclaration("
				window.addEvent('domready', function(){
	    			JSNISImageShow.alternativeContent();	
	    		});
			");
		}
	}
}
?>