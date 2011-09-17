<?php
/**
 * @author    JoomlaShine.com http://www.joomlashine.com
 * @copyright Copyright (C) 2008 - 2011 JoomlaShine.com. All rights reserved.
 * @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version   $Id$
 */

defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');

/**
 * Output JSN About section
 *
 * @package
 * @subpackage
 * @since		1.6
 */
class JFormFieldJSNAbout extends JFormField
{
	public $type = 'JSNAbout';

	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected function getInput() {

		JHTML::_('behavior.modal', 'a.jsn-modal');
		require_once dirname(dirname(__FILE__)).DS.'includes'.DS.'lib'.DS.'jsn_utils.php';
		$jsnUtils 	  		= JSNUtils::getInstance();
		$doc 				= JFactory::getDocument();
		$templateName		= $jsnUtils->getTemplateName();
		$templateAbsPath 	= JPATH_ROOT . DS . 'templates' .  DS . $templateName;

		$copyright          = '';
		$tellMore	    	= '';
		$html 				= '';
		$result 			= $jsnUtils->getTemplateDetails( $templateAbsPath, $templateName);
		
		// check System Cache - Plugin
		define('JSN_CACHESENSITIVE', $jsnUtils->checkSystemCache());

		$doc->addScriptDeclaration("
			function checkIntegrity()
			{
				$('jsn-check-integrity').set('html', '');
				$('jsn-check-integrity-result').set('html', '');
				var actionIntegrityUrl = '".JURI::root()."index.php';

				var resultIntegrityMsg = new Element('span');
				resultIntegrityMsg.set('class','jsn-integrity-checking');
				resultIntegrityMsg.set('html','".JText::_('CHECKING')."');
				resultIntegrityMsg.inject($('jsn-check-integrity-result'));

				var jsonRequest = new Request.JSON({url: actionIntegrityUrl, onSuccess: function(jsonObj){
					if(jsonObj.integrity == '1') {
						resultIntegrityMsg.set('class','jsn-modification-exist');
						resultIntegrityMsg.set('html','".JText::sprintf('SOME_FILES_HAVE_BEEN_MODIFIED', $templateName)."');
					} else if (jsonObj.integrity == '0') {
						resultIntegrityMsg.set('class','jsn-no-modification');
						resultIntegrityMsg.set('html','".JText::_('NO_FILES_MODIFICATION_FOUND')."');
					} else {
						resultIntegrityMsg.set('class','jsn-no-modification');
						resultIntegrityMsg.set('html','".JText::_('NO_CHECKSUM_FILE_FOUND')."');
					}
					resultIntegrityMsg.inject($('jsn-check-integrity-result'));
				}}).get({'template': '".$templateName."', 'tmpl': 'jsn_runajax', 'task': 'checkFilesIntegrity'});
			}

			window.addEvent('domready', function(){
				$('jsn-check-version').addEvent('click', function() {
				   $('jsn-check-version').set('html', '');
					var actionVersionUrl = '".JURI::root()."index.php';
					var resultVersionMsg = new Element('span');
					resultVersionMsg.set('class','jsn-version-checking');
					resultVersionMsg.set('html','".JText::_('CHECKING')."');
					resultVersionMsg.inject($('jsn-check-version-result'));
					var jsonRequest = new Request.JSON({url: actionVersionUrl, onSuccess: function(jsonObj){
						if(jsonObj.connection) {
							if(jsonObj.version == '".$result->version."') {
								resultVersionMsg.set('class','jsn-latest-version');
								resultVersionMsg.set('html','".JText::_('THE_LATEST_VERSION')."');
							} else {
								resultVersionMsg.set('class','jsn-outdated-version');
								resultVersionMsg.set('html','".JText::_('OUTDATE_VERSION')." <span class=\"jsn-newer-version\">' + jsonObj.version + '. </span><br />' + '".JText::_('CHECK_DETAILS')."');
							}
						} else {
							resultVersionMsg.set('class','jsn-connection-fail');
							resultVersionMsg.set('html','".JText::_('CONNECTION_FAILED')."');
						}
						resultVersionMsg.inject($('jsn-check-version-result'));
					}}).get({'template': '".$templateName."', 'tmpl': 'jsn_runajax', 'task': 'checkVersion'});
				});
				$('jsn-check-integrity').addEvent('click', function() {checkIntegrity()});
			});

		");

		$doc->addStyleSheet(JURI::root().'templates/'.$templateName.'/admin/css/jsn_admin.css');
		$doc->addScript(JURI::root().'templates/'.$templateName.'/admin/js/jsn_slider.js');
		$doc->addScript(JURI::root().'templates/'.$templateName.'/admin/js/jsn_admin.js');

		$jsAccordion 	= "window.addEvent('domready', function(){ new Accordion($$('.panel h3.jpane-toggler'), $$('.panel div.jpane-slider'), {onActive: function(toggler, i) { toggler.addClass('jpane-toggler-down'); toggler.removeClass('jpane-toggler'); },onBackground: function(toggler, i) { toggler.addClass('jpane-toggler'); toggler.removeClass('jpane-toggler-down'); },duration: 300,opacity: false,alwaysHide: true}); });";
		$doc->addScriptDeclaration($jsAccordion);

		if($result->edition == 'STANDARD')
		{
			$tellMore = '<p>'.JText::_('UPGRADE_TO_UNLIMITED').'</p>';
		}
		$explodedTemplateName = explode('_', $templateName);
		if(strstr($result->copyright, $result->author) === false)
		{
			$copyright = $result->copyright. ' (<a target="_blank" title="'.$result->author.'" href="'.$result->authorUrl.'">'.$result->author.'</a>)';
		}
		else
		{
			$copyright = str_replace($result->author, '<a target="_blank" title="'.$result->author.'" href="'.$result->authorUrl.'">'.$result->author.'</a>', $result->copyright);
		}
		$staticLink = $result->authorUrl.'/joomla-templates/'.@$explodedTemplateName[0].'-'.@$explodedTemplateName[1].'.html';
		$html   = '<div class="jsn-about">';
		$html  .= '<table width="100%"><tbody><tr><td width="10" valign="top">';
		$html  .= '<div class="jsn-template-thumbnail">';
		$html  .= '<img src ="../templates/'.$templateName.'/template_thumbnail.png" width="206" height="150" />';
		$html  .= '</div>';
		$html  .= '</td><td>';
		$html  .= '<div class="jsn-template-details">';
		$html  .= '<h2><a href="'.$result->authorUrl.'/joomla-templates/jsn-dome.html" target="_blank">'.str_replace('_', ' ', $result->name).' '. $result->edition .'</a></h2>'.$tellMore.'<hr />';
		$html  .= '<dl>';
		$html  .= '<dt>'.JText::_('VERSION').':</dt><dd><strong class="jsn-current-version">'.$result->version.'</strong> - <a href="javascript:void(0);" class="link-action" id="jsn-check-version">'.JText::_('CHECK_FOR_UPDATE').'</a><span id="jsn-check-version-result"></span></dd>';
		$html  .= '<dt>'.JText::_('COPYRIGHT').':</dt><dd>'.$copyright.'</dd>';
		$html  .= '<dt>'.JText::_('INTEGRITY').':</dt><dd><a href="javascript:void(0);" class="link-action" id="jsn-check-integrity">'.JText::_('CHECK_FOR_FILES_MODIFICATION').'</a><span id="jsn-check-integrity-result"></span></dd>';
		$html  .= '</dl>';
		$html	.= '<p class="jsn-action-buttons"><a rel="{handler: \'iframe\', size: {x: 640, y: 510}}" href="'.$result->authorUrl.'/'.@$explodedTemplateName[2].'-joomla-templates-promo.html" class="link-button jsn-modal"><span class="icon-gallery">'.JText::_('SEE_OTHER_TEMPLATES').'</span></a><a href="http://twitter.com/joomlashine" target="_blank" class="link-button"><span class="icon-twitter">'.JText::_('FOLLOW_US_ON_TWITTER').'</span></a></p>';
		$html  .= '</div>';
		$html  .= '</td></tr>';
		$html  .= '</tbody></table>';
		$html  .= '</div>';

		return $html;
	}
}