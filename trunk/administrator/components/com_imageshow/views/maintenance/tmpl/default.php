<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.html.pane');
JHTML::_('behavior.tooltip');
JToolBarHelper::title( JText::_('JSN_IMAGESHOW').': '.JText::_( 'MAINTENANCE_CONFIGURATION_AND_MAINTENANCE' ), 'maintenance' );
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$type  = JRequest::getWord('type','backup');
$source_type  = JRequest::getInt('source_type');
?>
<script language="javascript">
window.addEvent('domready', function(){
	JSNISImageShow.Maintenance();
});
</script>
<?php
	$objJSNMsg = JSNISFactory::getObj('classes.jsn_is_displaymessage');
	if($source_type != 2 && $source_type != 3){
	echo $objJSNMsg->displayMessage('CONFIGURATION_AND_MAINTENANCE');
?>

<div id="jsn-imageshow-configuration-maintenance">
	<div id="jsn-imageshow-configuration-maintenance_inner">
		<div id="jsn-imageshow-configuration-maintenance_inner1">
			<div id="jsnis-main-navigation">
				<div class="jsnis-dgrey-heading">
					<h3 class="jsn-pane-toggler-down jsnis-element-heading jsnis-dgrey-heading-style"><span><?php echo JText::_('MAINTENANCE_CONFIGURATION'); ?></span></h3>
				</div>
				<ul class="jsnis-navigation-item">
					<li<?php echo ($type=='configs')?' id="jsnis-active-item"':''; ?>><a id="linkconfigs" href="#"><span class="icon-configs"><?php echo JText::_('MAINTENANCE_GLOBAL_PARAMETERS'); ?></span></a></li>
					<li<?php echo ($type=='msgs')?' id="jsnis-active-item"':''; ?>><a id="linkmsgs" href="#"><span class="icon-msgs"><?php echo JText::_('MAINTENANCE_MESSAGES'); ?></span></a></li>
					<li<?php echo ($type=='inslangs')?' id="jsnis-active-item"':''; ?>><a id="linklangs" href="#"><span class="icon-langs"><?php echo JText::_('MAINTENANCE_LANGUAGES'); ?></span></a></li>
				</ul>
				<div class="jsnis-dgrey-heading">
					<h3 class="jsn-pane-toggler-down jsnis-element-heading jsnis-dgrey-heading-style"><span><?php echo JText::_('MAINTENANCE_MAINTENANCE'); ?></span></h3>
				</div>
				<ul class="jsnis-navigation-item">
					<li<?php echo ($type=='sampledata')?' id="jsnis-active-item"':''; ?>><a id="linksampledata" href="#"><span class="icon-sampledata"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALLATION'); ?></span></a></li>
					<li<?php echo ($type=='backup')?' id="jsnis-active-item"':''; ?>><a id="linkbackup" href="#"><span class="icon-backup"><?php echo JText::_('MAINTENANCE_DATA_BACKUP_AND_RESTORE'); ?></span></a></li>
					<li<?php echo ($type=='profiles')?' id="jsnis-active-item"':''; ?>><a id="linkprofile" href="#"><span class="icon-profile"><?php echo JText::_('MAINTENANCE_IMAGE_SOURCE_PROFILES'); ?></span></a></li>
					<li<?php echo ($type=='themes')?' id="jsnis-active-item"':''; ?>><a id="linkthemes" href="#"><span class="icon-themes"><?php echo JText::_('MAINTENANCE_THEMES_MANAGER'); ?></span></a></li>
				</ul>
			</div>
			<div id="jsnis-main-content-container">
				<?php
		}
		?>
				<?php
						switch($type){
							case 'inslangs':
								echo $this->loadTemplate('inslangs');
							break;
							case 'msgs':
								echo $this->loadTemplate('messages');
							break;
							case 'profiles':
								echo $this->loadTemplate('profiles');
							break;
							case 'editprofile':
								if($source_type == 2)
								{
									echo $this->loadTemplate('profile_flickr');
								}
								if($source_type == 3){
									echo $this->loadTemplate('profile_picasa');
								}
							break;
							case 'configs':
								echo $this->loadTemplate('configs');
							break;
							case 'sampledata':
								echo $this->loadTemplate('sampledata');
							break;
							case 'themes':
								echo $this->loadTemplate('themes');
							break;
							case 'backup':
							default:
								echo $this->loadTemplate('backup');
							break;
						}
					?>
				<?php
		if($source_type != 2 && $source_type != 3){

		?>
			</div>
			<div class="clr"></div>
		</div>
	</div>
</div>
<div id="maintenance-footer">
	<?php include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'footer.php'); ?>
</div>
<?php
}
?>
