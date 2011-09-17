<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: maintenance.php 6648 2011-06-08 10:13:51Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );

class ImageShowControllerMaintenance extends JController
{
	function __construct()
	{
		parent::__construct();
	}

	function display($cachable = false, $urlparams = false)
	{

		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar( 'view'  , 'maintenance' );
		JRequest::setVar( 'model'  , 'maintenance' );
		parent::display();
	}

	function backup()
	{
		global $option;
		$model 								= $this->getModel( 'maintenance' );
		$filename							= JRequest::getVar('filename');
		$showLists							= JRequest::getInt('showlists');
		$showCases							= JRequest::getInt('showcases');
		$timestamp							= JRequest::getInt('timestamp');

		if ($showLists == 1)
		{
			$showLists = true;
		}
		else
		{
			$showLists = false;
		}

		if ($showCases == 1)
		{
			$showCases = true;
		}
		else
		{
			$showCases = false;
		}

		if ($timestamp == 1)
		{
			$timestamp = true;
		}
		else
		{
			$timestamp = false;
		}

		$result 	= $model->backup($showLists, $showCases, $timestamp, $filename);
		$link 		= 'index.php?option=com_imageshow&controller=maintenance';

		if ($result == false)
		{
			$msg = JText::_('MAINTENANCE_BACKUP_YOU_MUST_SELECT_AT_LEAST_ONE_TYPE_TO_BACKUP');
			$this->setRedirect($link,$msg);
		}
	}

	function restore()
	{
		global $objectLog;
		$user			= JFactory::getUser();
		$userID			= $user->get ('id');
		$file       	= JRequest::getVar( 'filedata', '', 'files', 'array' );
		$extensionFile 	= substr($file['name'], strrpos($file['name'],'.')+1 );

		if ($extensionFile == 'zip')
		{
			$compressType 	= 1;
			$filepath 		= JPATH_ROOT.DS.'tmp';

			$config['path'] 		= $filepath;
			$config['file'] 		= $file;
			$config['compress'] 	= $compressType;

			$objJSNRestore 	= JSNISFactory::getObj('classes.jsn_is_restore');
			$result 		= $objJSNRestore->restore($config);
			$link 			= 'index.php?option=com_imageshow&controller=maintenance';

			if ($result === true)
			{
				$objectLog->addLog($userID, JRequest::getURI(), $file['name'],'maintenance','restore');
				$msg 		= JText::_('MAINTENANCE_BACKUP_RESTORE_SUCCESSFULL');
				$this->setRedirect($link,$msg);
			}
			elseif ($result == 'outdated')
			{
				$msg 		= JText::_('MAINTENANCE_BACKUP_ERROR_IMAGESHOW_VERSION_RESTORE');
				$this->setRedirect($link,$msg);
			}
			else
			{
				$msg 		= JText::_('MAINTENANCE_BACKUP_RESTORE_UNSUCCESSFULL');
				$this->setRedirect($link,$msg);
			}
		}
		else
		{
			$msg = JText::_('MAINTENANCE_BACKUP_FORMAT_FILE_RESTORE_INCORRECT');
			$link = 'index.php?option=com_imageshow&controller=maintenance';
			$this->setRedirect($link,$msg);
		}
	}

	function cancel()
	{
		$link = 'index.php?option=com_imageshow';
		$this->setRedirect($link);
	}

	function reInstallLang()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$array_BO		= JRequest::getVar('lang_array_BO', array(), 'post', 'array');
		$array_FO		= JRequest::getVar('lang_array_FO', array(), 'post', 'array');
		$objJSNLang 	= JSNISFactory::getObj('classes.jsn_is_language');
		$msg			= JText::_('MAINTENANCE_LANG_YOU_MUST_SELECT_AT_LEAST_ONE_LANGUAGE_TO_INSTALL');

		if (count($array_BO) > 0)
		{
			$msg = JText::_('MAINTENANCE_LANG_THE_LANGUAGE_HAS_BEEN_SUCCESSFULLY_INSTALLED');
			$objJSNLang->installationFolderLangBO($array_BO);
		}

		if (count($array_FO) > 0)
		{
			$msg = JText::_('MAINTENANCE_LANG_THE_LANGUAGE_HAS_BEEN_SUCCESSFULLY_INSTALLED');
			$objJSNLang->installationFolderLangFO($array_FO);
		}

		$link 	= 'index.php?option=com_imageshow&controller=maintenance&type=inslangs';
		$this->setRedirect($link, $msg);
	}

	function saveMessage()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$status		= JRequest::getVar( 'status', array(), 'post', 'array' );
		$screen		= JRequest::getString('msg_screen');
		$objJSNMsg 	= JSNISFactory::getObj('classes.jsn_is_displaymessage');
		$objJSNMsg->setMessagesStatus($status, $screen);
		$link 	= 'index.php?option=com_imageshow&controller=maintenance&type=msgs';

		$this->setRedirect($link);
	}

	function refreshMessage()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$objJSNMsg = JSNISFactory::getObj('classes.jsn_is_displaymessage');
		$objJSNMsg->refreshMessage();
		$link 	= 'index.php?option=com_imageshow&controller=maintenance&type=msgs';
		$this->setRedirect($link);
	}

	function setStatusMsg()
	{
		JRequest::checkToken('get') or jexit( 'Invalid Token' );
		$msgID 		= JRequest::getInt('msg_id');
		$objJSNMsg 	= JSNISFactory::getObj('classes.jsn_is_displaymessage');
		$objJSNMsg->setSeparateMessage($msgID);
	}

	function removeProfile()
	{
		global $mainframe, $objectLog;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$user		= JFactory::getUser();
		$userID		= $user->get ('id');
		$profileID 	= JRequest::getInt('configuration_id');
		$objJSNProfile = JSNISFactory::getObj('classes.jsn_is_profile');

		if (!$objJSNProfile->deleteProfile($profileID))
		{
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		else
		{
			$objectLog->addLog($userID, JRequest::getURI(), '1', 'profile', 'delete');
		}
		exit();
	}

	function removeProfileSelect()
	{
		global $mainframe, $objectLog;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$user	= JFactory::getUser();
		$userID	= $user->get ('id');
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		$model = $this->getModel('maintenance');

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_('PLEASE MAKE A SELECTION FROM THE LIST TO').' '.JText::_('DELETE'));
		}

		if (!$model->deleteProfileSelect($cid))
		{
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		else
		{
			$objectLog->addLog($userID, JRequest::getURI(), count($cid), 'profile', 'delete');
		}

		$mainframe->redirect('index.php?option=com_imageshow&controller=maintenance&type=profiles');
	}

	function saveParam()
	{
		global $mainframe;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$post 		   = JRequest::get('post');
		$objJSNProfile = JSNISFactory::getObj('classes.jsn_is_profile');
		$objJSNProfile->saveParameters($post);
		$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNUtils->approveModule('mod_imageshow_quickicon', (int) $post['show_quick_icons']);
		$mainframe->redirect('index.php?option=com_imageshow&controller=maintenance&type=configs');
	}

	function saveProfile()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$post 		   = JRequest::get('post');
		$objJSNProfile = JSNISFactory::getObj('classes.jsn_is_profile');
		$objJSNProfile->saveProfileInfo($post);
		jexit();
	}

	/*
	 *  Install sample data
	 */
	function installSampledata()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$sampleData 						= JSNISFactory::getObj('classes.jsn_is_sampledata');
		$objReadXmlDetail					= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$objJSNJSON 						= JSNISFactory::getObj('classes.jsn_is_json');
		$task 								= JRequest::getWord('task', '', 'POST');
		$post 								= JRequest::get('post');

		if ($task == 'installSampledata')
		{
			if (!$post['agree_install_sample'])
			{
				$sampleData->returnError('false', JText::_('MAINTENANCE_SAMPLE_DATA_PLEASE_CHECK_I_AGREE_INSTALL_SAMPLE_DATA'));
			}

			$perm = $sampleData->checkFolderPermission();

			if ($perm == true)
			{
				$sampleData->checkEnvironment();
				$inforPackage 	= $objReadXmlDetail->parserXMLDetails();

				$sampleData->getPackageVersion(trim(strtolower($inforPackage['realName'])));

				$package 		= $sampleData->getPackageFromUpload();
				$unpackage 		= $sampleData->unpackPackage($package);

				if ($package != '')
				{
					$sampleData->deleteISDFile($package);
				}

				if ($unpackage != false)
				{
					$dataInstall = $objReadXmlDetail->parserExtXmlDetailsSampleData($unpackage.DS.FILE_XML);
					$sampleData->deleteTempFolderISD($unpackage);

					if ($dataInstall != false && is_array($dataInstall))
					{
						if (trim(strtolower($inforPackage['version'])) != trim(strtolower($dataInstall['imageshow']->version)))
						{
							$sampleData->returnError('false',JText::sprintf('MAINTENANCE_SAMPLE_DATA_ERROR_IMAGESHOW_VERSION',$inforPackage['realName'],$dataInstall['imageshow']->version,$inforPackage['version']));
						}

						$sampleData->executeInstallSampleData($dataInstall);
						$this->setRedirect('index.php?option=com_imageshow&controller=maintenance&type=sampledata',JText::_('MAINTENANCE_SAMPLE_DATA_INSTALL_SAMPLE_DATA_SUCCESSFULLY'));
					}
					else
					{
						$sampleData->returnError('false','');
					}
				}
				else
				{
					$sampleData->returnError('false', JText::_('MAINTENANCE_SAMPLE_DATA_UNPACK_SAMPLEDATA_FALSE'));
				}
			}
		}
	}

	function deleteTheme()
	{
		global $mainframe;
		$themeID = array();
		$id 	 = JRequest::getInt('themeID');
		if($id)
		{
			$themeID [] = $id;
			$model	= &$this->getModel('installer');
			$model->uninstall($themeID);
		}

		$link = 'index.php?option=com_imageshow&controller=maintenance&type=themes';
		$mainframe->redirect($link);
	}

	function enableDisablePlugin()
	{
		global $mainframe;
		$arrayPluginID = JRequest::getVar('pluginID');
		$publishStatus = JRequest::getInt('publish');

		if (count($arrayPluginID) > 0)
		{
			$pluginTable = JTable::getInstance('extension', 'JTable');
			$pluginTable->publish($arrayPluginID, $publishStatus);
		}

		$link = 'index.php?option=com_imageshow&controller=maintenance&type=themes';
		$mainframe->redirect($link);
	}

	function installPluginManager()
	{
		$model	= $this->getModel('installer');
		$model->install();
		$link = 'index.php?option=com_imageshow&controller=maintenance&type=themes';
		$this->setRedirect($link);
	}

	function checkEditProfileExist()
	{
		$get 			= JRequest::get('get');
		$objJSNProfile 	= JSNISFactory::getObj('classes.jsn_is_profile');
		$result 		= $objJSNProfile->checkProfileExist(trim($get['configuration_title']), $get['configuration_id']);

		$data['success'] = $result;

		if ($result){
			$data['msg'] = JText::_('MAINTENANCE_SOURCE_REQUIRED_FIELD_PROFILE_TITLE_EXIST');
		}

		$objJSNJSON = JSNISFactory::getObj('classes.jsn_is_json');

		echo $objJSNJSON->encode($data);

		jexit();
	}

	function validateProfile()
	{
		$get = JRequest::get('get');
		$data['success'] = true;

		if (isset($get['flickr_api_key']))
		{
			$objJSNFlickr = JSNISFactory::getObj('classes.jsn_is_flickr');
			$verifyFlickr = $objJSNFlickr->getValidation(trim($get['flickr_screen_name']), trim($get['flickr_api_key']), trim($get['flickr_secret_key']));

			if ($verifyFlickr == false)
			{
				$data['success'] = $verifyFlickr;
				$data['msg']     = JText::_('MAINTENANCE_SOURCE_FLICKR_ERROR_CODE_'.$objJSNFlickr->_errorCode);
			}
		}

		if (isset($get['picasa_user_name']))
		{
			$objJSNPicasa = JSNISFactory::getObj('classes.jsn_is_picasa');
			$verifyPicasa = $objJSNPicasa->getValidation(trim($get['picasa_user_name']));

			if ($verifyPicasa == false)
			{
				$data['success'] = $verifyPicasa;
				$data['msg'] 	 = JText::_('MAINTENANCE_SOURCE_INVALID_PICASA_USERNAME');
			}

		}
		$objJSNJSON = JSNISFactory::getObj('classes.jsn_is_json');

		echo $objJSNJSON->encode($data);

		jexit();
	}
}