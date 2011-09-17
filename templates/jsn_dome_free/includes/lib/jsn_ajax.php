<?php
/**
 * @author    JoomlaShine.com http://www.joomlashine.com
 * @copyright Copyright (C) 2008 - 2011 JoomlaShine.com. All rights reserved.
 * @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version   $Id$
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted index access' );

class JSNAjax
{
	var $_template_folder_path  = '';
	var $_template_folder_name 	= '';
	var $_obj_utils				= null;
	var $_template_edition 		= '';
	var $_template_version 		= '';
	var $_template_name 		= '';
	var $_template_copyright	= '';
	var $_template_author		= '';
	var $_template_author_url	= '';
	var $_product_info_url		= '';

	function JSNAjax()
	{
		$this->_setPhysicalTmplInfo();
		require_once($this->_template_folder_path.DS.'includes'.DS.'lib'.DS.'jsn_utils.php');
		$this->_setUtilsInstance();
		$this->_setTmplInfo();
	}

	/**
	 *
	 * Initialize instance of JSNUtils class
	 */
	function _setUtilsInstance()
	{
		$this->_obj_utils = JSNUtils::getInstance();
	}

	/**
	 * Initialize Physical template information variable
	 *
	 */
	function _setPhysicalTmplInfo()
	{
		$template_name 					= explode(DS, str_replace(array('\includes\lib', '/includes/lib'), '', dirname(__FILE__)));
		$template_name 					= $template_name [count( $template_name ) - 1];
		$path_base 						= str_replace(DS."templates".DS.$template_name.DS.'includes'.DS.'lib', "", dirname(__FILE__));
		$this->_template_folder_name    = $template_name;
		$this->_template_folder_path 	= $path_base . DS . 'templates' .  DS . $template_name;
	}

	/**
	 * Initialize template information variable
	 *
	 */
	function _setTmplInfo()
	{
		$result 				 	= $this->_obj_utils->getTemplateDetails($this->_template_folder_path, $this->_template_folder_name);
		$this->_template_edition 	= $result->edition;
		$this->_template_version 	= $result->version;
		$this->_template_name 		= $result->name;
		$this->_template_copyright 	= $result->copyright;
		$this->_template_author 	= $result->author;
		$this->_template_author_url = $result->authorUrl;
		$template_name	  			= JString::strtolower($this->_template_name);
		$exploded_template_name 	= explode('_', $template_name);
		$template_name				= @$exploded_template_name[0].'-'.@$exploded_template_name[1];
		$this->_product_info_url	= 'http://www.joomlashine.com/joomla-templates/'.$template_name.'-version-check.html';
	}

	/**
	 * Check cache folder writable or not
	 *
	 */
	function checkCacheFolder()
	{
		$cache_folder   = JRequest::getVar('cache_folder');
		$isDir 			= is_dir($cache_folder);
		$isWritable 	= is_writable($cache_folder);
		echo json_encode(array('isDir' => $isDir, 'isWritable' => $isWritable));
	}
	/**
	 * Check template version
	 *
	 */
	function checkVersion()
	{
		$obj_http_request 		= new JSNHTTPRequests($this->_product_info_url, null, null, 'get');
		$result    		  		= $obj_http_request->sendRequest();
		if($result == false)
		{
			echo json_encode(array('connection' => false, 'version' => ''));
		}
		else
		{
			$stringExplode = explode("\n", $result);
			echo json_encode(array('connection' => true, 'version' => @$stringExplode[2]));
		}
	}
	/**
	 * Check Files Integrity
	 *
	 */
	function checkFilesIntegrity()
	{
		require_once($this->_template_folder_path.DS.'includes'.DS.'lib'.DS.'jsn_checksum.php');
		$checksum 	= new JSNCheckSum();
		$result 	= $checksum->compare();

		if (is_array($result) && count($result))
		{
			// Some files have been modified , added, or deleted
			echo json_encode(array('integrity' => 1));
		}
		elseif (is_array($result) && !count($result))
		{
			// No files modification found
			echo json_encode(array('integrity' => 0));
		}
		else
		{
			// The checksum file is missing or empty
			echo json_encode(array('integrity' => 2));
		}
	}
}