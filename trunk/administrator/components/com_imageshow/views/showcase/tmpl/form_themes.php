<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form_themes.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

JHTML::_('behavior.modal');
$objShowcaseTheme 	= JSNISFactory::getObj('classes.jsn_is_showcasetheme');
$objShowcase		= JSNISFactory::getObj('classes.jsn_is_showcase');
$objUtils 			= JSNISFactory::getObj('classes.jsn_is_utils');
$baseURL 			= $objUtils->overrideURL();
$themes 			= $objShowcaseTheme->listThemes(false);
$editTask 			= JRequest::getVar('edit',true);
$countTheme 		= count($themes);
?>
<script>
	window.addEvent('domready', function(){
		var listThemeID 	= $('jsnis-showcase-list-theme');
		var selectThemeID 	= $('jsn-showcase-select-theme');

		if(listThemeID)
		{
			var maxWidth = selectThemeID.offsetWidth.toInt();
			var realWidth = <?php echo $countTheme * 265; ?>;

			if(realWidth < maxWidth ){
				listThemeID.setStyles({'width': realWidth + 'px'});
			}
		}

	});
</script>

<div class="jsnis-dgrey-heading jsnis-dgrey-heading-style">
	<h3 class="jsnis-element-heading"><?php echo JText::_('SHOWCASE_TITLE_SHOWCASE_THEME_SETTINGS'); ?></h3>
</div>
<div id="jsnis-no-theme">
	<?php if($countTheme == 0){ ?>
	<p class="jsnis-theme-message"><?php echo JText::_('SHOWCASE_THEME_NO_THEME_FOUND');?></p>
	<p class="jsnis-theme-download-link"><?php echo JText::_('SHOWCASE_THEME_YOU_MUST_INSTALL_AT_LEAST_1_THEME_TO_SHOW_YOUR_GALLERY'); ?> <a href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow-default-theme-j16.zip"><?php echo JText::_('SHOWCASE_THEME_DOWNLOAD_DEFAULT_THEME'); ?></a></p>
	<p class="jsnis-theme-install"><a class="jsnis-theme-install-link modal" rel="{handler: 'iframe', size: {x: 480, y: 120}}" href="index.php?option=com_imageshow&controller=showcase&task=showcaseinstalltheme&tmpl=component"><?php echo JText::_('SHOWCASE_THEME_INSTALL_NEW_THEME');?></a></p>
	<?php }
		elseif($countTheme > 0) {
	?>
	<p class="jsnis-theme-message"><?php echo JText::_('SHOWCASE_THEME_SELECT_YOUR_THEME')?></p>
	<div id="jsnis-showcase-list-theme" class="clearafter">
		<?php
			foreach($themes as $theme) {
		?>
		<span class="jsnis-theme-container"><a class="theme" onclick="JSNISImageShow.switchShowcaseTheme(this); return false;" href="index.php?option=com_imageshow&controller=showcase&task=switchtheme&subtask=add&theme=<?php echo $theme['element']?>"> <img src="<?php echo dirname($baseURL); ?>/plugins/jsnimageshow/<?php echo $theme['element']; ?>/<?php echo $theme['element']; ?>/assets/images/jsn_theme_thumbnail.png"/></a></span>
		<?php
			}
		?>
		<a class="jsnis-theme-install-link modal" rel="{handler: 'iframe', size: {x: 480, y: 140}}" href="index.php?option=com_imageshow&controller=showcase&task=showcaseinstalltheme&tmpl=component"><?php echo JText::_('SHOWCASE_THEME_INSTALL_NEW_THEME');?></a> </div>
	<?php
		}
	?>
</div>
