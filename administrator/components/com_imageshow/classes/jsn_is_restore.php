<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_restore.php 6677 2011-06-10 01:37:28Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.file');
require_once(JPATH_COMPONENT.DS.'classes'.DS.'jsn_is_upgradedbutil.php');
require_once(JPATH_COMPONENT.DS.'classes'.DS.'jsn_is_showcasetheme.php');
class JSNISRestoreDBUtil
{
	var $_fileName 		  = 'db_schema_upgrade.xml';
	var $_mainfest		  = null;
	var $_previousVersion = null;
	var $_currentVersion  = null;
	var $_versionIndexes  = array();
	var $_currentTables   = array();
	var $_upgradeDBAction = null;
	var $_items			  = array();

	function JSNISRestoreDBUtil()
	{
		$this->setObjUpgradeDBAction();
		$this->setCurrentVersion();
		$this->parserXMLContent();
	}

	function setObjUpgradeDBAction()
	{
		$this->_upgradeDBAction = new JSNJSUpgradeDBAction();
	}


	function setPreviousVersion($value)
	{
		$this->_previousVersion	= $value;
	}

	function setCurrentVersion()
	{
		$objectReadxmlDetail    = new JSNISReadXmlDetails();
		$infoXmlDetail 		    = $objectReadxmlDetail->parserXMLDetails();
		$this->_currentVersion	= @$infoXmlDetail['version'];
	}

	function setVersionIndex($key, $value)
	{
		$this->_versionIndexes[$key] = $value;
	}

	function getEndVersionIndex()
	{
		return end($this->_versionIndexes);
	}

	function getStartVersionIndex()
	{
		$previousVersion = $this->_previousVersion;
		if (isset($this->_versionIndexes[$previousVersion]))
		{
			return $this->_versionIndexes[$previousVersion];
		}
		else
		{
			$previousVersion = (float) str_replace('.', '', $previousVersion);
			foreach ($this->_versionIndexes as $key => $value)
			{
				$tmpPreviousVersion = (float) str_replace('.', '', $key);
				if ($tmpPreviousVersion > $previousVersion)
				{
					return $value;
				}
			}
		}
	}

	function setCurrentTable($key, $value)
	{
		$this->_currentTables[$key] = $value;
	}

	function extractArray($data, $begin, $end)
	{
		$newData = array();
		for($i = $begin; $i <= $end; $i++)
		{
			$newData [] = $data[$i];
		}
		return $newData;
	}

	function extractVersionRange()
	{
		$items 		   = $this->_items;
		$startIndex    = $this->getStartVersionIndex();
		$endIndex      = $this->getEndVersionIndex();
		$items 		   = $this->extractArray($items, $startIndex, $endIndex);
		return $items;
	}

	function parserXMLContent()
	{
		$versionsArray			= array();
		$filePath 				= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.$this->_fileName;
		$parser					= JFactory::getXMLParser('Simple');
		$resultLoadFile     	= $parser->loadFile($filePath);

		if($resultLoadFile == false)
		{
			return false;
		}

		$document	=& $parser->document;

		if($document->getElementByPath('version') != false)
		{
			$versions =& $document->version;
			if (count($versions))
			{
				for($i = 0, $count = count($versions); $i < $count; $i++)
				{
					$tablesArray		= array();
					$version    		= $versions[$i];
					$versionAttributes  = $version->attributes();
					$objVersion			= new stdClass();
					$objVersion->number = $versionAttributes['number'];
					$this->setVersionIndex($versionAttributes['number'], $i);

					if($version->getElementByPath('tables') != false)
					{
						$tables 	 =& $version->tables;
						$tableParent =& $tables[0]->table;

						if(count($tableParent))
						{
							for ($j = 0, $count1 = count($tableParent); $j < $count1; $j++)
							{
								$fieldsArray 		= array();
								$table 				= $tableParent[$j];
								$tableAttributes 	= $table->attributes();

								$objTable 			= new stdClass();
								$objTable->id	    = @$tableAttributes['id'];
								$objTable->status   = @$tableAttributes['status'];
								$objTable->name	    = @$tableAttributes['name'];

								$fields				=& $table->field;
								if(count($fields))
								{
									for ($z = 0, $count2 = count($fields); $z < $count2; $z++)
									{
										$field						= $fields[$z];
										$fieldAttributes 			= $field->attributes();

										$objField					= new stdClass();
										$objField->id				= $fieldAttributes['id'];
										$objField->status			= $fieldAttributes['status'];
										$objField->name				= (isset($fieldAttributes['name'])?$fieldAttributes['name']:'');
										$objField->type				= (isset($fieldAttributes['type'])?$fieldAttributes['type']:'');
										$objField->primary_key		= (isset($fieldAttributes['primary_key'])?$fieldAttributes['primary_key']:'');
										$objField->default_value	= (isset($fieldAttributes['default_value'])?$fieldAttributes['default_value']:'');
										$objField->not_null			= (isset($fieldAttributes['not_null'])?$fieldAttributes['not_null']:'yes');
										$fieldsArray []				= $objField;
									}
								}
								$objTable->fields	= $fieldsArray;
								$tablesArray []		= $objTable;
							}

						}

					}
					$objVersion->tables 	= $tablesArray;
					$versionsArray []		= $objVersion;
				}
			}
		}

		$this->_items = $versionsArray;

	}

	function searchField($tableID, $fieldID)
	{
		$result	= array();
		$items  = $this->_items;

		for ($i = 0, $count = count($items); $i < $count; $i++)
		{
			$data     = $items[$i];
			$tables   = $data->tables;
			$version  = $data->number;

			if (count($tables))
			{
				for ($j = 0, $count1 = count($tables); $j < $count1; $j++)
				{
					$table = $tables[$j];
					if ($table->id == $tableID)
					{
						$fields = $table->fields;
						if (count($fields))
						{
							for ($z = 0, $count2 = count($fields); $z < $count2; $z++)
							{
								$obj = new stdClass();
								$field = $fields[$z];

								if($field->id == $fieldID && JString::strtolower($field->status) != 'removed')
								{
									$obj->index   = $i;
									$obj->id      = $field->id;
									$obj->version = $version;
									$obj->status  = $field->status;
									$obj->name    = $field->name;
									$obj->type    = $field->type;
									$obj->not_null= $field->not_null;
									$result []    = $obj;
									break;
								}
							}
						}
						break;
					}
				}
			}
		}
		return $result;
	}

	function getFieldChanged($items)
	{
		$tableResult   = array();
		$fieldIDs	   = array();
		for ($i = 1, $count = count($items); $i < $count; $i++)
		{
			$data     	   = $items[$i];
			$version       = $data->number;
			$tables        = $data->tables;
			if (count($tables))
			{
				for ($j = 0, $count1 = count($tables); $j < $count1; $j++)
				{

					$table         = $tables[$j];
					$tableName     = $table->name;
					$tableID       = $table->id;
					$tableStatus   = $table->status;

					if (JString::strtolower($tableStatus) == 'changed')
					{
						$fields      = $table->fields;

						if (count($fields))
						{
							for ($z = 0, $count2 = count($fields); $z < $count2; $z++)
							{
								$field   	 = $fields[$z];
								$fieldID 	 = $field->id;
								$fieldStatus = JString::strtolower($field->status);

								if ($fieldStatus == 'changed' && !in_array($fieldID, $fieldIDs))
								{
									$tableResult [$tableName][] = $this->searchField($tableID, $fieldID);
									$fieldIDs []    			= $fieldID;
								}
							}
						}
					}
				}
				$fieldIDs = array_unique($fieldIDs);
			}
		}

		return $tableResult;
	}

	function getDataChanged()
	{
		$result	= array();
		if($this->_previousVersion == $this->_currentVersion) return $result;
		$items   			= $this->extractVersionRange();
		$preDataChange 		= $this->getFieldChanged($items);
		$dataChange			= $this->processDataChanged($preDataChange);
		return $dataChange;
	}

	function processDataChanged($data)
	{
		$tableResult = array();
		if(count($data))
		{
			foreach ($data as $key => $tables)
			{
				$tableName = $key;
				if(count($tables))
				{
					foreach ($tables as $fields)
					{
						$tmpArray = array();
						if(count($fields))
						{
							for ($i = 0, $count = count($fields); $i < $count; $i++)
							{
								$field = $fields[$i];
								if($field->version == $this->_previousVersion)
								{
									$originalFieldName = $field->name;
									break;
								}
								elseif ($this->_upgradeDBAction->isExistTableColumn($tableName, $field->name))
								{
									$originalFieldName = $field->name;
									break;
								}

							}

							$lastElement = end($fields);
							$tmpArray [$originalFieldName] = array('type' => $lastElement->type, 'change' => $lastElement->name, 'id' => $lastElement->id, 'not_null' => $lastElement->not_null);
							$tableResult [$tableName][]   	   = $tmpArray;
						}
					}
				}
			}
		}

		return $tableResult;
	}
}

class JSNISRestore
{
	var $path;
	var $file;
	var $compress;
	var $_document;
	var $_manifestInfo;
	var $_db;
	var $_fileRestore;
	var $_fieldChanged;
	function JSNISRestore($config = array('path'=> '', 'file'=>'', 'compress'=>''))
	{
		if (count($config) > 0)
		{
			$this->path 	= $config['path'];
			$this->file 	= $config['file'];
			$this->compress = $config['compress'];
		}

		$this->_db	= JFactory::getDBO();
		$this->_setManifestInfo();

	}

	function &getInstance()
	{
		static $instanceRestore;
		if ($instanceRestore == null)
		{
			$instanceRestore = new JSNISRestore();
		}
		return $instanceRestore;
	}

	function _loadRestoreFile()
	{
		$fileStoreName	 	 = "jsn_".JString::strtolower(@$this->_manifestInfo['realName']).'_backup_db.xml';
		$filepath	 		 = JPATH_ROOT.DS.'tmp'.DS.$fileStoreName;
		$this->_fileRestore  = $filepath;
		$parser				 = JFactory::getXMLParser('Simple');
		$loadFile			 = $parser->loadFile($filepath);

		if (!$loadFile)
		{
			$fileStoreName	 	 = "jsn_".JString::strtolower(@$this->_manifestInfo['realName']).'_pro_backup_db.xml';
			$filepath	 		 = JPATH_ROOT.DS.'tmp'.DS.$fileStoreName;
			$this->_fileRestore  = $filepath;
			if($parser->loadFile($filepath))
			{
				return $parser;
			}
			return false;
		}

		return $parser;
	}

	function _setDocument()
	{

		$loadFile = $this->_loadRestoreFile();

		if (!$loadFile)
		{
			$this->_document = null;
		}

		$this->_document = $loadFile->document;
	}

	function _setManifestInfo()
	{
		$objJSNXML 				= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$this->_manifestInfo 	= $objJSNXML->parserXMLDetails();
	}

	function importFile()
	{
		$getFileUpload		= $this->upload();

		switch ($this->compress)
		{
			case 1:
			case 2:
				$extractdir 	= JPath::clean(dirname($getFileUpload));
				$archivename 	= JPath::clean($getFileUpload);
				$result 		= JArchive::extract($archivename, $extractdir);
				break;
			case 0:
				break;
		}

		if (!$result)
		{
			return false;
		}
		else
		{
			$this->_setDocument();
			if (is_null($this->_document))
			{
				return false;
			}
			$attributeDocument  	= $this->_document->attributes();
			$versionRestore 		= (float) str_replace('.', '', $attributeDocument['version']);
			$versionCheck			= (float) str_replace('.', '', '2.3.0');
			$versionCheckMigrate	= (float) str_replace('.', '', '3.0.0');

			if($versionRestore < $versionCheck)
			{
				return 'outdated';
			}
			$this->_fieldChanged = $this->_getFieldChanged();
			if($versionRestore < $versionCheckMigrate)
			{
				$queries [] = $this->_migrateShowcase();
			}
			else
			{
				$queries []	= $this->_restoreShowcase();
				$queries []	= $this->_restoreShowcaseTheme();
			}
			$queries [] = $this->_restoreParameter();
			$queries []	= $this->_restoreShowlist();
			$queries []	= $this->_restoreConfiguration();

			$this->executeQuery($queries);
			$this->_upgradeShowcaseThemeData();
			$arrayFileDelte = array($archivename, $this->_fileRestore);
			JFile::delete($arrayFileDelte);
			return true;
		}
	}

	function executeQuery($datas)
	{
		if (count($datas))
		{
			foreach ($datas as $data)
			{
				if (count($data))
				{
					foreach ($data as $value)
					{
						$this->_db->setQuery($value);
						$this->_db->query();
					}
				}
			}
		}
	}

	function upload()
	{
		global $mainframe;
		$file = $this->file;

		$err  = null;
		if (isset($file['name']))
		{
			$filepath = JPath::clean($this->path.DS.$file['name']);
			if (!JFile::upload($file['tmp_name'], $filepath))
			{
				header('HTTP/1.0 400 Bad Request');
				die('Error. Unable to upload file');
				return false;
			}
			else
			{
				return $filepath;
			}
		}
	}


	function restore($config)
	{
		$this->JSNISRestore($config);
		$result = $this->importFile();
		return $result;
	}

	function _restoreShowcase()
	{
		$queries 		= array();
		$checkShowCase 	=& $this->_document->getElementByPath('showcases');

		if(!$checkShowCase) return $queries;

		$fieldChanged 	= array();
		if (count($this->_fieldChanged) && isset($this->_fieldChanged['#__imageshow_showcase']) && count($this->_fieldChanged['#__imageshow_showcase']))
		{
			foreach ($this->_fieldChanged['#__imageshow_showcase'] as $items)
			{
				foreach ($items as $key => $item)
				{
					$fieldChanged [$key] = $item['change'];
				}
			}
		}

		$showcaseRoot =& $this->_document->showcases;
		if ($showcaseRoot != null)
		{
			$showcase = @$showcaseRoot[0]->showcase;
			if (count($showcase))
			{
				for ($i = 0; $i < count($showcase); $i++)
				{
					$rows 			= $showcase[$i];
					$attributes [] 	= $rows->attributes();
				}
				if (count($attributes))
				{
					$queries [] = 'TRUNCATE #__imageshow_showcase';
					$queries [] = 'ALTER TABLE #__imageshow_showcase AUTO_INCREMENT = 1';
					foreach ($attributes as $attribute)
					{
						$fields 		= '';
						$fieldsValue 	= '';
						foreach ($attribute as $key => $value)
						{
							if (count($fieldChanged) && isset($fieldChanged[$key]))
							{
								$key = $fieldChanged[$key];
							}
							if ($this->_checkTableColumExist('#__imageshow_showcase', $key))
							{
								$fields 	 .= $key.',';
								$fieldsValue .= $this->_db->quote($value).',';
							}
						}
						$queries [] = 'INSERT #__imageshow_showcase ('.substr($fields, 0, -1).') VALUES ('.substr($fieldsValue, 0, -1).')';
					}
				}
			}
		}

		return $queries;
	}

	function _restoreConfiguration()
	{
		$queries 				= array();
		$checkConfiguration 	=& $this->_document->getElementByPath('configurations');

		if(!$checkConfiguration) return $queries;

		$fieldChanged 	= array();
		if (count($this->_fieldChanged) && isset($this->_fieldChanged['#__imageshow_configuration']) && count($this->_fieldChanged['#__imageshow_configuration']))
		{
			foreach ($this->_fieldChanged['#__imageshow_configuration'] as $items)
			{
				foreach ($items as $key => $item)
				{
					$fieldChanged [$key] = $item['change'];
				}
			}
		}

		$configurationRoot =& $this->_document->configurations;
		if ($configurationRoot != null)
		{
			$configuration = @$configurationRoot[0]->configuration;

			if (count($configuration))
			{
				for ($i = 0; $i < count($configuration); $i++)
				{
					$rows 				= $configuration[$i];
					$attributes [] 		= $rows->attributes();
				}
				if (count($attributes))
				{
					$queries [] = 'TRUNCATE #__imageshow_configuration';
					$queries [] = 'ALTER TABLE #__imageshow_configuration AUTO_INCREMENT = 1';
					foreach ($attributes as $attribute)
					{
						$fields			= '';
						$fieldsValue 	= '';
						foreach ($attribute as $key => $value)
						{
							if (count($fieldChanged) && isset($fieldChanged[$key]))
							{
								$key = $fieldChanged[$key];
							}

							if ($this->_checkTableColumExist('#__imageshow_configuration', $key))
							{
								$fields 	 .= $key.',';
								$fieldsValue .= $this->_db->quote($value).',';
							}
						}
						$queries [] = 'INSERT #__imageshow_configuration ('.substr($fields, 0, -1).') VALUES ('.substr($fieldsValue, 0, -1).')';
					}
				}
			}
		}

		return $queries;
	}

	function _restoreParameter()
	{
		$queries 		= array();
		$checkParameter =& $this->_document->getElementByPath('parameter');

		if(!$checkParameter) return $queries;

		$fieldChanged 	= array();
		if (count($this->_fieldChanged) && isset($this->_fieldChanged['#__imageshow_parameters']) && count($this->_fieldChanged['#__imageshow_parameters']))
		{
			foreach ($this->_fieldChanged['#__imageshow_parameters'] as $items)
			{
				foreach ($items as $key => $item)
				{
					$fieldChanged [$key] = $item['change'];
				}
			}
		}
		$parameter =& $this->_document->parameter;
		if ($parameter != null)
		{
			$attributes [] = $parameter[0]->attributes();
			if(count($attributes))
			{
				$queries [] = 'TRUNCATE #__imageshow_parameters';
				$queries [] = 'ALTER TABLE #__imageshow_parameters AUTO_INCREMENT = 1';
				foreach ($attributes as $attribute)
				{
					$fields			= '';
					$fieldsValue 	= '';

					foreach($attribute as $key => $value)
					{
						if (count($fieldChanged) && isset($fieldChanged[$key]))
						{
							$key = $fieldChanged[$key];
						}

						if ($this->_checkTableColumExist('#__imageshow_parameters', $key))
						{
							$fieldsValue 	.= $this->_db->quote($value).',';
							$fields 		.= $key.',';
						}
					}

					$queries [] = 'INSERT #__imageshow_parameters ('.substr($fields, 0, -1).') VALUES ('.substr($fieldsValue, 0, -1).')';
				}
			}
		}
		return $queries;
	}

	function _restoreShowlist()
	{
		$objJSNUtil  	  = JSNISFactory::getObj('classes.jsn_is_utils');
		$joomlaGroupLevel = $objJSNUtil->getJoomlaLevelName();

		$queries 	 	= array();
		$checkShowList 	=& $this->_document->getElementByPath('showlists');

		if(!$checkShowList) return $queries;

		$fieldChanged 	= array();
		if (count($this->_fieldChanged) && isset($this->_fieldChanged['#__imageshow_showlist']) && count($this->_fieldChanged['#__imageshow_showlist']))
		{
			foreach ($this->_fieldChanged['#__imageshow_showlist'] as $items)
			{
				foreach ($items as $key => $item)
				{
					$fieldChanged [$key] = $item['change'];
				}
			}
		}

		$showlists =& $this->_document->showlists;
		$showlist  = @$showlists[0]->showlist;
		if (count($showlist))
		{
			for ($i = 0; $i < count($showlist); $i ++ )
			{
				$rows 		= $showlist[$i];
				$attributes [] = $rows->attributes();
				$images =& $rows->image;
				if(count($images) > 0)
				{
					for ($y = 0 ; $y < count($images); $y++)
					{
						$image 				= $images[$y];
						$attributesImage [] = $image->attributes();
					}
				}
			}

			if(count($attributes))
			{
				$queries [] = 'TRUNCATE #__imageshow_showlist';
				$queries [] = 'ALTER TABLE #__imageshow_showlist AUTO_INCREMENT = 1';
				foreach ($attributes as $attribute)
				{
					$fields 		= '';
					$fieldsValue 	= '';
					foreach($attribute as $key => $value)
					{
						if (count($fieldChanged) && isset($fieldChanged[$key]))
						{
							$key = $fieldChanged[$key];
						}

						if ($key == 'access')
						{
							$value = $objJSNUtil->convertJoomlaLevelFromNameToID($joomlaGroupLevel, $value);
						}
						if ($this->_checkTableColumExist('#__imageshow_showlist', $key))
						{
							$fields 		.= $key.',';
							$fieldsValue 	.= $this->_db->quote($value).',';
						}
					}

					$queries [] = 'INSERT #__imageshow_showlist ('.substr($fields, 0, -1).') VALUES ('.substr($fieldsValue, 0, -1).')';
				}
			}

			if(count($attributesImage))
			{
				$fieldChanged 	= array();
				if (count($this->_fieldChanged) && isset($this->_fieldChanged['#__imageshow_images']) && count($this->_fieldChanged['#__imageshow_images']))
				{
					foreach ($this->_fieldChanged['#__imageshow_images'] as $items)
					{
						foreach ($items as $key => $item)
						{
							$fieldChanged [$key] = $item['change'];
						}
					}
				}

				$queries [] = 'TRUNCATE #__imageshow_images';
				$queries [] = 'ALTER TABLE #__imageshow_images AUTO_INCREMENT = 1';
				foreach ($attributesImage as $attributeImage)
				{
					$fields 		= '';
					$fieldsValue 	= '';
					foreach($attributeImage as $key => $value)
					{
						if (count($fieldChanged) && isset($fieldChanged[$key]))
						{
							$key = $fieldChanged[$key];
						}
						if ($this->_checkTableColumExist('#__imageshow_images', $key))
						{
							$fields 		.= $key.',';
							$fieldsValue 	.= $this->_db->quote($value).',';
						}
					}

					$queries [] = 'INSERT #__imageshow_images ('.substr($fields, 0, -1).') VALUES ('.substr($fieldsValue, 0, -1).')';
				}
			}
		}

		return $queries;
	}

	function _getShowcaseTheme()
	{
		$objJSNISShowcaseTheme  = JSNISShowcaseTheme::getInstance();
		$themes 				= $objJSNISShowcaseTheme->listThemes(false);
		$results		 		= array();
		if (count($themes))
		{
			foreach ($themes as $theme)
			{
				$results [] = $theme['element'];
			}
		}

		return $results;
	}

	function _restoreShowcaseTheme()
	{
		$queries		= array();
		$checkThemes 	=& $this->_document->getElementByPath('themes');

		if(!$checkThemes) return $queries;

		$themesRoot	   =& $this->_document->themes;
		$themes 		= $this->_getShowcaseTheme();

		if (count($themes))
		{
			foreach ($themes as $theme)
			{
				$themeName	     = JString::str_ireplace('theme', 'theme_', $theme);
				$fieldChanged 	 = array();
				$checkTableTheme = $this->_checkTableExist('#__imageshow_'.$themeName);
				if(!$checkTableTheme) continue;
				if (count($this->_fieldChanged) && isset($this->_fieldChanged['#__imageshow_'.$themeName]) && count($this->_fieldChanged['#__imageshow_'.$themeName]))
				{
					foreach ($this->_fieldChanged['#__imageshow_'.$themeName] as $items)
					{
						foreach ($items as $key => $item)
						{
							$fieldChanged [$key] = $item['change'];
						}
					}
				}

				$queries [] = 'TRUNCATE #__imageshow_'.$themeName;
				$queries [] = 'ALTER TABLE #__imageshow_'.$themeName.' AUTO_INCREMENT = 1';

				$attributes = array();

				$check 	=& $themesRoot[0]->getElementByPath($theme.'s');
				if ($check != false)
				{
					$root =& $themesRoot[0]->{$theme.'s'};
					if ($root != null)
					{
						$subRoot = @$root[0]->{$theme};

						if (count($subRoot))
						{
							for ($i = 0; $i < count($subRoot); $i++)
							{
								$rows 				= $subRoot[$i];
								$attributes [] 		= $rows->attributes();
							}
							if (count($attributes))
							{
								foreach ($attributes as $attribute)
								{
									$fields			= '';
									$fieldsValue 	= '';
									foreach ($attribute as $key => $value)
									{
										if (count($fieldChanged) && isset($fieldChanged[$key]))
										{
											$key = $fieldChanged[$key];
										}
										if ($this->_checkTableColumExist('#__imageshow_'.$themeName, $key))
										{
											$fields 	 .= $key.',';
											$fieldsValue .= $this->_db->quote($value).',';
										}
									}
									$queries [] = 'INSERT #__imageshow_'.$themeName.' ('.substr($fields, 0, -1).') VALUES ('.substr($fieldsValue, 0, -1).')';
								}
							}
						}
					}
				}

			}
		}
		return $queries;
	}

	function _getTableFields($table)
	{
		$fields			= array();
		$tableInfo 		= $this->_db->getTableFields($table, true);
		$countFields 	= count($tableInfo[$table]);
		if($countFields > 0)
		{
			foreach ($tableInfo[$table] as $key =>$value)
			{
				$fields [] = $key;
			}
		}
		return $fields;
	}

	function _migrateShowcase()
	{
		$index				= 1;
		$fieldsComparer	    = $this->_getTableFields('#__imageshow_showcase');
		$queries 			= array();
		$checkShowCase 		=& $this->_document->getElementByPath('showcases');
		$checkTableTheme 	= $this->_checkTableExist('#__imageshow_theme_classic');
		if ($checkShowCase != false)
		{
			$fieldChanged 	= array();
			if (count($this->_fieldChanged) && isset($this->_fieldChanged['#__imageshow_showcase']) && count($this->_fieldChanged['#__imageshow_showcase']))
			{
				foreach ($this->_fieldChanged['#__imageshow_showcase'] as $items)
				{
					foreach ($items as $key => $item)
					{
						$fieldChanged [$key] = $item['change'];
					}
				}
			}

			$showcaseRoot =& $this->_document->showcases;
			if ($showcaseRoot != null)
			{
				$showcase = @$showcaseRoot[0]->showcase;
				if (count($showcase))
				{
					for ($i = 0; $i < count($showcase); $i++)
					{
						$rows 			= $showcase[$i];
						$attributes [] 	= $rows->attributes();
					}
					if (count($attributes))
					{
						$queries [] = 'TRUNCATE #__imageshow_showcase';
						$queries [] = 'ALTER TABLE #__imageshow_showcase AUTO_INCREMENT = 1';
						if ($checkTableTheme)
						{
							$queries [] = 'TRUNCATE #__imageshow_theme_classic';
							$queries [] = 'ALTER TABLE #__imageshow_theme_classic AUTO_INCREMENT = 1';
						}
						foreach ($attributes as $attribute)
						{
							$fields 			= 'theme_id,theme_name,';
							$fieldsValue 		= "'".$index."','themeclassic',";
							$fieldsTheme		= '';
							$fieldsThemeValue	= '';
							foreach ($attribute as $key => $value)
							{

								if (in_array($key, $fieldsComparer))
								{
									$fields 	 .= $key.',';
									$fieldsValue .= $this->_db->quote($value).',';
								}
								else
								{
									if (count($fieldChanged) && isset($fieldChanged[$key]))
									{
										$key = $fieldChanged[$key];
									}

									if ($this->_checkTableColumExist('#__imageshow_theme_classic', $key))
									{
										$fieldsTheme	 	.= $key.',';
										$fieldsThemeValue 	.= $this->_db->quote($value).',';
									}
								}
							}

							$queries [] = 'INSERT #__imageshow_showcase ('.substr($fields, 0, -1).') VALUES ('.substr($fieldsValue, 0, -1).')';

							if ($checkTableTheme)
							{
								$queries [] = 'INSERT #__imageshow_theme_classic ('.substr($fieldsTheme, 0, -1).') VALUES ('.substr($fieldsThemeValue, 0, -1).')';
							}

							$index++;
						}
					}
				}
			}
		}
		return $queries;
	}

	function _checkTableExist($table)
	{
		$objUpgradeDBAction = new JSNJSUpgradeDBAction();
		return $objUpgradeDBAction->isExistTable($table);
	}

	function _checkTableColumExist($table, $column)
	{
		$objUpgradeDBAction = new JSNJSUpgradeDBAction();
		return $objUpgradeDBAction->isExistTableColumn($table, $column);
	}

	function _getFieldChanged()
	{
		$objJSNISRestoreDBUtil = new JSNISRestoreDBUtil();
		$attributeDocument  	= $this->_document->attributes();
		$versionRestore 		= $attributeDocument['version'];
		$objJSNISRestoreDBUtil->setPreviousVersion($versionRestore);
		return $objJSNISRestoreDBUtil->getDataChanged();
	}

	function _upgradeShowcaseThemeData()
	{
		require_once(JPATH_COMPONENT.DS.'classes'.DS.'jsn_is_upgradethemedb.php');
		$objJSNPlugins 		= JSNISFactory::getObj('classes.jsn_is_plugins');
		$objPluginModel		= JModel::getInstance('plugins', 'imageshowmodel');

		$existedPlugins     		= $objPluginModel->getFullData();
		$countExistedPlugins		= count($existedPlugins);

		$objTmpTheme    = new stdClass();
		$objTmpTheme->version = '1.0.0';

		if ($countExistedPlugins)
		{
			for ($i = 0; $i < $countExistedPlugins; $i++)
			{
				$item = $existedPlugins[$i];
				$data =  $objJSNPlugins->getXmlFile($item, false);
				if(!is_null($data))
				{
					$objUpgradeThemeDB 				= new JSNISUpgradeThemeDB($data, $objTmpTheme);
					$items   						= $objUpgradeThemeDB->extractVersionRange();
					$preDataChange 					= $objUpgradeThemeDB->getFieldChanged($items);
					$dataChange						= $objUpgradeThemeDB->processDataChanged($preDataChange);
					$queriesFieldDataChange 		= array($objUpgradeThemeDB->buildQueriesFieldDataChange($dataChange, true));
					$this->executeQuery($queriesFieldDataChange);
				}
			}
		}
	}
}