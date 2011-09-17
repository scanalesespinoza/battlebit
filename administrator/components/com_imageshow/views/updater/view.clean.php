<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.clean.php 6760 2011-06-15 09:39:38Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.view');
class ImageShowViewUpdater extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;
		$objVersion 	  = new JVersion();
		$objJSNXML 		  = JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$objJSNUtil		  = JSNISFactory::getObj('classes.jsn_is_utils');
		$infoXmlDetail    = $objJSNXML->parserXMLDetails();
		$this->assignRef('infoXmlDetail',$infoXmlDetail);
		$this->assignRef('objVersion',$objVersion);
		$this->assignRef('objJSNUtil',$objJSNUtil);
		parent::display($tpl);

	}
}