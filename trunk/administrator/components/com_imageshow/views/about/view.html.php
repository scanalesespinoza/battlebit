<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 7498 2011-07-27 04:33:17Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class ImageShowViewAbout extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;
		JHTML::script('jsn_is_imageshow.js','administrator/components/com_imageshow/assets/js/');
		JHTML::stylesheet('imageshow.css','administrator/components/com_imageshow/assets/css/');

		$objJSNXML 		  = JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$objJSNUtils      = JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNJSON       = JSNISFactory::getObj('classes.jsn_is_json');
		$doc			  = JFactory::getDocument();
		$infoXmlDetail    = $objJSNXML->parserXMLDetails();
		$componentInfo 	  = $objJSNUtils->getComponentInfo();
		$componentData 	  = null;

		if(!is_null($componentInfo) && isset($componentInfo->manifest_cache) && $componentInfo->manifest_cache != '')
		{
			$componentData  = $objJSNJSON->decode($componentInfo->manifest_cache);
			$currentVersion = $componentData->version;
		}
		else
		{
			$currentVersion = trim(@$infoXmlDetail['version']);;
		}

		$doc->addScriptDeclaration("
				window.addEvent('domready', function(){
					var check = false;
					$('jsn-check-version').addEvent('click', function() {
					   $('jsn-check-version').set('html', '');
						var actionVersionUrl = 'index.php';
						var resultVersionMsg = new Element('span');
						resultVersionMsg.set('class','jsn-version-checking');
						resultVersionMsg.set('html','".JText::_('ABOUT_CHECKING')."');
						resultVersionMsg.inject($('jsn-check-version-result'));
						var jsonRequest = new Request.JSON({url: actionVersionUrl, onSuccess: function(jsonObj){
							if(jsonObj.connection) {
								check = JSNISImageShow.checkVersion('".$currentVersion."', jsonObj.version);
								if(check) {
									resultVersionMsg.set('class','jsn-outdated-version');
									resultVersionMsg.set('html','".JText::_('ABOUT_SEE_UPDATE_INSTRUCTIONS')."');
								} else {
									resultVersionMsg.set('class','jsn-latest-version');
									resultVersionMsg.set('html','".JText::_('ABOUT_THE_LATEST_VERSION')."');
								}
							} else {
								resultVersionMsg.set('class','jsn-connection-fail');
								resultVersionMsg.set('html','".JText::_('ABOUT_CONNECTION_FAILED')."');
							}
							resultVersionMsg.inject($('jsn-check-version-result'));
						}}).get({'option': 'com_imageshow', 'controller': 'ajax', 'task': 'checkVersion'});
					});
				});

			");
		$params = JComponentHelper::getParams('com_imageshow');
		$this->assignRef('infoXmlDetail',$infoXmlDetail);
		$this->assignRef('componentData', $componentData);
		$this->assignRef('params',$params);
		parent::display($tpl);

	}
}