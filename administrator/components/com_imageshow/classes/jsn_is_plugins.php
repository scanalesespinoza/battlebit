<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_plugins.php 6648 2011-06-08 10:13:51Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

class JSNISPlugins
{
	public static function getInstance()
	{
		static $instanceJSNPlugins;
		if ($instanceJSNPlugins == null)
		{
			$instanceJSNPlugins = new JSNISPlugins();
		}
		return $instanceJSNPlugins;
	}

	function getXmlFile($row, $convertToObj = true)
	{
		$baseDir = JPATH_ROOT.DS.'plugins';
		$xmlfile = $baseDir.DS.$row->folder.DS.$row->element.DS.$row->element.".xml";
		$result = new stdClass();
		if(file_exists($xmlfile))
		{
			if($convertToObj)
			{
				if($data = JApplicationHelper::parseXMLInstallFile($xmlfile))
				{
					foreach($data as $key => $value)
					{
						$result->$key = $value;
					}
				}
				return $result;
			}
			else
			{
				return JFactory::getXML($xmlfile);
			}
		}
		return null;
	}
}