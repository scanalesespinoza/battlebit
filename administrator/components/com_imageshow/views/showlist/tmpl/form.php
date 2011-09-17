<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form.php 6637 2011-06-08 08:21:13Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.html.pane');
$task = JRequest::getVar('task');
$pane = JPane::getInstance('Sliders', array('allowAllClose' => true));
echo $this->loadTemplate('showlist');
echo '<div style="display: block; height:5px; width:5px;"></div>';
echo "<div class=\"jsnis-dgrey-heading jsnis-dgrey-heading-style\"><h3 class=\"jsnis-element-heading\">".JText::_('SHOWLIST_TITLE_SHOWLIST_IMAGES')."</h3></div>";
		if($task == 'add'){
			echo "<div id=\"jsnis-no-showlist\"><p class=\"jsnis-showlist-empty-warning\">".JText::_('SHOWLIST_PLEASE_SAVE_THIS_SHOWLIST_BEFORE_SELECTING_IMAGES')."<br /><a id=\"jsnis-go-link\" title=\"Go\" class=\"jsnis-button\" href=\"javascript: javascript:Joomla.submitbutton('apply');\">".JText::_('SHOWLIST_SAVE_SHOWLIST')."</a></p></div>";
		}else{
			echo $this->loadTemplate('flex');
		}
include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'footer.php');