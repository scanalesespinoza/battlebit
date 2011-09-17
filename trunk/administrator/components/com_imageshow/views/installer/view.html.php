<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class ImageShowViewInstaller extends JView
{
	function display($tpl = null) 
	{
		global $mainframe, $option;
		$this->model = $this->getModel();
		JHTML::stylesheet('imageshow.css','administrator/components/com_imageshow/assets/css/');
		JHTML::script('jsn_is_imageshow.js','administrator/components/com_imageshow/assets/js/');
		$objJSNXML = JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$infoXmlDetail    = $objJSNXML->parserXMLDetails();	
		$this->assignRef('infoXmlDetail',$infoXmlDetail);
		parent::display($tpl);
	}
}