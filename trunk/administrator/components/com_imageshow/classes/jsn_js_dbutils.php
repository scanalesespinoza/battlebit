<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_js_dbutils.php 6637 2011-06-08 08:21:13Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');

class JSNJSDBUtils
{
	function _getFields($table = '')
	{
		$result	= array();
		$db		= JFactory::getDBO();
		$query	= 'SHOW FIELDS FROM '.$db->nameQuote($table);
		$db->setQuery($query);
		$fields	= $db->loadObjectList();
		if(count($fields))
		{
			foreach ($fields as $field)
			{
				$result[$field->Field] = preg_replace('/[(0-9)]/' , '' , $field->Type);
			}
		}
		return $result;
	}

	function _getTables()
	{
		$result	= array();
		$db		= JFactory::getDBO();
		return  $db->getTableList();
	}

	function isExistTableColumn($tableName, $columnName)
	{
		$fields	= $this->_getFields($tableName);
		if (array_key_exists($columnName, $fields))
		{
			return true;
		}
		return false;
	}

	function isExistTable($tableName)
	{
		$tables 	= $this->_getTables();
		$config 	= JFactory::getConfig();
		$dbprefix   = $config->getValue('config.dbprefix');
		$tableName  = str_replace('#__', $dbprefix, $tableName);

		if (@in_array($tableName, $tables))
		{
			return 	true;
		}
		return false;
	}
}