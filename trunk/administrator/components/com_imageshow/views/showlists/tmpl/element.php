<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: element.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
?>

<form action="index.php?option=com_imageshow&controller=showlist&task=element&tmpl=component" method="post" name="adminForm" id="adminForm">
<div id="jsnis-image-source-profile-details">
	<h3 class="jsnis-element-heading"><?php echo JText::_('SHOWLIST_IMAGE_SOURCE_PROFILE_DELETION'); ?></h3>
	<?php if (count( $this->items )) {?>
	<p><?php echo JText::_('SHOWLIST_FOLLOWING_SHOWLISTS_WILL_BE_RESET'); ?>:</p>
	<?php } ?>
	<ul>
		<?php
			$k 		= 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++){
			$row 			= &$this->items[$i];
			//$checked 		= JHTML::_('grid.id', $i, $row->showlist_id );
		?>
		<li>
			<?php echo $this->escape($row->showlist_title); ?>
		</li>
		<?php
			$k = 1 - $k;
		}
		?>
    </ul>
	<p><?php echo JText::_('SHOWLIST_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_IMAGE_SOURCE_PROFILE'); ?></p>
	<div class="button">
        	<button type="button" value="<?php echo JText::_('SHOWLIST_DELETE'); ?>" onclick="JSNISImageShow.ProfileDelete();" class="jsnis-button"><?php echo JText::_('SHOWLIST_DELETE'); ?></button>
            <button type="button" value="<?php echo JText::_('SHOWLIST_CANCEL');?>" onclick="window.top.setTimeout('SqueezeBox.close()', 200);" class="jsnis-button"><?php echo JText::_('SHOWLIST_CANCEL');?></button>
	</div>
</div>
<input type="hidden" name="configuration_id" value="<?php echo JRequest::getInt('configuration_id'); ?>" />
<input type="hidden" name="option" value="com_imageshow" />
<input type="hidden" name="task" id="task" value="element" />
<input type="hidden" name="controller" id="controller" value="showlist" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>