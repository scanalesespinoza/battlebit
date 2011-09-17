<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: core.php 6776 2011-06-16 02:26:17Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
$themeID 	= JRequest::getInt('theme_id');
$commercial = JRequest::getVar('commercial')
?>
<link rel="stylesheet" href="<?php echo JURI::root(true);?>/administrator/components/com_imageshow/assets/css/imageshow.css" type="text/css" />
<div class="jsn-sub-updater-wrapper">
	<div class="jsn-sub-updater-content">
		<form action="<?php echo AUTOUPDATE_LINK; ?>" method="post">
		<?php
			if ($commercial == '' || $commercial == 'yes')
			{
		?>
		<p><?php echo JText::_('UPDATER_PLEASE_INPUT_YOUR_JOOMLASHINE_CUSTOMER_ACCOUNT_TO_DOWNLOAD_THE_LATEST_PRODUCT_VERSION'); ?></p>
		<p><span><?php echo JText::_('UPDATER_USERNAME'); ?>:</span>
			<input id="username" type="text" name="username" />
		</p>
		<p><span><?php echo JText::_('UPDATER_PASSWORD'); ?>:</span>
			<input id="password" type="password" name="password" />
		</p>
		<?php
			}
		?>
		<div class="jsnis-button-container" <?php echo ($commercial == 'no')?'style="padding-top:50px;"':'';?>>
			<button class="jsnis-button download_package" type="submit" name="submit" ><?php echo JText::_('UPDATER_DOWNLOAD');?></button>
		</div>
		<input type="hidden" name="product_name" value="<?php echo trim(strtolower(@$this->infoXmlDetail['realName']));?>" />
		<input type="hidden" name="edition" value="<?php echo trim(strtolower(@$this->infoXmlDetail['edition']));?>" />
		<input type="hidden" name="joomla_version" value="<?php echo $this->objVersion->RELEASE; ?>" />
		</form>
	</div>
</div>