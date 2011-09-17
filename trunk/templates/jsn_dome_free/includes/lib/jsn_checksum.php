<?php
/**
 * @author    JoomlaShine.com http://www.joomlashine.com
 * @copyright Copyright (C) 2008 - 2011 JoomlaShine.com. All rights reserved.
 * @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version   $Id: jsn_checksum.php 6797 2011-06-16 11:42:41Z tuannh $
 */
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
class JSNCheckSum
{
	var $_checksum_file			= 'template.checksum';
	var $_template_folder_path  = '';
	var $_template_folder_name 	= '';
	var $_obj_utils				= null;

	function JSNCheckSum()
	{
		$this->_setPhysicalTmplInfo();
		require_once($this->_template_folder_path.DS.'includes'.DS.'lib'.DS.'jsn_utils.php');
		$this->_setUtilsInstance();
	}

	/**
	 *
	 * Initialize instance of JSNUtils class
	 */
	function _setUtilsInstance()
	{
		$this->_obj_utils =& JSNUtils::getInstance();
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


	function getFileList()
	{
		$files 		= array ();
		$basePath	= $this->_template_folder_path;

		//Get the list of files from given folder
		$fileList 	= JFolder::files($basePath, '.', true, true, array('.checksum', '.svn', 'CVS'));
		if ($fileList !== false)
		{
			foreach ($fileList as $file)
			{
				$absolute_path				  = str_replace('/', DS, $file);
				$relative_path 				  = str_replace(DS, '/', str_replace($basePath.DS, '',  $absolute_path));
				$files[$relative_path] = md5_file($absolute_path); //$tmp;
			}
			unset($files[@$this->_checksum_file]);
		}
		return $files;
	}

	function readCheckSumFile()
	{
		$path 	= $this->_template_folder_path.DS.$this->_checksum_file;
		$files	= array();
		if (JFile::exists($path))
		{
			 $elements = explode("\n", trim(JFile::read($path)));
			 if (count($elements))
			 {
				foreach ($elements as $element)
				{
					if (!empty($element))
					{
						$line = explode("\t", $element);
						$files [$line[0]] = $line[1];
					}
				}
				unset($files[@$this->_checksum_file]);
			 }
		}
		return $files;
	}

	function compare()
	{
		$local 			= $this->getFileList();
		$checksum		= $this->readCheckSumFile();
		$countLocal 	= count($local);
		$countChecksum 	= count($checksum);
		$results		= array('added'=>array(), 'deleted'=>array(), 'modified'=>array());

		if (!$countLocal || !$countChecksum) return false;

		if(!function_exists('array_diff_key'))
		{
			$results['added'] 		= $this->arrayDiffKey($local, $checksum);
			$results['deleted'] 	= $this->arrayDiffKey($checksum, $local);
		}
		else
		{
			$results['added'] 		= array_diff_key($local, $checksum);
			$results['deleted'] 	= array_diff_key($checksum, $local);
		}

		foreach ($checksum as $key => $value)
		{
			if (isset($local[$key]) && $local[$key] != $value)
			{
				$results['modified'][$key] =  $value;
			}
		}

		if (count($results['added']))
		{
			if (isset($results['added'][$this->_checksum_file]))
			{
				unset($results['added'][$this->_checksum_file]);
			}
		}
		else
		{
			unset($results['added']);
		}

		if (count($results['deleted']))
		{
			if (isset($results['deleted'][$this->_checksum_file]))
			{
				unset($results['deleted'][$this->_checksum_file]);
			}
		}
		else
		{
			unset($results['deleted']);
		}

		if (count($results['modified']))
		{
			if (isset($results['modified'][$this->_checksum_file]))
			{
				unset($results['modified'][$this->_checksum_file]);
			}
		}
		else
		{
			unset($results['modified']);
		}

		return $results;
	}

	function arrayDiffKey()
	{
		$arrs   = func_get_args();
		$result = array_shift($arrs);
		foreach ($arrs as $array)
		{
			foreach ($result as $key => $v)
			{
				if (array_key_exists($key, $array))
				{
					unset($result[$key]);
				}
			}
		}
		return $result;
	}
}