<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: install.class.php 7769 2011-08-16 04:27:47Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
class com_imageshowInstallerScript
{
	private $_currentVersion = '';
	private $_currentEdition = '';
	private $_parent 		 = null;
	private $_manifest 		 = null;
	private $_mainframe		 = null;
	private $_db     		 = null;

	public function __construct()
	{
		$this->_parent 				= JInstaller::getInstance();
		$this->_manifest 			= $this->_parent->getManifest();
		$this->_currentVersion    	= $this->_manifest->version;
		$this->_currentEdition      = $this->_manifest->edition;
		$this->_mainframe  			= JFactory::getApplication();
		$this->_db 		  			= JFactory::getDBO();
	}

	public function preflight()
	{
		if($this->_checkManifestFileExist())
		{
			$file = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_readxmldetails.php';
			if(JFile::exists($file))
			{
				include_once($file);
				$objectReadxmlDetail	= new JSNISReadXmlDetails();
				$info		 		    = $objectReadxmlDetail->parserXMLDetails();
				$this->_previousVersion	= $info['version'];
				$tmpCurrentVersion 		= (float) str_replace('.', '', $this->_currentVersion);
				$tmpPrevioustVersion 	= (float) str_replace('.', '', $this->_previousVersion);
				if ($tmpCurrentVersion < $tmpPrevioustVersion)
				{
					$msg = JText::sprintf('You cannot install an older version %s on top of the newer version %s', $this->_currentVersion, $this->_previousVersion);
					$this->_parent->abort($msg);
					return false;
				}
			}

			$fileUpgrade = $this->_parent->getPath('source').DS.'admin'.DS.'subinstall'.DS.'upgrade.helper.php';
			if(JFile::exists($fileUpgrade))
			{
				require_once $fileUpgrade;
				$objUpgradeHelper	= new JSNUpgradeHelper($this->_manifest);
				$objUpgradeHelper->executeUpgrade();
			}

			$this->_updateMenu();
		}
		return true;
	}

	public function postflight()
	{
		$packageFile 			= JPATH_ROOT.DS.'tmp'.DS.'jsn_imageshow_'.str_replace(' ', '_' , JString::strtolower($this->_currentEdition)).'_j1.6_'.$this->_currentVersion.'_install.zip';
		$packageExtDir 			= $this->_parent->getPath('source');
		$this->_removeFile($packageFile);
		$this->_removeFolder($packageExtDir);
		$this->_onAfterSetupImageshow();
		$this->_mainframe->redirect('index.php?option=com_imageshow&controller=installer&task=installcore');
	}

	private function _removeFile($path)
	{
		if (JFile::exists($path))
		{
			JFile::delete($path);
		}
	}

	private function _removeFolder($path)
	{
		if (JFolder::exists($path))
		{
			JFolder::delete($path);
		}
	}

	private function _removeAllThemes()
	{
		jimport('joomla.installer.installer');
		require_once dirname(__FILE__).DS.'classes'.DS.'jsn_is_showcasetheme.php';
		$objJSNTheme 	= new JSNISShowcaseTheme();
		$listThemes	 	= $objJSNTheme->listThemes(false);
		$installer 		= new JInstaller();
		$extentsion 	= JTable::getInstance('extension');
		JPluginHelper::importPlugin('jsnimageshow');
		$dispatcher 	= JDispatcher::getInstance();
		if (count($listThemes))
		{
			foreach ($listThemes as $theme)
			{
				$id = trim($theme['id']);
				$extentsion->load($id);
				$extentsion->protected = 0;
				$extentsion->store();
				$dispatcher->trigger('onExtensionBeforeUninstall', array('eid' => $id));
				$result = $installer->uninstall('plugin', $id);
			}
			$this->_mainframe->enqueueMessage('Sub-Install: Successfully removed all JSN ImageShow Theme plugins', 'message');
		}
	}

	public function uninstall()
	{
		$this->_removeAllThemes();
	}

	private function _checkManifestFileExist()
	{
		jimport('joomla.filesystem.file');
		$pathOldManifestFile		= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'com_imageshow.xml';
		$pathNewManifestFile 		= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'imageshow.xml';
		if (JFile::exists($pathNewManifestFile) || JFile::exists($pathOldManifestFile))
		{
			return true;
		}
		return false;
	}

	private function _updateMenu()
	{
		$query  = "UPDATE #__menu SET title = 'COM_IMAGESHOW' WHERE title = 'COM_IMAGESHOWS' AND link = 'index.php?option=com_imageshow'";
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	private function _onAfterSetupImageshow()
	{
		require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_update.php';
		$objJSNUpdate = new JSNISUpdate();
		$objJSNUpdate->eventUpdate('after');
	}
}