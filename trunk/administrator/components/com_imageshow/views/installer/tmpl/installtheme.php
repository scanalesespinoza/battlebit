<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: installtheme.php 6726 2011-06-14 04:23:35Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
global $mainframe;
include_once('steps.php');
$objVersion	= new JVersion();
if($this->model->checkThemePlugin())
{
	$mainframe->redirect('index.php?option=com_imageshow&controller=installer&task=installsuccessfully');
}
?>

<div class="jsnis-installation-container">
	<div class="jsnis-installation-step<?php echo $step1; ?> first"><?php echo JText::_('INSTALLER_CP_INSTALL_CORE'); ?></div>
	<div class="jsnis-installation-step<?php echo $step2; ?>"><?php echo JText::_('INSTALLER_CP_INSTALL_THEME'); ?></div>
	<div class="jsnis-installation-finish">
		<form enctype="multipart/form-data" action="index.php?option=com_imageshow&controller=installer&task=installtheme" method="post" name="adminForm">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td colspan="2"><?php echo JText::_('INSTALLER_YOU_MUST_INSTALL_AT_LEAST_1_THEME_TO_PRESENT_YOUR_GALLERY'); ?></td>
				</tr>
				<tr>
					<td width="120"><strong>
						<label for="install_package"><?php echo JText::_('INSTALLER_SELECT_THEME'); ?>:</label>
						</strong></td>
					<td><input class="input_box" id="install_package" name="install_package" type="file" size="57" /></td>
				</tr>
			</table>
			<div class="jsnis-button-container">
				<button class="jsnis-button" type="button" value="<?php echo JText::_('INSTALLER_NEXT'); ?>" onclick="submitbutton()"><?php echo JText::_('INSTALLER_NEXT'); ?></button>
			</div>
			<input type="hidden" name="controller" value="installer" />
			<input type="hidden" name="type" value="" />
			<input type="hidden" name="installtype" value="upload" />
			<input type="hidden" name="task" value="doInstall" />
			<input type="hidden" name="option" value="com_imageshow" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	</div>
	<div class="jsnis-installation-step<?php echo $step3; ?>"><?php echo JText::_('INSTALLER_CP_FINISH_INSTALLATION'); ?></div>
</div>
<form action="<?php echo AUTOUPDATE_LINK; ?>" method="post" id="frm_download_theme">
	<input type="hidden" name="product_name" value="themeclassic" />
	<input type="hidden" name="edition" value="" />
	<input type="hidden" name="joomla_version" value="<?php echo $objVersion->RELEASE; ?>" />
</form>