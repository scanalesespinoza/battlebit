<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_button.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>
<div id="jsn-imageshow-quickicon">
	<div class="icon">
		<a href="<?php echo $button['link']; ?>">
			<img src="<?php echo $button['image']; ?>" title="<?php echo $button['text']; ?>" alt="<?php echo $button['text']; ?>" />
			<span><?php echo $button['text']; ?><?php echo $button['extra_text']; ?></span></a>
	</div>
</div>
