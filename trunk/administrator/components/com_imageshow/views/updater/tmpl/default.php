<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 7481 2011-07-26 09:19:44Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JToolBarHelper::title(JText::_('JSN_IMAGESHOW').': '.JText::_('UPDATER_UPDATER'));
$objJSNUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$elementID 		= JRequest::getInt('element_id');
$type 			= JRequest::getVar('type');
$hash    		= JUtility::getHash('JSN_IMAGESHOW_'.@$_SERVER['HTTP_USER_AGENT']);
$session 		= JFactory::getSession();
if(!$session->has($hash))
{
	$session->set($hash, array());
}
$sessionData = $session->get($hash);
$elements 	 = $objJSNUtils->getAllCoreElements();

if (!count($sessionData))
{
	$data = array();
	if(count($elements))
	{
		foreach ($elements as $key => $value)
		{
			$data[$key] = $objJSNUtils->getRemoteElementInfor($key, $value['edition']);
		}
		$session->set($hash, $data);
		$sessionData = $data;
	}
}

$document = JFactory::getDocument();
$document->addScriptDeclaration("
	window.addEvent('domready', function()
	{
		var items = $$('.jsnis-navigation-item li a');
		var updateArea = $$('.jsn-updater-wrapper');

		if (items.length > 0 && updateArea.length <= 0) {
			window.location = items[0].href;
		}
	});
");
?>
<div class="jsnis-updater-containner">
	<div class="jsnis-updater-wrapper">
		<div class="jsnis-updater-inner">
			<div class="jsnis-updater-inner-left">
				<div class="jsnis-grey-heading">
					<h3 class="jsnis-element-heading"><span><?php echo JText::_('UPDATER_UPDATE_LIST'); ?></span></h3>
				</div>
				<ul class="jsnis-navigation-item">
					<?php echo $this->renderCoreItem($type, $elements, $sessionData); ?>
					<?php echo $this->renderThemeItems($type, $elementID, $sessionData); ?>
				</ul>
			</div>
			<div class="jsnis-updater-inner-right">
				<?php
					switch ($type)
					{
						case 'core':
							echo $this->loadTemplate('core');
						break;
						case 'theme':
							echo $this->loadTemplate('theme');
						break;
						default:
						break;
					}
				?>
			</div>
			<div class="clr"></div>
		</div>
	</div>
</div>