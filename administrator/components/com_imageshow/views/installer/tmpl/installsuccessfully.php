<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: installsuccessfully.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
include_once('steps.php');
?>

<div class="jsnis-installation-container">
	<div class="jsnis-installation-step<?php echo $step1; ?> first"><?php echo JText::_('INSTALLER_CP_INSTALL_CORE'); ?></div>
	<div class="jsnis-installation-step<?php echo $step2; ?>"><?php echo JText::_('INSTALLER_CP_INSTALL_THEME'); ?></div>
	<div class="jsnis-installation-step<?php echo $step3; ?>"><?php echo JText::_('INSTALLER_CP_FINISH_INSTALLATION'); ?></div>
	<div class="jsnis-installation-finish">
		<p class="jsnis-installation-success"><?php echo JText::_('INSTALLER_INSTALLATION_CONGRATULATION'); ?></p>
		<div class="jsnis-button-container">
			<button class="jsnis-button" name="close" value="Finish" type="button" onclick="redirectToImageShowPage();"><?php echo JText::_('INSTALLER_FINISH'); ?></button>
		</div>
	</div>
</div>