<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_configs.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
?>

<div id="jsnis-main-content">
	<div id="jsnis-configuration">
		<form action="index.php?option=com_imageshow&controller=maintenance&type=msgs" method="POST" name="adminForm" id="frm_param">
			<table class="admintable" border="0" width=" 100%">
				<tbody>
					<tr>
						<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('MAINTENANCE_GLOBAL_HTML_OUTPUT_METHOD');?>::<?php echo JText::_('MAINTENANCE_GLOBAL_DESC_HTML_OUTPUT_METHOD'); ?>"><?php echo JText::_('MAINTENANCE_GLOBAL_HTML_OUTPUT_METHOD');?></span></td>
						<td><?php echo $this->lists['generalSwfLibrary']; ?></td>
					</tr>
					<tr>
						<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('MAINTENANCE_GLOBAL_ROOT_URL_GENERATION_MODE');?>::<?php echo JText::_('MAINTENANCE_GLOBAL_DES_ROOT_URL_GENERATION_MODE'); ?>"><?php echo JText::_('MAINTENANCE_GLOBAL_ROOT_URL_GENERATION_MODE');?></span></td>
						<td><?php echo $this->lists['rootURL']; ?></td>
					</tr>
					<tr>
						<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('MAINTENANCE_SHOW_QUICK_ICONS');?>::<?php echo JText::_('MAINTENANCE_SHOW_QUICK_ICONS_DES'); ?>"><?php echo JText::_('MAINTENANCE_SHOW_QUICK_ICONS');?></span></td>
						<td><?php echo $this->lists['showQuickIcons']; ?></td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" name="option" value="com_imageshow" />
			<input type="hidden" name="controller" value="maintenance" />
			<input type="hidden" name="task" value="saveparam" id="task" />
			<?php echo JHTML::_('form.token'); ?>
			<div class="jsnis-button-container">
				<button class="jsnis-button" type="submit" value="<?php echo JText::_('SAVE'); ?>"><?php echo JText::_('SAVE'); ?></button>
			</div>
		</form>
	</div>
</div>