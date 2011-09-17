<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: footer.php 6818 2011-06-18 03:26:18Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JHTML::script('jsn_is_imageshow.js','administrator/components/com_imageshow/assets/js/');
JHTML::script('jsn_is_checkupdate.js','administrator/components/com_imageshow/assets/js/');
$objJSNUtils  		= JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNXML 	  		= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
$objJSNJSON     	= JSNISFactory::getObj('classes.jsn_is_json');
$shortEdition 		= $objJSNUtils->getShortEdition();
$doc			  	= JFactory::getDocument();
$infoXmlDetail    	= $objJSNXML->parserXMLDetails();
$currentVersion 	= (float) str_replace('.', '', trim(@$infoXmlDetail['version']));
$componentInfo 		= $objJSNUtils->getComponentInfo();
if(!is_null($componentInfo) && isset($componentInfo->manifest_cache) && $componentInfo->manifest_cache != '')
{
	$componentData = $objJSNJSON->decode($componentInfo->manifest_cache);
	$componentVersion = trim($componentData->version);
}
else
{
	$componentVersion = trim($infoXmlDetail['version']);
}
$exts 				= array();
$exts[] 			= "{name: '".strtolower($infoXmlDetail['realName'])."', id: '".strtolower($infoXmlDetail['realName'])."', version: '".$componentVersion."', edition: '".strtolower($infoXmlDetail['edition'])."'}";
$modelThemePlugin	= JModel::getInstance('plugins', 'imageshowmodel');
$themeItems			= $modelThemePlugin->getFullData();
if (count($themeItems))
{
	for($i = 0, $count = count($themeItems); $i < $count; $i++)
	{
		$themeItem = $themeItems[$i];
		$exts[] = "{name: '".strtolower($themeItem->name)."', id: '".strtolower($themeItem->element)."', version: '".$themeItem->version."', edition:''}";
	}
}
$doc->addScriptDeclaration("
		var jsn_checked_extensions = [ ".implode( ', ', $exts )." ];
		window.addEvent( 'domready', function() {
			JSNISCheckUpdate.load_extensions('".JText::_('FOOTER_SEE_UPDATE_INSTRUCTIONS')."');
		});

	");
?>

<p class="jsnis-footer">
	<span class="jsnis-footer-item"><a target="_blank" class="link-item" href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow-docs.zip"><?php echo JText::_('FOOTER_DOCUMENTATION');?></a></span>
	<span class="jsnis-footer-item"><a target="_blank" class="link-item" href="http://www.joomlashine.com/forum/"><?php echo JText::_('FOOTER_SUPPORT_FORUM')?></a></span>
	<?php if($shortEdition == 'free'): ?>
		<span class="jsnis-footer-item item-end"><a target="_blank" class="link-item" href="http://www.joomlashine.com/joomla-extensions/buy-jsn-imageshow.html"><?php echo JText::_('FOOTER_UPGRADE_TO_PRO')?></a></span>
	<?php endif;?>
	<?php if($shortEdition == 'pro'): ?>
		<span class="jsnis-footer-item item-end"><a target="_blank" class="link-item" href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow-on-jed.html"><?php echo JText::_('FOOTER_VOTE_ON_JED')?></a></span>
	<?php endif;?>
	<span id="jsn-global-check-version-result" class=""></span>
</p>
