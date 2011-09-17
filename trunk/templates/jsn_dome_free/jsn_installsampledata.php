<?php
/**
 * @author    JoomlaShine.com http://www.joomlashine.com
 * @copyright Copyright (C) 2008 - 2011 JoomlaShine.com. All rights reserved.
 * @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version   $Id$
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
@ini_set('display_errors', 0);
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.path');
require_once('includes'.DS.'lib'.DS.'jsn_backup.php');
global $error;

class JSNReadXmlDetails
{

	function parserTempXMLDetails()
	{
		$file_name 			= 'templateDetails.xml';
		$array_result 		= array();
		$file_details 		= dirname(__FILE__).DS.$file_name;
		$parser_details 	= JFactory::getXMLParser('Simple');
		$result_load_file_details = $parser_details->loadFile($file_details);
		if($result_load_file_details == false)
		{
			return $array_result;
		}
		$document_details 		= $parser_details->document;
		$nod_name 	 			= $document_details->getElementByPath('name');
		if($nod_name != false)
		{
			$arr_name = explode('_', $nod_name->data());
			$array_result['name'] 				= strtolower(@$arr_name[0].'_'.@$arr_name[1]);
			$array_result['name_uppercase'] 	= @$arr_name[0].' '.@$arr_name[1];
			$array_result['full_name'] 			= @$arr_name[0].'_'.@$arr_name[1].'_'.@$arr_name[2];
		}

		$nod_version 	 = $document_details->getElementByPath('version');
		if($nod_version != false)
		{
			$array_result['version'] = $nod_version->_data;
		}
		return $array_result;
	}

	function parserLocalExtXMLDetails($name, $value)
	{
		$data 	= array();
		if($value['manifest_path'] == '')
		{
			return $data;
		}

		$file_details = JPATH_ROOT.DS.str_replace('/', DS, $value['manifest_path']);
		if($name == 'imageshow')
		{
			if (!JFile::exists($file_details))
			{
				$file_details	= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_'.$name.DS.'com_'.$name.'.xml';
			}
		}

		$xml 				= JFactory::getXMLParser('Simple');
		if (!$xml->loadFile($file_details))
		{
			unset($xml);
			return $data;
		}
		$element = $xml->document->version[0];
		$data['version'] = $element ? $element->data() : '';
		return 	$data;
	}

	function parserInstalledExtXMLDetails($path)
	{
		$array_result 		= array();
	    $xml 	            = JFactory::getXMLParser('Simple');
		$path 	            = JPath::clean($path.DS.FILE_XML);
		if (!$xml->loadFile($path))
		{
			unset($xml);
			$msg 	= 'Installation file not found';
			JError::raiseWarning('SOME_ERROR_CODE', JText::_($msg));
			return false;
		}
		$document   = $xml->document;
		$attributes = $document->attributes();
		$array_result ['name'] = trim(strtolower($attributes['name']));
		$array_result ['version'] = trim(strtolower($attributes['version']));
		return $array_result;
	}
	function parserExtXmlDetails($path)
	{
	    $xml 	= JFactory::getXMLParser('Simple');
		$path 	= JPath::clean($path.DS.FILE_XML);
		if (!$xml->loadFile($path))
		{
			unset($xml);
			$msg 	= 'Installation file not found';
			JError::raiseWarning('SOME_ERROR_CODE', JText::_($msg));
			return false;
		}
		$array_obj = array();
		$document = $xml->document;
		foreach( $document->children() as $child )
		{
			$array_backup = array();
			$array_query = array();
			$obj = new stdClass();
			$attributes = $child->attributes();
			$obj->name = trim(strtolower($attributes['name']));
			$obj->version = (isset($attributes['version'])?$attributes['version']:'');
			$obj->author = (isset($attributes['author'])?$attributes['author']:'');
			$obj->manifest_path = (isset($attributes['manifest_path'])?$attributes['manifest_path']:'');
			$obj->description = (isset($attributes['description'])?$attributes['description']:'');

			foreach ($child->children() as $task)
			{
				$attributes_task = $task->attributes();
				if($attributes_task ["name"] == 'dbbackup')
				{
					foreach ($task->children() as $parameters)
					{
						foreach ($parameters->children() as $parameter)
						{
							$array_backup[] = $parameter->data();

						}
					}
				}
				if($attributes_task ["name"] == 'dbinstall')
				{
					foreach ($task->children() as $parameters)
					{
						foreach ($parameters->children() as $parameter)
						{
							$array_query[] = $parameter->data();
						}
					}
				}
			}
			$obj->backup = $array_backup;
			$obj->queries = $array_query;
			$array_obj [$attributes['name']] = $obj;
		}
		return $array_obj;
	}
}

$obj_read_xml_detail 	= new JSNReadXmlDetails();
$name 					= $obj_read_xml_detail->parserTempXMLDetails();
define("FILE_URL",'http://www.joomlashine.com/joomla-templates/'.strtolower(str_replace('_', '-', $name['full_name'])).'-sample-data-j17.zip');
define("FILE_ZIP", strtolower(str_replace('_', '-', $name['full_name'])).'-sample-data-j17.zip');
define("FILE_XML", $name['name'].'_sample_data.xml');
define("FOLDER_IMAGE", $name['name']);
define("PREFIX_FOLDER_NAME", $name['name'].'_sample_data_');
define('JAVASCRIPT_MOOTOOL', '<script type="text/javascript" src="'.JURI::base(true).'/media/system/js/mootools.js"></script>');
define('JAVASCRIPT_MOOTAB', '<script type="text/javascript" src="'.JURI::base(true).'/media/system/js/tabs.js"></script>');
////////////////////////////////////////////////////////////////////////////////////////PROTOTYPE//////////////////////////////////////////////////////////////////

function checkJSNExtensionExist($data)
{
	$config 			= new JConfig();
	$database_prefix	= $config->dbprefix;
	$db	 				= JFactory::getDBO();
	$array_extention 	= array();
	if($data != false && is_array($data))
	{
        foreach ($data as $value)
		{
			$author = $value->author;

			if($author != '' && strtolower(trim($author)) == "joomlashine")
			{
				$name 			= $value->name;
				$query 			= 'SELECT COUNT(extension_id) FROM #__extensions c WHERE c.element = "com_'.$name.'" AND c.type = "component" GROUP BY c.element';
				$db->setQuery($query);
				$result 		=  $db->loadRow();
				if($result[0] > 0)
				{
					$array_extention [$name]['exist'] = 1;
				}
				else
				{
					$array_extention [$name]['exist'] = 0;
				}

				if(isset($value->manifest_path) && trim($value->manifest_path) !='')
				{
					$array_extention [$name]['manifest_path'] = str_replace('/', DS, trim($value->manifest_path));
				}
				else
				{
					$array_extention [$name]['manifest_path'] = '';
				}
			}
		}
	}
	return 	$array_extention;
}

function deleteRecordTableAssetsByName($name)
{
	$db	 	= JFactory::getDBO();
	$where	= array();
	if (count($name))
	{
		foreach ($name as $value)
		{
			$where [] = 'LOWER(name) LIKE '.$db->Quote('%'.'com_'.$value.'%', false);
		}
		$where 	= (count($where) ? ' WHERE '. implode(' OR ', $where) : '');
		$query 	= 'DELETE FROM #__assets'.$where;
		$db->setQuery($query);
		$db->query();
	}
	return false;
}

function getPackageFromUpload()
{
	$user_file = JRequest::getVar('install_package', null, 'files', 'array');
	if (!(bool) ini_get('file_uploads'))
	{
		$msg 	= 'File upload function is disabled, please enable it in file "php.ini"';
		JError::raiseWarning('SOME_ERROR_CODE', JText::_($msg));
		return false;
	}
	if (!extension_loaded('zlib'))
	{
		$msg = 'Zlib library is disabled, please enable it in file "php.ini"';
		JError::raiseWarning('SOME_ERROR_CODE', JText::_($msg));
		return false;
	}
	if ($user_file['name'] == '')
	{
		$msg 	= 'Sample data package is not selected, please download and select it as described in "Step 1"';
		JError::raiseWarning('SOME_ERROR_CODE', JText::_($msg));
		return false;
	}
	if (JFile::getExt($user_file['name']) != 'zip')
	{
		$msg = 'Sample data package has incorrect format, please use exactly the file you downloaded in "Step 1"';
		JError::raiseWarning('SOME_ERROR_CODE', JText::_($msg));
		return false;
	}
	$tmp_dest 	= JPath::clean(JPATH_ROOT.DS.'tmp'.DS.$user_file['name']);
	$tmp_src	= $user_file['tmp_name'];
	if (!JFile::upload($tmp_src, $tmp_dest))
	{
		$msg = 'Folder "tmp" is Unwritable, please set it to Writable (chmod 777). You can set the folder back to Unwritable after sample data installation';
		JError::raiseWarning('SOME_ERROR_CODE', JText::_($msg));
		return false;
	}
	return 	$user_file['name'];
}

function unpackPackage($p_file)
{
	$tmp_dest 		= JPATH_ROOT.DS.'tmp';
	$tmpdir			= uniqid(PREFIX_FOLDER_NAME);
	$archive_name 	= $p_file;
	$extract_dir 	= JPath::clean($tmp_dest.DS.dirname($p_file).DS.$tmpdir);
	$archive_name 	= JPath::clean($tmp_dest.DS.$archive_name);
	$result 		= @JArchive::extract($archive_name, $extract_dir);
	if($result)
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
		$datas 	= $rows->queries;
		if(count($datas))
		{
			foreach ($datas as $value)
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
					return false;
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

function deleteISDFile($file_name)
{
	$path = JPATH_ROOT.DS.'tmp'.DS.$file_name;

	if(JFile::exists($path))
	{
		JFile::delete($path);
		return true;
	}
	return false;
}

function returnError($result, $msg)
{
	global $error;
	$error = $msg;
}

function getModuleIsNotBasic()
{
	$db					= JFactory::getDBO();
	$str_query 			= '';
	$str_field 			= '';
	$arrayValue			= array();
	$field_module 		= array();
	$queries			= array();

	$table_info_module 	= $db->getTableFields('#__modules', false);
	foreach ($table_info_module['#__modules'] as $key =>$value){
		$field_module [] = $value->Field;
	}
	$str_field 			= implode(',', $field_module);
	$query 				= "SELECT " . implode(',', $field_module)
							." FROM #__modules WHERE `module` NOT IN ('mod_login', 'mod_stats', 'mod_users_latest', "
							." 'mod_footer', 'mod_stats', 'mod_menu', 'mod_articles_latest', 'mod_languages', 'mod_articles_category', "
							." 'mod_whosonline', 'mod_articles_popular', 'mod_articles_archive', 'mod_articles_categories', "
							." 'mod_articles_news', 'mod_related_items', 'mod_search', 'mod_random_image', 'mod_banners', "
							." 'mod_wrapper', 'mod_feed', 'mod_breadcrumbs', 'mod_syndicate', 'mod_custom') AND `client_id` = 0";
	$db->setQuery($query);
	$rows_module_query = $db->loadAssocList();

	foreach ($rows_module_query as $value)
	{
		reset($field_module);
		foreach ($field_module as $field_value)
		{
			if($value[$field_value] == '')
			{
				$str_query .= '"", ';
			}
			else
			{
				if($field_value == 'id')
				{
					$str_query .= '"null", ';
				}
				elseif ($field_value == 'published'){
					$str_query .= '"0", ';
				}
				elseif ($field_value == 'params')
				{
					$str_query .= '"'.str_replace('"', '\"', $value['params']).'", ';
				}
				else
				{
					$str_query .= '"'.$value[$field_value].'", ';
				}
			}
		}
		$str_query = substr($str_query, 0 , -2);
		$queries [] = 'INSERT INTO #__modules ('.$str_field.') VALUES ('.$str_query.' )';
		$str_query = '';
	}
	return $queries;
}

function getModuleAdminIsNotBasic()
{
	$db					= JFactory::getDBO();
	$str_query 			= '';
	$str_field 			= '';
	$arrayValue			= array();
	$field_module 		= array();
	$queries			= array();

	$table_info_module 	= $db->getTableFields('#__modules', false);
	foreach ($table_info_module['#__modules'] as $key =>$value){
		$field_module [] = $value->Field;
	}
	$str_field 			= implode(',', $field_module);
	$query 				= "SELECT " . implode(',', $field_module) . " FROM #__modules WHERE `id` NOT IN (2, 3, 4, 6, 7, 8, 9, 10, 12, 13, 14, 15, 70) AND `client_id` = 1";
	$db->setQuery($query);
	$rows_module_query = $db->loadAssocList();

	foreach ($rows_module_query as $value)
	{
		reset($field_module);
		foreach ($field_module as $field_value)
		{
			if($value[$field_value] == '')
			{
				$str_query .= '"", ';
			}
			elseif ($field_value == 'published'){
				$str_query .= '"0", ';
			}
			else
			{
				if($field_value == 'id')
				{
					$str_query .= '"null", ';
				}
				else
				{
					$str_query .= '"'.$value[$field_value].'", ';
				}
			}
		}
		$str_query = substr($str_query, 0 , -2);
		$queries [] = 'INSERT INTO #__modules ('.$str_field.') VALUES ('.$str_query.' )';
		$str_query = '';
	}
	return $queries;
}

function deleteModuleAdminIsNotBasic()
{
	$db	= JFactory::getDBO();
	$query = 'DELETE FROM #__modules WHERE `id` NOT IN (2, 3, 4, 6, 7, 8, 9, 10, 12, 13, 14, 15, 70) AND `client_id` = 1';
	$db->setQuery($query);
	$db->query();
}

function executeQueryModuleIsNotBasic($queries, $admin = false){
	$db	= JFactory::getDBO();
	if(count($queries))
	{
		foreach ($queries as $query)
		{
			$query = trim($query);
			if ($query != '')
			{
				$db->setQuery($query);
				$db->query();
				if($admin)
				{
					$id  = $db->insertid();
					insertDataModulesMenu($id);
				}
			}
		}
	}
	return true;
}

function authenticationUsername()
{

	jimport('joomla.user.helper');
	$app = JFactory::getApplication();
	$credentials = array();
	$credentials['username'] = JRequest::getVar('username', '', 'method', 'username');
	$credentials['password'] = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);

	// Get the log in options.
	$options = array();

	// Perform the login action
	$error = $app->login($credentials, $options);

	// Check if the log in succeeded.
	if (!JError::isError($error)) {
		return true;
	} else {
		return false;
	}
}

function setDefaultTemplate($template_name)
{
	if ($template_name != '')
	{
		$db 	= JFactory::getDBO();
		$query  = 'UPDATE #__template_styles SET home = 0 WHERE client_id = 0';
		$db->setQuery($query);
		$db->query();
		$query = 'UPDATE #__template_styles SET home = 1 WHERE client_id = 0 AND template = '.$db->quote($template_name);
		$db->setQuery($query);
		$db->query();
		return true;
	}
	else
	{
		return false;
	}
}

function checkFolderPermission()
{
	$folderpath = JPATH_ROOT.DS.'tmp';
	if(is_writable($folderpath) == false)
	{
		$msg 	= 'Folder "/tmp" is currently UNWRITABLE (!). Please make it WRITABLE (chmod 777) and try again';
		JError::raiseWarning('SOME_ERROR_CODE', JText::_($msg));
		return false;
	}
	return true;
}

function getDomain()
{
	$pathURL = array();
	$uri	= JURI::getInstance();
	$pathURL['prefix'] = $uri->toString( array('host'));
	return $pathURL['prefix'];
}
/**
 * Gets a list of the actions that can be performed.
 *
 * @return	JObject
 */
function getActions()
{
	$user	= JFactory::getUser();
	$result	= new JObject;
	$actions = array(
		'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
	);

	foreach ($actions as $action) {
		$result->set($action, $user->authorise($action, 'com_templates'));
	}

	return $result;
}

function getThirdPartyExtensionMenu()
{
	$db					= JFactory::getDBO();
	$str_query 			= '';
	$sub_str_query 		= '';
	$str_field 			= '';
	$arrayValue			= array();
	$field_menu 		= array();
	$queries			= array();
	$sub_queries		= array();
	$menu_info_module 	= $db->getTableFields('#__menu', false);
	foreach ($menu_info_module['#__menu'] as $key =>$value){
		$field_menu [] = $value->Field;
	}
	$str_field 			= implode(',', $field_menu);
	$query 				= "SELECT " . implode(',', $field_menu) . " FROM #__menu WHERE `client_id` = 1 AND `parent_id` = 1 ORDER BY id ASC";
	$db->setQuery($query);
	$rows_menu_query = $db->loadAssocList();
	$defined			= array('menutype', 'client_id', 'title', 'alias', 'type', 'published', 'component_id', 'img', 'home', 'link');
	foreach ($rows_menu_query as $value)
	{
		reset($field_menu);
		$sub_queries = array();
		foreach ($field_menu as $field_value)
		{
			if($value[$field_value] == '')
			{
				$str_query .= '"", ';
			}
			else
			{
				if($field_value == 'id')
				{
					$str_query .= 'null, ';
				}
				else
				{
					$str_query .= '"'.$value[$field_value].'", ';
				}
			}
		}
		$str_query = substr($str_query, 0 , -2);
		$queries [strtolower($value['title'])]['parent']= 'INSERT INTO #__menu ('.$str_field.') VALUES ('.$str_query.' )';
		$sub_query = "SELECT " . implode(',', $field_menu) . " FROM #__menu WHERE `client_id` = 1 AND `parent_id` = ". (int) $value['id']. ' ORDER BY id ASC';

		$db->setQuery($sub_query);
		$rows_sub_menu_query = $db->loadAssocList();

		if (count($rows_sub_menu_query))
		{
			foreach ($rows_sub_menu_query as $sub_value)
			{
				$sub_menu_data = array();
				reset($field_menu);
				foreach ($field_menu as $sub_field_value)
				{
					if(in_array($sub_field_value, $defined))
					{
						if($sub_value[$sub_field_value] == '')
						{
							$sub_menu_data[$sub_field_value] = '';
						}
						else
						{
							$sub_menu_data[$sub_field_value] = $sub_value[$sub_field_value];
						}
					}
				}
				$sub_queries [] = $sub_menu_data;
			}

		}
		$queries [strtolower($value['title'])]['sub']= $sub_queries;

		$str_query = '';
	}
	return $queries;
}

function restoreThirdPartyExtensionMenu($data)
{
	$db	= JFactory::getDBO();
	if (count($data))
	{
		foreach ($data as $menu)
		{
			$db->setQuery($menu['parent']);
			$db->query();
			$id  		= $db->insertid();
			$array_id 	=  array('parent_id'=>$id);
			if (count($menu['sub']))
			{
				foreach ($menu['sub'] as $sub_menu)
				{
					$table = JTable::getInstance('menu');
					$sub_menu = $sub_menu;
					$tmp_sub_data = array_merge($sub_menu, $array_id);
					if (!$table->setLocation($id, 'last-child') || !$table->bind($tmp_sub_data) || !$table->check() || !$table->store()){}
				}
			}
		}
	}
}

function insertDataModulesMenu($moduleID)
{
	$db 	= JFactory::getDBO();
	$query = 'INSERT INTO #__modules_menu (moduleid, menuid) VALUES ("'.$moduleID.'", "0")';
	$db->setQuery($query);
	$db->query();
}
/////////////////////////////////////////////////////////////////////////////////////MAIN//////////////////////////////////////////////////////////////////////////////////////////////////
$task 								= JRequest::getWord('task', '', 'POST');
$back_up 							= JRequest::getInt('back_up', 0, 'POST');
$template_style_id					= JRequest::getInt('template_style_id', 0, 'GET');
$array_get_module_not_basic			= getModuleIsNotBasic();
$array_get_admin_module_not_basic	= getModuleAdminIsNotBasic();
$array_get_third_party_extension_menu 	= getThirdPartyExtensionMenu();
$array_in_not_existed				= array();
$file_backup_name					= null;
$check_install_success				= false;
$check_file_download				= false;
$backup_obj							= JSNBackup::getInstance();
$domain								= getDomain();
$ext_errors							= array();
$tem_name = strtolower($name['full_name']);
if($task == 'installlocal'){
	JRequest::checkToken() or jexit( 'Invalid Token' );
	$auth = authenticationUsername();
	$perm = checkFolderPermission();
	$canDo	= getActions();

	if( $auth == true && $canDo->get('core.manage') ){
		if($perm == true)
		{
			$result_get_package_from_url 	= getPackageFromUpload();
			if($result_get_package_from_url) {
				$result_unpack_package 			= unpackPackage($result_get_package_from_url);
			}
			if(isset($result_unpack_package) && $result_unpack_package != false)
			{
				$data_install 	= $obj_read_xml_detail->parserExtXmlDetails($result_unpack_package);
				if($data_install != false && is_array($data_install))
				{
					$template_install_info = $obj_read_xml_detail->parserInstalledExtXMLDetails($result_unpack_package);
				    if($template_install_info['version'] != $name['version'])
					{
					    $msg = 'The '.$name['version'].' version installed on your website is not supported. Sample data is designed for the version <strong>'.$template_install_info['version'].'</strong>, but you are using version <strong>'.$name['version'].'</strong>';
					    JError::raiseWarning('SOME_ERROR_CODE', JText::_($msg));
						deleteISDFile($result_get_package_from_url);
						deleteTempFolderISD($result_unpack_package);
					}
					else
					{
						$result_check_ext_exist = checkJSNExtensionExist($data_install);
    					foreach ($result_check_ext_exist as $key => $value)
    					{
    						if($value['exist'] == 1)
    						{
    							$ext_local_info 	= $obj_read_xml_detail->parserLocalExtXMLDetails($key, $value);
    							if(count($ext_local_info))
    							{
									if(@$ext_local_info['version'] != $data_install[$key]->version)
	    							{
	    								$ext_errors [] = 'Can not install sample data of <strong>'.$data_install[$key]->description.'</strong> - '.'The version installed on your website is out dated. Sample data is designed for the latest version <strong>'.$data_install[$key]->version.'</strong>, but you are using version <strong>'.$ext_local_info['version'].'</strong>';
	    								returnError('false', $ext_errors);
	    								unset($data_install[$key]);
	    							}
    							}
    							else
	    						{
									$ext_errors [] = '<strong>'.$data_install[$key]->description.'</strong> - '.'the version information not found in sample data package';
	    							returnError('false', $ext_errors);
	    							unset($data_install[$key]);
	    						}
    						}
    						else
    						{
    								$ext_errors [] = '<strong>'.$data_install[$key]->description.'</strong> - '.'The extension it-self is NOT installed on your website. <a class="link-action" href="http://www.joomlashine.com/joomla-extensions/jsn-'.$key.'.html" target="_blank">Read more...</a>';
    								returnError('false', $ext_errors);
									$array_in_not_existed [] = $key;
    								unset($data_install[$key]);
    						}
    					}
    					if($back_up == 1)
    					{
    						$file_backup_name 		= $backup_obj->executeBackup($domain, $data_install);
    						$check_file_download 	= true;
    					}
    					deleteModuleAdminIsNotBasic();
    					executeInstallSampleData($data_install);
    					if (count($array_in_not_existed))
    					{
							deleteRecordTableAssetsByName($array_in_not_existed);
    					}
    					executeQueryModuleIsNotBasic($array_get_module_not_basic);
    					executeQueryModuleIsNotBasic($array_get_admin_module_not_basic, true);
						restoreThirdPartyExtensionMenu($array_get_third_party_extension_menu);
    					deleteTempFolderISD($result_unpack_package);
    					setDefaultTemplate($tem_name);
    					deleteISDFile($result_get_package_from_url);
    					$check_install_success = true;
					}
				}
				else
				{
					deleteISDFile($result_get_package_from_url);
				}
			}
		    if($result_get_package_from_url != false )
			{
				deleteISDFile($result_get_package_from_url);
			}
		}
	}
}

if($task == 'download')
{
	JRequest::checkToken() or jexit( 'Invalid Token' );
	$file_name 		= JRequest::getString('file_name', '', 'POST');
	$folder_temp	= JPATH_ROOT.DS.'tmp';
	$file_path 		= $folder_temp.DS.$file_name;
	if(JFile::exists($file_path)){
		$backup_obj->downloadFile('zip', $file_name);
		jexit();
	}else{
		$msg = 'File not exists';
		JError::raiseWarning('SOME_ERROR_CODE', JText::_($msg));
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
	<head>
		<title>Install Sample Data</title>
		<link rel="stylesheet" href="<?php echo JURI::base(true); ?>/templates/<?php echo $this->template; ?>/admin/css/jsn_admin.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo JURI::base(true); ?>/media/system/css/system.css" type="text/css" />

		<script type="text/javascript">
			function submitInstallSampleData(form)
			{
				var user_name = form.username.value;
				var password = form.password.value;

				if(user_name == ''){
					alert('Required field username cannot be left blank');
					return false;
				}else if(password == ''){
					alert('Required field password cannot be left blank');
					return false;
				}else{
					return true;
				}
			}
			function enableButton(form){
				if(form.agree.checked == true){
					form.button_installation.disabled = false;
				}else{
					form.button_installation.disabled = true;
				}
			}

		</script>
	</head>
	<body id="jsn-sampledata">
		<div id="jsn-page"><div id="jsn-page_inner1"><div id="jsn-page_inner2">
		<jdoc:include type="message" />
		<?php if($check_install_success == false) { ?>
		<h1><?php echo $name['name_uppercase']; ?> Sample Data Installation</h1>
		<p>By installing sample data, you will get your website looks like on <a href="http://demo.joomlashine.com/joomla-templates/<?php echo $name['name']; ?>/index.php" target="_blank"><?php echo $name['name_uppercase']; ?> live demo</a> website. Please read following information carefully before you start.</p>
		<div class="text-alert"><strong style="color: #cc0000">WARNING!</strong>
			<ul>
				<li>Installing sample data will delete all data on this website.</li>
				<li>It is NOT recommended to install sample data on production website.</li>
			</ul>
		</div>
		<div id="jsn-wrap-installation-content">
			<form method="post" action="index.php?template=<?php echo $this->template; ?>&amp;tmpl=jsn_installsampledata&amp;template_style_id=<?php echo $template_style_id ?>" id="installSampleDataFrom" autocomplete="off" enctype="multipart/form-data">
				<div class="jsn-install-admin-info">
					<h2>Step 1. Download and select sample data package</h2>
					<p>Please download sample data package and select it in the field bellow. <a id="jsn-install-download-file" href="<?php echo FILE_URL; ?>" class="link-button">Download sample data</a></p>
					<p class="clearafter"><span>Sample data:</span><input type="file" name="install_package" id="install_package" size="43" /></p>
					<hr />
					<h2>Step 2. Input Super Administrator account and start intallation</h2>
					<p>For security reason, please input the Super Administrator account that you are using to access to your Joomla! website administration.</p>
					<p class="clearafter"><span>Username:</span><input name="username" id="username" type="text" value="" /></p>
					<p class="clearafter"><span>Password:</span><input name="password" id="password" type="password" value="" /></p>
				</div>
				<div class="jsn-install-admin-check">
					<p>
						<input type="checkbox" value="1" id="local_rules_agree" name="agree" onclick="enableButton(this.form);" />
						<label for="local_rules_agree" class="input-label"><strong>I agree that installing sample data will delete all content on this website</strong></label>
					</p>
					<p class="hd">
						<input type="checkbox" value="1" id="local_back_up" name="back_up" checked="checked" />
						<label for="local_back_up">Create for me backup of all data you are going to delete</label>
					</p>
				</div>
				<div class="jsn-install-admin-navigation">
					<button class="action-submit" type="submit" onclick="return submitInstallSampleData(this.form);" id="jsn-install-button" name="button_installation" disabled="disabled">Install sample data</button>
					<span class="action-cancel"><a id="jsn-install-cancel" class="link-action" href="administrator/index.php?option=com_templates&task=style.edit&id=<?php echo $template_style_id?>">Cancel</a></span>
				</div>
				<input type="hidden" name="task" value="installlocal" />
				<?php echo JHTML::_( 'form.token' ); ?>
			</form>
		</div>
		<?php }else{ ?>
		<form method="post" action="index.php?template=<?php echo $this->template; ?>&amp;tmpl=jsn_installsampledata&amp;template_style_id=<?php echo $template_style_id ?>" id="frm_download" name="frm_download" autocomplete="off">
			<h1><?php echo $name['name_uppercase']; ?> Sample Data <span id="jsn-sampledata-success-message">Successfully Installed</span></h1>
			<ul style="margin-top: 2em;">
				<?php if($check_file_download == true) { ?>
				<li>
					<a href="javascript:document.frm_download.submit();" class="link-action">Download backup file</a> for later restoration if necesary. <a href="http://www.joomlashine.com/docs/joomla-templates/how-to-restore-after-sample-data-installation.html" class="link-action" target="_blank">Read more...</a>
					<p><strong>Note:</strong><br />The backup file is located in folder <strong>&quot;tmp&quot;</strong> inside your Joomla folder. You can get the backup file anytime or delete it if you want.</p>
				</li>
				<?php } ?>
				<li><a href="administrator/index.php?option=com_templates&task=style.edit&id=<?php echo $template_style_id?>" class="link-action">Return to template settings page</a> for further configuration.</li>
			</ul>
			<?php if(is_array($error) && !is_null($error) && count($error)) { ?>
			<h2 style="color: #cc0000">Attention!</h2>
			<p>Sample data for following extensions could NOT be installed:</p>
			<ul>
				<?php foreach ($error as $value) { ?>
				<li><p><?php echo $value; ?></p></li>
				<?php } ?>
			</ul>
			<p>Please install the latest version of above extension(s) and rerun the sample data installation process again.</p>
			<?php } ?>
			<input type="hidden" name="task" value="download" />
			<input type="hidden" name="file_name" value="<?php echo $file_backup_name; ?>" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php } ?>
		</div></div></div>
	</body>
</html>