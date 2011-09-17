<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: plugin.php 6599 2011-06-06 02:26:54Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$showlistID = JRequest::getInt('showlist_id');
$showcaseID = JRequest::getInt('showcase_id');
$pluginInfo = $this->pluginContentInfo;
?>
<div class="jsnis-plugin-details">
<h3><?php echo JText::_('CPANEL_PLUGIN_SYNTAX_DETAILS'); ?></h3>
<?php
echo JText::_('CPANEL_PLEASE_INSERT_FOLLOWING_TEXT_TO_YOUR_ARTICLE_AT_THE_POSITION_WHERE_YOU_WANT_TO_SHOW_GALLERY');
?>
<p><input type="text" id="syntax-plugin" class="jsnis-readonly" name="plugin" value="{imageshow sl=<?php echo $showlistID; ?> sc=<?php echo $showcaseID; ?> /}" /></p>
<?php
echo JText::_('CPANEL_MORE_DETAILS_ABOUT_PLUGIN_SYNTAX');
?>
</div>
