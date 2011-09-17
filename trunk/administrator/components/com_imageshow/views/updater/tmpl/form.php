<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
?>
<div class="jsn-sub-updater-wrapper">
	<div class="jsn-sub-updater-content">
		<form action="<?php echo AUTOUPDATE_LINK; ?>" method="post">
		<p><?php echo JText::_('UPDATER_PLEASE_INPUT_YOUR_JOOMLASHINE_CUSTOMER_ACCOUNT_TO_DOWNLOAD_THE_LATEST_PRODUCT_VERSION'); ?></p>
		<p><span><?php echo JText::_('UPDATER_USERNAME'); ?>:</span>
			<input id="username" type="text" name="username" />
		</p>
		<p><span><?php echo JText::_('UPDATER_PASSWORD'); ?>:</span>
			<input id="password" type="password" name="password" />
		</p>
		<div class="jsnis-button-container">
			<button class="jsnis-button download_package" type="submit" name="submit" ><?php echo JText::_('UPDATER_DOWNLOAD');?></button>
		</div>
		<input type="hidden" name="product_name" value="JSN <?php echo trim(@$this->infoXmlDetail['realName']);?>" />
		<input type="hidden" name="edition" value="<?php echo trim(@$this->infoXmlDetail['edition']);?>" />
		<input type="hidden" name="joomla_version" value="<?php echo $this->objVersion->RELEASE; ?>" />
		</form>
	</div>
</div>