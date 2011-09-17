<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_theme.php 6776 2011-06-16 02:26:17Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>
<div class="jsn-updater-wrapper">
	<div class="jsn-updater-content">
		<h3 class="jsnis-element-heading"><?php echo JText::_("UPDATER_DOWNLOAD_THE_LATEST_VERSION");?></h3>
		<iframe src="index.php?option=com_imageshow&controller=updater&layout=theme&theme_id=<?php echo JRequest::getInt('element_id'); ?>&commercial=<?php echo JRequest::getVar('commercial'); ?>&format=clean" width="570" height="145" noresize="noresize" frameborder="0" border="0" cellspacing="0" scrolling="no" marginwidth="0" marginheight="0"></iframe>
		<hr />
		<form action="index.php?option=com_imageshow&controller=updater" method="post" enctype="multipart/form-data">
		<h3 class="jsnis-element-heading"><?php echo JText::_("UPDATER_INSTALL_THE_DOWNLOADED_PACKAGE");?></h3>
		<p><?php echo JText::_('UPDATER_ONCE_THE_LATEST_VERSION_IS_DOWNLOADED_YOU_NEED_TO_SELECT_IT_AND_START_INSTALLATION_PROCESS'); ?></p>
		<p><span><?php echo JText::_('UPDATER_PACKAGE_FILE'); ?>:</span>
			<input id="package_input_file" type="file" name="install_package" id="install_package" size="122"/>
		</p>
		<div class="jsnis-button-container">
			<button class="jsnis-button install_package" type="submit" name="submit" >&nbsp;&nbsp;&nbsp;<?php echo JText::_('UPDATER_INSTALL');?>&nbsp;&nbsp;&nbsp;</button>
		</div>
			<input type="hidden" name="task" value="install"/>
			<input type="hidden" name="option" value="com_imageshow" />
			<input type="hidden" name="controller" value="updater" />
			<input type="hidden" name="installtype" value="upload" />
			<input type="hidden" name="type" value="theme" />
			<input type="hidden" name="element_id" value="<?php echo JRequest::getInt('element_id'); ?>" />
			<input type="hidden" name="commercial" value="<?php echo JRequest::getVar('commercial'); ?>" />
			<?php echo JHTML::_('form.token'); ?>
		</form>
	</div>
</div>