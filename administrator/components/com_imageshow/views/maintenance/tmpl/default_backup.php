<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_backup.php 6637 2011-06-08 08:21:13Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
?>
<script language="javascript">
	function backup(){
		document.getElementById('frm_backup').submit();
	}
	function restore(){
		if (document.getElementById('file-upload').value == ""){
			alert( "<?php echo JText::_('MAINTENANCE_BACKUP_YOU_MUST_SELECT_A_FILE_BEFORE_IMPORTING', true); ?>" );
			return false;
		}else {
			document.getElementById('frm_restore').submit();
		}
	}
</script>
<?php $pane = JPane::getInstance('tabs',array('startOffset'=>0)); ?>

<div id="jsnis-main-content">
	<div id="jsn-data-maintenance"> <?php echo $pane->startPane('pane'); ?> <?php echo $pane->startPanel( JText::_('MAINTENANCE_BACKUP_DATA_BACKUP'), 'panel1' ); ?>
		<form action="index.php?option=com_imageshow&controller=maintenance" method="POST" name="adminForm" id="frm_backup">
			<div id="jsnis-data-backup">
				<p class="item-title"><?php echo JText::_('MAINTENANCE_BACKUP_BACKUP_OPTIONS'); ?>:</p>
				<p>
					<input type="checkbox" name="showlists"  id="showlist" value="1" />
					<label for="showlist"><?php echo JText::_('MAINTENANCE_BACKUP_BACKUP_SHOWLISTS'); ?></label>
				</p>
				<p>
					<input type="checkbox" name="showcases" id="showcases" value="1" />
					<label for="showcases"><?php echo JText::_('MAINTENANCE_BACKUP_BACKUP_SHOWCASES'); ?></label>
				</p>
				<p class="item-title"><?php echo JText::_('MAINTENANCE_BACKUP_BACKUP_FILENAME'); ?>:</p>
				<p>
					<input type="text" id="filename" name="filename" />
				</p>
				<p>
					<input type="checkbox" name="timestamp" id="timestamp" value="1" />
					<label for="timestamp"><?php echo JText::_('MAINTENANCE_BACKUP_ATTACH_TIMESTAMP_TO_FILENAME'); ?></label>
				</p>
				<input type="hidden" name="option" value="com_imageshow" />
				<input type="hidden" name="controller" value="maintenance" />
				<input type="hidden" name="task" value="backup" />
				<?php echo JHTML::_( 'form.token' ); ?> </div>
			<div class="jsnis-button-container">
				<button class="jsnis-button" type="button" value="<?php echo JText::_('MAINTENANCE_BACKUP_BACKUP');?>" onclick="backup();"><?php echo JText::_('MAINTENANCE_BACKUP_BACKUP');?></button>
			</div>
		</form>
		<?php echo $pane->endPanel(); ?> <?php echo $pane->startPanel( JText::_('MAINTENANCE_BACKUP_DATA_RESTORE'), 'panel2' ); ?>
		<form action="index.php?option=com_imageshow&controller=maintenance" method="POST" name="adminForm" enctype="multipart/form-data" id="frm_restore">
			<div id="jsnis-data-restore">
				<p class="item-title"><?php echo JText::_('MAINTENANCE_BACKUP_BACKUP_FILE'); ?>:</p>
				<p>
					<input type="file" id="file-upload" name="filedata" size="100" />
				</p>
				<input type="hidden" name="option" value="com_imageshow" />
				<input type="hidden" name="controller" value="maintenance" />
				<input type="hidden" name="task" value="restore" />
				<?php echo JHTML::_( 'form.token' ); ?> </div>
			<div class="jsnis-button-container">
				<button class="jsnis-button" type="button" value="<?php echo JText::_('MAINTENANCE_BACKUP_RESTORE'); ?>" onclick="return restore();"><?php echo JText::_('MAINTENANCE_BACKUP_RESTORE'); ?></button>
			</div>
		</form>
		<?php echo $pane->endPanel(); ?> <?php echo $pane->endPane(); ?> </div>
</div>
