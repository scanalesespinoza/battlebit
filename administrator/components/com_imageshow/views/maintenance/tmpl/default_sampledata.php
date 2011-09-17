<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_sampledata.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

JHTML::stylesheet('imageshow.css','administrator/components/com_imageshow/assets/css/');
JHTML::_('behavior.mootools');
?>
<script type="text/javascript">
	window.addEvent('domready',function(){
		$$('.agree_install_sample_local').each(function(element){
			element.disabled = true;
		});
	});

	function enableDisableButton(element)
	{
		var elementId = element.id;
		$$('.'+elementId).each(function(el){
			if(el.disabled == true)
			{
				el.disabled = false;
				return;
			}
			if(el.disabled == false)
			{
				el.disabled = true;
				return;
			}
		});
	}
</script>

<div id="jsnis-main-content">
	<div id="jsnis-sample-data">
		<div class="text-alert">
			<strong style="color: #cc0000"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_WARNING'); ?></strong>
			<?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_SUGGESTION'); ?>
		</div>
		<div id="jsnis-sample-data-install">
			<form action="index.php?option=com_imageshow&controller=maintenance&type=sampledata" method="post" enctype="multipart/form-data">
				<h3 class="jsnis-element-heading"><?php echo JText::_("MAINTENANCE_SAMPLE_DATA_LOCAL_STEP_1");?></h3>
				<p><?php echo JText::sprintf('MAINTENANCE_SAMPLE_DATA_LOCAL_SUGGESTION', FILE_URL); ?></p>
				<hr />
				<h3 class="jsnis-element-heading"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_LOCAL_STEP_2');?></h3>
				<p><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_AFTER_DOWNLOAD_SUGGESTION'); ?></p>
				<p><span><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_SAMPLE_DATA'); ?>:</span>&nbsp;&nbsp;
					<input id="sample_data_input_file" size="75%" type="file" name="install_package" />
				</p>
				<p>
					<input onclick="return enableDisableButton(this);" type="checkbox" name="agree_install_sample" id="agree_install_sample_local" value="1" />
					<label for="agree_install_sample_local"> <strong><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_AGREE_INSTALL_SAMPLE_DATA'); ?></strong> </label>
				</p>
				<div class="jsnis-button-container">
					<button class="jsnis-button agree_install_sample_local" type="submit" name="submit" ><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_SAMPLE_DATA');?></button>
				</div>
				<input type="hidden" name="task" value="installSampledata"/>
				<input type="hidden" name="option" value="com_imageshow" />
				<input type="hidden" name="controller" value="maintenance" />
				<?php echo JHTML::_( 'form.token' ); ?>
			</form>
		</div>
	</div>
</div>
