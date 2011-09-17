<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_readxmldetails.php 6749 2011-06-15 04:31:28Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.filesystem.file');
class JSNISReadXmlDetails
{
	var $_arrayTruncate 	= array();
	var $_arrayInstall 		= array();
	var $_limitEdition 		= true;
	var $_themeTablesExist  = array();
	var $_error				= false;

	public static function getInstance()
	{
		static $instanceReadXML;
		if ($instanceReadXML == null)
		{
			$instanceReadXML = new JSNISReadXmlDetails();
		}
		return $instanceReadXML;
	}

	function parserXMLDetails()
	{
	    $arrayResult 			= array();
		$arraylang 				= array();
		$temp 					= null;

		$pathOldManifestFile		= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'com_imageshow.xml';
		$pathNewManifestFile 		= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'imageshow.xml';

		if (JFile::exists($pathNewManifestFile))
		{
			$fileDescription = $pathNewManifestFile;
		}
		else
		{
			$fileDescription = $pathOldManifestFile;
		}

		$parserDescription 			= JFactory::getXMLParser('Simple');
		$resultLoadFileDescription  = $parserDescription->loadFile($fileDescription);
		$documentDescription 		= $parserDescription->document;
		$nodeRealName				= $documentDescription->getElementByPath('name');
		$nodVersion 			    = $documentDescription->getElementByPath('version');
		$nodAuthor 			   		= $documentDescription->getElementByPath('author');
		$nodDate 			        = $documentDescription->getElementByPath('creationdate');
		$nodLicense			        = $documentDescription->getElementByPath('license');
		$nodCopyright			    = $documentDescription->getElementByPath('copyright');
		$nodWebsite 			    = $documentDescription->getElementByPath('authorurl');
		$languages 			    	= $documentDescription->getElementByPath('languages');
		$administration				= $documentDescription->getElementByPath('administration');
		$nodEdition 				= $documentDescription->getElementByPath('edition');

		if ($administration != false)
		{
			$submenu = $administration->getElementByPath('submenu');
			if ($submenu != false)
			{
				$child = $submenu->children();

				if (count($child) > 0)
				{
					$arrayKey = array();
					foreach ($child as $value)
					{
						$keyValue = JString::strtoupper($value->data());
						$arrayKey [] = $keyValue;
					}
					$arrayResult['menu'] = $arrayKey;
				}
			}
		}

		if ($nodAuthor != false && $nodVersion != false && $nodDate!= false && $nodLicense!= false && $nodCopyright!= false && $nodWebsite!= false && $nodeRealName != false)
		{
		    $arrayResult['realName'] 	= $nodeRealName->data();
			$arrayResult['version'] 	= $nodVersion->data();
			$arrayResult['author'] 		= $nodAuthor->data();
			$arrayResult['date'] 		= $nodDate->data();
			$arrayResult['license'] 	= $nodLicense->data();
			$arrayResult['copyright'] 	= $nodCopyright->data();
			$arrayResult['website'] 	= $nodWebsite->data();
			$arrayResult['edition'] 	= (($nodEdition!= false) ? $nodEdition->data() : '');

			if ($languages!=false && count($languages->children()))
			{
				foreach ($languages->children() as $value)
				{
					if ($temp != $value->attributes('tag'))
					{
						$tag 				= $value->attributes('tag');
						$arraylang [$tag] 	= $tag;
						$temp 				= $tag;
					}
				}
			}
			$arrayResult['langs'] = $arraylang;
		}
		return $arrayResult;
	}

	function raiseError($error)
	{
		$this->_error = true;
		JError::raiseWarning(100,JText::_($error));
	}

	/*
	 * Paser xml file in package was downloaded
	 * $path path to xml file
	 *
	 */
	function parserExtXmlDetailsSampleData($path)
	{
		$objJSNUtils 	      = JSNISFactory::getObj('classes.jsn_is_utils');
		$this->_limitEdition  = $objJSNUtils->checkLimit();
		$xml 				  = JFactory::getXMLParser('Simple');
		$path 				  = JPath::clean($path);

		if (!$xml->loadFile($path))
		{
			$this->raiseError('MAINTENANCE_SAMPLE_DATA_NOT_FOUND_INSTALLATION_FILE_IN_SAMPLE_DATA_PACKAGE');
			return false;
		}

		$arrayObj = array();

		$document =& $xml->document;

		$obj = new stdClass();
		$attributes = $document->attributes();

		if ($attributes)
		{
			if($attributes['name'] != 'imageshow' || $attributes['author'] != 'joomlashine' || $attributes['description'] != 'JSN ImageShow' )
			{
				$this->raiseError('MAINTENANCE_SAMPLE_DATA_XML_STRUCTURE_WAS_EDITED');
				return false;
			}

			$obj->name 			= trim(strtolower($attributes['name']));
			$obj->version 		= (isset($attributes['version']) ? $attributes['version'] : '');
			$obj->author 		= (isset($attributes['author']) ? $attributes['author'] : '');
			$obj->description 	= (isset($attributes['description']) ? $attributes['description'] : '');
		}
		else
		{
			$this->raiseError('MAINTENANCE_SAMPLE_DATA_INCORRECT_FILE_XML_SAMPLE_DATA_PACKAGE');
			return false;
		}

		$this->_arrayTruncate = array();
		$this->_arrayInstall  = array();

		$arrayMethod 			= get_class_methods('JSNISReadXmlDetails');
		$arrayMethodAvailable   = array();

		if (is_array($arrayMethod))
		{
			foreach ($arrayMethod as $method)
			{
				$arrayMethodAvailable[] = trim(strtolower($method));
			}
		}
		
		foreach ($document->children() as $children)
		{
			$methodName = "_parse".ucfirst($children->name())."SampleData";

			if (in_array(trim(strtolower($methodName)), $arrayMethodAvailable))
			{
				$this->$methodName($children);
			}
		}

		if ($this->_error){
			return false;
		}

		$obj->truncate = $this->_arrayTruncate;
		$obj->install  = $this->_arrayInstall;

		$arrayObj [$attributes['name']] = $obj;

		return $arrayObj;
	}

	function _parseCoreSampleData($objSimpleXML)
	{
		foreach ($objSimpleXML->children() as $task)
		{
			if ($task->_name != 'task')
			{
				$this->raiseError('MAINTENANCE_SAMPLE_DATA_XML_STRUCTURE_WAS_EDITED');
				return false;
			}

			$arrayTruncate = $this->_parseTablesElementSampleData($task, 'dbtruncate');
			$arrayInstall  = $this->_parseTablesElementSampleData($task, 'dbinstall');

			if ($arrayInstall === false || $arrayTruncate === false)
			{
				return false;
			}
			
			foreach ($arrayTruncate as $value)
			{
				$this->_arrayTruncate[] =  $value;
			}

			foreach ($arrayInstall as $value)
			{
				$this->_arrayInstall[] = $value;
			}
		}

		return true;
	}

	function _parseThemesSampleData($objSimpleXML)
	{
		$objJSNTheme 	 		 = JSNISFactory::getObj('classes.jsn_is_showcasetheme');
		$themeTableExist 		 = $objJSNTheme->listThemesExist();
		$this->_themeTablesExist = is_array($themeTableExist) ? $themeTableExist : array();

		foreach ($objSimpleXML->children() as $theme)
		{
			$objJSNTheme 	= JSNISFactory::getObj('classes.jsn_is_showcasetheme');
			$themeInfo 		= $objJSNTheme->getThemeInfo($theme->_attributes['name']);
			$themeVersion   = $theme->_attributes['version'];

			if ($themeInfo->version != $themeVersion)
			{
				$this->_error = true;
				JError::raiseWarning(100,JText::sprintf('MAINTENANCE_SAMPLE_DATA_THEME_VERSION_ERROR', $theme->_attributes['description'], $themeVersion));
				return false;
			}

			foreach ($theme->children() as $task)
			{
				if ($task->_name != 'task')
				{
					$this->raiseError('MAINTENANCE_SAMPLE_DATA_XML_STRUCTURE_WAS_EDITED');
					return false;
				}

				$arrayTruncate = $this->_parseThemeTablesElementSampleData($task, 'dbtruncate');
				$arrayInstall  = $this->_parseThemeTablesElementSampleData($task, 'dbinstall');

				if ($arrayInstall === false || $arrayTruncate === false)
				{
					return false;
				}
				
				foreach ($arrayTruncate as $value)
				{
					$this->_arrayTruncate[] =  $value;
				}

				foreach ($arrayInstall as $value)
				{
					$this->_arrayInstall[] = $value;
				}
			}
		}
		return true;
	}

	function _parseTablesElementSampleData($objSimleXML, $tableType)
	{
		$queries 		= array();
		$attributesTask = $objSimleXML->attributes();

		if (isset($attributesTask ["name"]) && $attributesTask ["name"] == $tableType)
		{
			foreach ($objSimleXML->children() as $tables)
			{

				if ($tables->_name != 'tables')
				{
					$this->raiseError('MAINTENANCE_SAMPLE_DATA_XML_STRUCTURE_WAS_EDITED');
					return false;
				}

				foreach ($tables->children() as $table)
				{

					if ($table->_name != 'table')
					{
						$this->raiseError('MAINTENANCE_SAMPLE_DATA_XML_STRUCTURE_WAS_EDITED');
						return false;
					}

					foreach ($table->children() as $parameters)
					{
						if ($parameters->_name != 'parameters')
						{
							$this->raiseError('MAINTENANCE_SAMPLE_DATA_XML_STRUCTURE_WAS_EDITED');
							return false;
						}

						$countRecord = 0;
						foreach($parameters->children() as $parameter)
						{
							if($parameter->_name != 'parameter')
							{
								$this->raiseError('MAINTENANCE_SAMPLE_DATA_XML_STRUCTURE_WAS_EDITED');
								return false;
							}

							if($countRecord == 2 && $this->_limitEdition == true  && $tableType = 'dbinstall')// allow only two record
							{
								break;
							}

							$queries[] = trim($parameter->data());
							$countRecord++;
						}
					}
				}
			}
		}

		return $queries;
	}

	function _parseThemeTablesElementSampleData($objSimleXML, $tableType)
	{
		$config   = new JConfig();
		$dbprefix = $config->dbprefix;
		$queries 		= array();
		$attributesTask = $objSimleXML->attributes();

		if (isset($attributesTask ["name"]) && $attributesTask ["name"] == $tableType)
		{
			foreach ($objSimleXML->children() as $tables)
			{
				if ($tables->_name != 'tables')
				{
					$this->raiseError('MAINTENANCE_SAMPLE_DATA_XML_STRUCTURE_WAS_EDITED');
					return false;
				}

				foreach ($tables->children() as $table)
				{
					if ($table->_name != 'table')
					{
						$this->raiseError('MAINTENANCE_SAMPLE_DATA_XML_STRUCTURE_WAS_EDITED');
						return false;
					}

					$tableName = str_replace("#__", $dbprefix, $table->_attributes['name']);

					if (!in_array($tableName, $this->_themeTablesExist))
					{
						$this->_error = true;
						JError::raiseWarning(100,JText::sprintf('MAINTENANCE_SAMPLE_DATA_THEME_TABLE_NOT_EXIST', $table->_attributes['name']));
						return false;
					}

					foreach ($table->children() as $parameters)
					{
						if ($parameters->_name != 'parameters')
						{
							$this->raiseError('MAINTENANCE_SAMPLE_DATA_XML_STRUCTURE_WAS_EDITED');
							return false;
						}

						$countRecord = 0;
						foreach($parameters->children() as $parameter)
						{
							if($parameter->_name != 'parameter')
							{
								$this->raiseError('MAINTENANCE_SAMPLE_DATA_XML_STRUCTURE_WAS_EDITED');
								return false;
							}

							if($countRecord == 2 && $this->_limitEdition == true  && $tableType = 'dbinstall')// allow only two record
							{
								break;
							}

							$queries[] = trim($parameter->data());
							$countRecord++;
						}
					}
				}
			}
		}

		return $queries;
	}
}
?>