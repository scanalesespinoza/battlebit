<?php
defined('_JEXEC') or die( 'Restricted access' );
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_sampledata.php 6652 2011-06-08 10:47:12Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.path');
class JSNISSampledata
{
	public static function getInstance()
	{
		static $instanceSampleData;
		if ($instanceSampleData == null)
		{
			$instanceSampleData = new JSNISSampledata();
		}
		return $instanceSampleData;
	}

	/**
	 * Define link download, name of zip file, name of json file & prefix folder will be created in ../tmp
	 * $infor get from parse com_imageshow.xml
	 */
	function getPackageVersion($infor)
	{
		define("FILE_URL", 'http://www.joomlashine.com/joomla-extensions/jsn-'.$infor.'-sample-data-j17.zip');
		define("FILE_XML", 'jsn_'.$infor.'_sample_data.xml');
		define("PREFIX_FOLDER_NAME", 'jsn_'.$infor.'_sample_data_');
	}

	/**
	 *  Check environment allow to upload , zip file
	 */
	function checkEnvironment()
	{
		if (!(bool) ini_get('file_uploads'))
		{
			$this->returnError('false', JText::_('MAINTENANCE_SAMPLE_DATA_ENABLE_UPLOAD_FUNCTION'));
		}

		if (!extension_loaded('zlib'))
		{
			$this->returnError('false', JText::_('MAINTENANCE_SAMPLE_DATA_ENABLE_ZLIB'));
		}
		return true;
	}

	/**
	 * Check upload_max_file
	 * Check upload file have been selected & correct format
	 */
	function checkFileUpload()
	{
		$params 	= JComponentHelper::getParams('com_media');
		$max_size 	= (int) ($params->get('upload_maxsize', 0) * 1024 * 1024);
		$user_file 	= JRequest::getVar('install_package', null, 'files', 'array');

		if ($user_file['name'] == '')
		{
			$this->returnError('false', JText::_('MAINTENANCE_SAMPLE_DATA_SAMPLE_DATA_NOT_SELECTED'));
		}

		if (trim(strtolower(JFile::getExt($user_file['name']))) != 'zip')
		{
			$this->returnError('false', JText::_('MAINTENANCE_SAMPLE_DATA_SAMPLE_DATA_INCORRECT_FORMAT'));
		}

		if($user_file['size'] >= $max_size)
		{
			$this->returnError('false', JText::_('MAINTENANCE_SAMPLE_DATA_AMOUNT_UPLOAD_ALLOW').' '. ($params->get('upload_maxsize', 0)).'M');
		}
		return true;

	}

	/**
	 * Upload package from local
	 */
	function getPackageFromUpload()
	{
		$this->checkFileUpload();
		$user_file 	= JRequest::getVar('install_package', null, 'files', 'array');
		$tmp_dest 	= JPath::clean(JPATH_ROOT.DS.'tmp'.DS.$user_file['name']);
		$tmp_src	= $user_file['tmp_name'];

		if (!$user_file['size'])
		{
			$this->returnError('false', JText::_('MAINTENANCE_SAMPLE_DATA_LARGE_UPLOAD_FILE'));
		}
		
		if (!JFile::upload($tmp_src, $tmp_dest))
		{
			$this->returnError('false', '');	
		}
		return 	$user_file['name'];
	}

	/**
	 * Extract package
	 */
	function unpackPackage($p_file)
	{
		$tmp_dest 		= JPATH_ROOT.DS.'tmp';
		$tmpdir			= uniqid(PREFIX_FOLDER_NAME);
		$archive_name 	= $p_file;
		$extract_dir 	= JPath::clean($tmp_dest.DS.dirname($p_file).DS.$tmpdir);
		$archive_name 	= JPath::clean($tmp_dest.DS.$archive_name);
		$result 		= JArchive::extract( $archive_name, $extract_dir);

		if ($result)
		{
			$path = $tmp_dest.DS.$tmpdir;
			return $path;
		}
		return false;
	}

	function executeInstallSampleData($data)
	{
		$db			= JFactory::getDBO();
		$queries 	= array();

		foreach ($data as $rows)
		{
			$truncate 	= $rows->truncate;
			if (count($truncate))
			{
				foreach ($truncate as $value)
				{
					$queries [] = $value;
				}
			}
			$install 	= $rows->install;

			if (count($install))
			{
				foreach ($install as $value)
				{
					$queries [] = $value;
				}
			}
		}

		if (count($queries) != 0)
		{
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '')
				{
					$db->setQuery($query);
					if (!$db->query())
					{
						$this->returnError("false", "MAINTENANCE_SAMPLE_DATA_ERROR_QUERY_DATABASE");
					}
				}
			}
			return true;
		}
		return false;
	}

	function deleteTempFolderISD($path)
	{
		$path = JPath::clean($path);
		if (JFolder::exists($path))
		{
			JFolder::delete($path);
			return true;
		}
		return false;
	}

	function deleteISDFile($file)
	{
		$path = JPATH_ROOT.DS.'tmp'.DS.$file;

		if (JFile::exists($path))
		{
			JFile::delete($path);
			return true;
		}
		return false;
	}

	function returnError($result, $msg)
	{
		global $mainframe;

		if (is_array($msg))
		{
			foreach ($msg as $value)
			{
				JError::raiseWarning(100,JText::_($value));
			}
		}
		else
		{
			JError::raiseWarning(100,JText::_($msg));
		}

		$mainframe->redirect('index.php?option=com_imageshow&controller=maintenance&type=sampledata');
		return $result;
	}

	function checkFolderPermission()
	{
		$folderpath = JPATH_ROOT.DS.'tmp';
		if (is_writable($folderpath) == false)
		{
			$this->returnError('false','');
			return false;
		}
		return true;
	}

	// convert json sampledata to object data
	function jsonSampleDataToObject($path)
	{
		$path 		= JPath::clean($path);
		$objJSNJSON = JSNISFactory::getObj('classes.jsn_is_json');

		if (!$jsonString = @file_get_contents($path))
		{
			JError::raiseWarning(100,JText::_('MAINTENANCE_SAMPLE_DATA_NOT_FOUND_INSTALLATION_FILE_IN_SAMPLE_DATA_PACKAGE'));
			return false;
		}

		return $dataObj = $objJSNJSON->decode($jsonString);

	}

	// Prepare sampledata json
//	function parserSampleData($path)
//	{
//		$dataObj = $this->jsonSampleDataToObject($path);
//
//		if ($dataObj === false)
//		{
//			return false;
//		}
//
//		if ($dataObj != null)
//		{
//			$arrayObj = array();
//
//			$obj = new stdClass();
//			$attributes = &$dataObj->product->attributes;
//
//			if ($attributes)
//			{
//				if ($attributes->name != 'imageshow' || $attributes->author != 'joomlashine' || $attributes->description != 'JSN ImageShow' )
//				{
//					JError::raiseWarning(100,JText::_('JSON_STRUCTURE_WAS_EDITED'));
//					return false;
//				}
//
//				$obj->name 			= trim(strtolower($attributes->name));
//				$obj->version 		= (isset($attributes->version)?$attributes->version:'');
//				$obj->author 		= (isset($attributes->author)?$attributes->author:'');
//				$obj->description 	= (isset($attributes->description)?$attributes->description:'');
//			}
//			else
//			{
//				JError::raiseWarning(100,JText::_('INCORRECT_FILE_JSON_SAMPLE_DATA_PACKAGE'));
//				return false;
//			}
//
//			$arrayTruncate 	= array();
//			$arrayInstall 	= array();
//			$taskObj 		= &$dataObj->product->task;
//
//			if ($taskObj == null)
//			{
//				JError::raiseWarning(100,JText::_('JSON_STRUCTURE_WAS_EDITED'));
//				return false;
//			}
//
//			foreach ($taskObj as $task)
//			{
//				// truncate table
//				if (isset($task->name) && $task->name == 'dbtruncate')
//				{
//					foreach ($task->tables as $table)
//					{
//						foreach ($table->queries as $query)
//						{
//							$arrayTruncate[] = $query;
//						}
//					}
//				}
//
//				// install table
//				if (isset($task->name) && $task->name == 'dbinstall')
//				{
//					foreach ($task->tables as $table)
//					{
//						foreach ($table->queries as $query)
//						{
//							$arrayInstall[] = $query;
//						}
//					}
//				}
//			}
//
//			if (count($arrayTruncate) < 1 || count($arrayInstall) < 1)
//			{
//				JError::raiseWarning(100,JText::_('JSON_STRUCTURE_WAS_EDITED'));
//				return false;
//			}
//
//			$obj->truncate 	= $arrayTruncate;
//			$obj->install 	= $arrayInstall;
//
//			$arrayObj [$attributes->name] = $obj;
//
//			return $arrayObj;
//		}
//		else
//		{
//			JError::raiseWarning(100,JText::_('JSON_STRUCTURE_WAS_EDITED'));
//			return false;
//		}
//	}

}