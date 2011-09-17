<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: installcore.php 6658 2011-06-09 01:38:49Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
global $mainframe;
include_once('steps.php');
$session 	= JFactory::getSession();
if(!$this->model->checkThemePlugin())
{
	$link = 'index.php?option=com_imageshow&controller=installer&task=installtheme';
}
else
{
	$link = 'index.php?option=com_imageshow&controller=installer&task=installsuccessfully';
}

?>

<div class="jsnis-installation-container">
	<div class="jsnis-installation-step<?php echo $step1; ?> first"><?php echo JText::_('INSTALLER_CP_INSTALL_CORE'); ?></div>
	<div class="jsnis-installation-finish">
		<form action="<?php echo $link; ?>" method="POST">
			<?php
	$errors = $session->get('jsn_install_error');
	if(count($errors))
	{
		echo JText::_('INSTALLER_INSTALLATION_IS_NOT_COMPLETE');
		echo '<ul id="jsnis-installation-failure-elements">';
		foreach ($errors as $value)
		{
			if ($value == 'lgcheck')
			{
				$sessFolderAdmin = $session->get( 'jsn_install_folder_admin' );
				if (count($sessFolderAdmin) > 0)
				{
					foreach ($sessFolderAdmin as $key => $val)
					{
						if ($val == 'no')
						{
							echo '<li>/administrator/language/'.$key.'</li>';
						}
					}
				}
			}

			if ($value == 'lgcheckfo')
			{
				$sessFolderSite = $session->get('jsn_install_folder_client');
				if (count($sessFolderSite) > 0)
				{
					foreach ($sessFolderSite as $key => $val)
					{
						if ($val == 'no')
						{
							echo '<li>/language/'.$key.'</li>';
						}
					}
				}
			}

			if ($value == 'plgcontent')
			{
				echo '<li>/plugins/content</p>';
			}

			if ($value == 'plgsystem')
			{
				echo '<li>/plugins/system</p>';
			}

			if ($value == 'module')
			{
				echo '<li>/modules</p>';
			}
		}
		echo '</ul>';
		echo JText::_('INSTALLER_INSTALLATION_SET_FOLDER_PERMISSION');
		echo '<div class="jsnis-button-container"><button class="jsnis-button" name="close" value="close" type="button" onclick="redirectToInstallPage();">'.JText::_('INSTALLER_CLOSE').'</button></div>';
	}
	else
	{
		if(!$this->model->checkThemePlugin())
		{
			echo JText::_('INSTALLER_CONTENT_PLUGIN_INSTALLATION_SUCCESS');
			echo JText::_('INSTALLER_SYSTEM_PLUGIN_INSTALLATION_SUCCESS');
			echo JText::_('INSTALLER_IMAGESHOW_MODULE_INSTALLATION_SUCCESS');
			echo '<div class="jsnis-button-container"><button class="jsnis-button" name="step2" value="step2" type="submit">'.JText::_('INSTALLER_NEXT').'</button></div>';
		}
		else
		{
			$mainframe->redirect('index.php?option=com_imageshow&controller=installer&task=installsuccessfully');
		}
	}
?>
		</form>
	</div>
	<div class="jsnis-installation-step<?php echo $step2; ?>"><?php echo JText::_('INSTALLER_CP_INSTALL_THEME'); ?></div>
	<div class="jsnis-installation-step<?php echo $step3; ?>"><?php echo JText::_('INSTALLER_CP_FINISH_INSTALLATION'); ?></div>
</div>