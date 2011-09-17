<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_utils.php 7198 2011-07-08 08:17:43Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

class JSNISUtils
{
	var $_db = null;

	function JSNISUtils()
	{
		if ($this->_db == null)
		{
			$this->_db = JFactory::getDBO();
		}
	}

	public static function getInstance()
	{
		static $instanceUtils;
		if ($instanceUtils == null)
		{
			$instanceUtils = new JSNISUtils();
		}
		return $instanceUtils;
	}

	function getParametersConfig()
	{
		$query = 'SELECT * FROM #__imageshow_parameters';
		$this->_db->setQuery($query);

		return $this->_db->LoadObject();
	}

	function overrideURL()
	{
		$config = $this->getParametersConfig();

		if (!is_null($config) && $config->root_url == 2)
		{
			return JURI::base();
		}
		else
		{
			$pathURL 			= array();
			$uri				= JURI::getInstance();
			$pathURL['prefix'] 	= $uri->toString( array('scheme', 'host', 'port'));

			if (strpos(php_sapi_name(), 'cgi') !== false && !ini_get('cgi.fix_pathinfo') && !empty($_SERVER['REQUEST_URI']))
			{
				$pathURL['path'] =  rtrim(dirname(str_replace(array('"', '<', '>', "'"), '', $_SERVER["PHP_SELF"])), '/\\');
			}
			else
			{
				$pathURL['path'] =  rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
			}

			return $pathURL['prefix'].$pathURL['path'].'/';
		}
	}

	function checkSupportLang()
	{
		$objectReadxmlDetail 	= new JSNISReadXmlDetails();
		$infoXmlDetail 			= $objectReadxmlDetail->parserXMLDetails();
		$supportLang			= $infoXmlDetail['langs'];
		$objLanguage 			= JFactory::getLanguage();
		$language           	= $objLanguage->getTag();

		if (@in_array($language, $supportLang))
		{
    		return true;
		}
		return false;
	}

	function getAlterContent()
	{
		$script = "\n<script type='text/javascript'>\n";
		$script .= "window.addEvent('domready', function(){
						JSNISImageShow.alternativeContent();
					});";
		$script .= "\n</script>\n";
		return $script;
	}


	/*
	 *  encode url with special character
	 *
	 */
	function encodeUrl($url, $replaceSpace = false)
	{
		$encodeStatus = $this->encodeStatus($url);

		if ($encodeStatus == false)
		{
			$url = rawurlencode($url);
		}

		$url = str_replace('%3B', ";", $url);
	    $url = str_replace('%2F', "/", $url);
	    $url = str_replace('%3F', "?", $url);
	    $url = str_replace('%3A', ":", $url);
	    $url = str_replace('%40', "@", $url);
	    $url = str_replace('%26', "&", $url);
	    $url = str_replace('%3D', "=", $url);
	    $url = str_replace('%2B', '+', $url);
	    $url = str_replace('%24', "$", $url);
	    $url = str_replace('%2C', ",", $url);
	    $url = str_replace('%23', "#", $url);
	    $url = str_replace('%2D', "-", $url);
	    $url = str_replace('%5F', "_", $url);
	    $url = str_replace('%2E', ".", $url);
	    $url = str_replace('%21', "!", $url);
	    $url = str_replace('%7E', "~", $url);
	    $url = str_replace('%2A', "*", $url);
	    $url = str_replace('%27', "'", $url);
	    $url = str_replace('%22', "\"", $url);
	    $url = str_replace('%28', "(", $url);
	    $url = str_replace('%29', ")", $url);
		$url = str_replace('%5D', "]", $url);
	    $url = str_replace('%5B', "[", $url);

	    if ($replaceSpace == true)
	    {
	    	$url = str_replace('%20', " ", $url);
	    }
	    return $url;
	}

	/*
	 * encode array url
	 *
	 */
	function encodeArrayUrl($urls, $replaceSpace = false)
	{
		$arrayUrl =  array();
		foreach ($urls as $key => $value )
		{
			$url = $this->encodeUrl($value, $replaceSpace);
			$arrayUrl[$key] = $url;
		}

		return $arrayUrl;
	}

	//decode url that was encoded by encodeUrl()
	function decodeUrl($url)
	{
		$url = rawurldecode($url);
		return $url;
	}

	// check string was encoded
	function encodeStatus($string)
	{
		$regexp  = "/%+[A-F0-9]{2}/";
		if (preg_match($regexp,$string))
		{
			return true;
		}
		return false;
	}

	function getIDComponent()
	{
		$query 	= 'SELECT c.id FROM #__components c WHERE c.option="com_imageshow" AND c.parent = 0';
		$this->_db->setQuery($query);
		return $this->_db->loadAssoc();
	}

	function insertMenuSample($menuType)
	{
		$comID 	= $this->getIDComponent();
		$query 	= "INSERT INTO
						`#__menu`
						(`id`, `menutype`, `name`, `alias`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`, `lft`, `rgt`, `home`)
				   VALUES
				  		(NULL, '".$menuType."', 'JSN ImageShow', 'imageshow', 'index.php?option=com_imageshow&view=show', 'component', '1', '0', '".$comID['id']."', '0', '0', '0', '0000-00-00 00:00:00', '0', '0', '0', '0', 'showlist_id=1\nshowcase_id=1', '0', '0', '0')";
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	function checkComInstalled($comName)
	{
		$query 	= "SELECT * FROM #__extensions WHERE STRCMP(`element`, '".$comName."') = 0";
		$this->_db->setQuery($query);
		$result = $this->_db->loadRow();

		if (!empty($result))
		{
			return true;
		}
		return false;
	}

	function checkIntallModule()
	{
		$query 	= 'SELECT COUNT(*) FROM #__extensions WHERE element="mod_imageshow" AND type="module"';
		$this->_db->setQuery($query);
		$result = $this->_db->loadRow();

		if ($result[0] > 0)
		{
			return true;
		}
		return false;
	}

	function checkIntallPluginContent()
	{
		$query 	= 'SELECT COUNT(*) FROM #__extensions WHERE element="imageshow" AND folder="content" AND type="plugin"';
		$this->_db->setQuery($query);
		$result = $this->_db->loadRow();

		if ($result[0] > 0)
		{
			return true;
		}
		return false;
	}

	function checkIntallPluginSystem()
	{
		$query 	= 'SELECT COUNT(*) FROM #__extensions WHERE element="imageshow" AND folder="system" AND type="plugin"';
		$this->_db->setQuery($query);
		$result = $this->_db->loadRow();

		if ($result[0] > 0)
		{
			return true;
		}
		return false;
	}

	function getPluginContentInfo()
	{
		$query 	= 'SELECT * FROM #__extensions WHERE element="imageshow" AND folder="content" AND type="plugin"';
		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();
		return $result;
	}

	function getModuleInfo()
	{
		$db 	= JFactory::getDBO();
		$query 	= 'SELECT * FROM #__extensions WHERE element="mod_imageshow" AND type="module"';
		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();
		return $result;
	}

	function getComponentInfo()
	{
		$db 	= JFactory::getDBO();
		$query 	= 'SELECT * FROM #__extensions WHERE element="com_imageshow" AND type="component"';
		$this->_db->setQuery($query);
		return $this->_db->loadObject();
	}

	function clearData()
	{
		$queries [] = 'TRUNCATE TABLE `#__imageshow_configuration`';
		$queries [] = 'TRUNCATE TABLE `#__imageshow_showlist`';
		$queries [] = 'TRUNCATE TABLE `#__imageshow_showcase`';
		$queries [] = 'TRUNCATE TABLE `#__imageshow_images`';

		foreach ($queries as $query)
		{
			$query = trim($query);
			if ($query != '')
			{
				$this->_db->setQuery($query);
				$this->_db->query();
			}
		}
		return true;
	}

	function getTotalProfile()
	{
		$query 	= 'SELECT COUNT(*) FROM #__imageshow_configuration WHERE source_type <> 1';
		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}

	function drawXMLTreeByPath($path, $syncAlbum = array())
	{
		$dir = @opendir($path);
		$xml = '';
		while (false !== ($file = @readdir($dir)))
		{
			if (is_dir($path.DS.$file) && $file != '.' && $file != '..')
        	{
        		if (JPATH_ROOT == '')
				{
					$folderLevel = substr_replace($path, '', 0, 1);
				}
				else
				{
					$folderLevel = str_replace(JPATH_ROOT.DS, '', $path);
				}

				$folderLevel = $folderLevel.DS.$file;
        		$folderLevel = str_replace(DS, '/', $folderLevel);
        		$syncStatus  = (in_array($folderLevel, $syncAlbum)) ? ' state=\'checked\' ' : ' state=\'unchecked\' ';

        		$xml .= "<node label='". htmlspecialchars (utf8_encode($file), ENT_QUOTES) ."' data='". htmlspecialchars (utf8_encode($folderLevel), ENT_QUOTES) ."' ". $syncStatus .">\n";
				$xml .= JSNISUtils::drawXMLTreeByPath($path.DS.$file, $syncAlbum);
			}
	    }
	    $xml .= "</node>\n";

	    return $xml;
	}

	function getImageInPath($path = null)
	{
		jimport( 'joomla.filesystem.file' );

		if ($path == null ) return false;

		$arrayImage = array();

		if (!JFolder::exists($path))
		{
			return false;
		}

		$dir = @opendir($path);

//		$specialCharacter 	= 0;
		$data 				= new stdClass();
		$arrayImage 		= array();
//		$pattern = '/^[A-Za-z0-9_-\s]+[A-Za-z0-9_\.-\s]*([\\\\\/\s][A-Za-z0-9_-\s]+[A-Za-z0-9_\.-\s]*)*$/';

		while (false !== ($file = @readdir($dir)))
		{
			if (JFile::exists($path.DS.$file))
			{
				$fileInfo = pathinfo($path.DS.$file);

				if (preg_match('(png|jpg|jpeg|gif)',strtolower($fileInfo['extension'])))
				{
//					preg_match($pattern, (string)$file, $matches);
//
//					if (count($matches) == 0)
//					{
//						$specialCharacter++;
//					}
					$arrayImage[] = str_replace(DS, '/', $path.DS.$file);
				}
			}
        }

//      $data->specialCharacter = (boolean) $specialCharacter;
		natcasesort($arrayImage);
		$data->images		    = $arrayImage;
      	return $data;
    }

	function checkValueArray($arrayList, $index)
	{
		if (!array_key_exists($index, $arrayList))
		{
   			return false;
		}

		if ($arrayList[$index] != '')
		{
			return $arrayList[$index];
		}
		else
		{
			$index = $index - 1;
			return $this->checkValueArray($arrayList,$index);
		}
	}

	function wordLimiter($str, $limit = 100, $end_char = '&#8230;')
	{
		if (trim($str) == '')
			return $str;
		preg_match('/\s*(?:\S*\s*){'. (int) $limit .'}/', $str, $matches);
		if (strlen($matches[0]) == strlen($str))
			$end_char = '';
		return rtrim($matches[0]).$end_char;
	}

	function checkTmpFolderWritable()
	{
		$foldername = 'tmp';
		$folderpath = JPATH_ROOT.DS.$foldername;

		if (is_writable($folderpath) == false)
		{
			JError::raiseWarning(100, JText::sprintf('Folder "%s" is Unwritable. Please set Writable permission (CHMOD 777) for it before performing maintenance operations', DS.$foldername));
		}
		return true;
	}

	function renderMenuComboBox($ID, $elementText, $elementName, $parameters = '')
	{
		$query  = 'SELECT menutype AS value, title AS text FROM #__menu_types';
		$this->_db->setQuery($query);
		$data 	= $this->_db->loadObjectList();
		array_unshift($data, JHTML::_('select.option', '', '- '.JText::_($elementText).' -', 'value', 'text'));
		return JHTML::_('select.genericlist', $data, $elementName, $parameters, 'value', 'text', $ID);
	}

	function randSTR($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
	{
	    $charsLength 	= (strlen($chars) - 1);
	    $string 		= $chars{rand(0, $charsLength)};

	    for ($i = 1; $i < $length; $i = strlen($string))
	    {
	        $r = $chars{rand(0, $charsLength)};
	        if ($r != $string{$i - 1}) $string .=  $r;
	    }

	    return $string;
	}

	function getEdition()
	{
		$objJSNXML 		= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$infoXMLDetail 	= $objJSNXML->parserXMLDetails();

		if (!isset($infoXMLDetail) && !isset($infoXMLDetail['edition']))
		{
			return null;
		}

		return trim(strtolower($infoXMLDetail['edition']));
	}

	function getShortEdition()
	{
		$arrayStr = explode(' ', $this->getEdition());

		if (count($arrayStr) > 0)
		{
			return $arrayStr[0];
		}

		return null;
	}

	function callJSNButtonMenu()
	{
		jimport('joomla.html.toolbar');
		$path = JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers';
		$toolbar = JToolBar::getInstance('toolbar');
		$toolbar->addButtonPath($path);
		$toolbar->appendButton('JSNMenuButton');
	}

	function checkLimit()
	{
		$edition = $this->getShortEdition();

		if ($edition == 'pro')
		{
			return false;
		}

		return true;
	}

	function checkVersion()
	{
		$jsnProductInfo = 'http://www.joomlashine.com/joomla-extensions/jsn-imageshow-version-check.html';
		$objJSNHTTP   	= JSNISFactory::getObj('classes.jsn_is_httprequest', null, $jsnProductInfo);
		$result   		= $objJSNHTTP->DownloadToString();

		if (!$result)
		{
			return false;
		}
		else
		{
			$stringExplode = explode("\n", $result);
			return @$stringExplode[2];
		}
	}

	function getModuleInformation($moduleName)
	{
		$db 	= JFactory::getDBO();
		$query 	= 'SELECT * FROM #__modules WHERE module = '.$db->Quote($moduleName, false);
		$db->setQuery($query);
		return $db->loadObject();
	}

	function approveModule($moduleName, $publish = 1)
	{
		$db 	= JFactory::getDBO();
		$query 	= 'UPDATE #__modules SET published ='.$publish.' WHERE module = '.$db->Quote($moduleName, false);
		$db->setQuery($query);
		if (!$db->query())
		{
			return false;
		}
		return true;
	}


	function getJoomlaLevelName()
	{
		$db 	= JFactory::getDBO();
		$query 	= 'SELECT * FROM #__viewlevels';
		$db->setQuery($query);
		$items  = $db->loadObjectlist();
		$count  = count($items);
		$result = array();
		if($count)
		{
			for($i = 0; $i < $count; $i++)
			{
				$item = $items[$i];
				$result[$item->id] = strtolower($item->title);
			}
		}
		return $result;
	}

	function convertJoomlaLevelFromIDToName($data, $id)
	{
		$count = count($data);
		if($count)
		{
			return $data[$id];
		}
		return $id;
	}

	function convertJoomlaLevelFromNameToID($data, $name)
	{
		$count   = count($data);
		$default = '';
		$index   = 0;
		if ($count)
		{
			foreach ($data as $key => $value)
			{
				if (!$index)
				{
					$default = $key;
					$index   = 1;
				}
				if ($name == $value)
				{
					return $key;
				}
			}
			return $default;
		}
		return '';
	}

	function displayShowcaseMissingMessage()
	{
		$string = '<div class="jsn-missing-data-alert-box">';
		$string .= '<div class="header"><span class="icon-warning"></span><span class="message">'.JText::_('SITE_SHOWCASE_DATA_IS_NOT_CONFIGURED').'</span></div>';
		$string .= '<div class="footer"><span class="link-to-more">'.JText::_('SITE_SHOWCASE_CLICK_TO_LEARN_MORE').'</span></div></div>';
		return $string;
	}

	function displayShowlistMissingMessage()
	{
		$string = '<div class="jsn-missing-data-alert-box">';
		$string .= '<div class="header"><span class="icon-warning"></span><span class="message">'.JText::_('SITE_SHOWLIST_DATA_IS_NOT_CONFIGURED').'</span></div>';
		$string .= '<div class="footer"><span class="link-to-more">'.JText::_('SITE_SHOWLIST_CLICK_TO_LEARN_MORE').'</span></div></div>';
		return $string;
	}

	function displayShowlistNoImages()
	{
		$string = '<div class="jsn-missing-data-alert-box no-image">';
		$string .= '<div class="header"><span class="icon-warning"></span><span class="message">'.JText::_('SITE_SHOWLIST_NO_IMAGE').'</span></div>';
		$string .= '</div>';
		return $string;
	}

	function renderListItems($arrayItems, $type = "showlist")
	{
		$itemID 		 = $type.'_id';
		$itemTitle 		 = $type.'_title';
		$showlistAddText = JText::_('JSN_MENU_CREATE_NEW_SHOWLIST');
		$showcaseAddText = JText::_('JSN_MENU_CREATE_NEW_SHOWCASE');

		$html 		= '';
		$html = '<ul class="jsnis-list-items">';

		if (count($arrayItems) > 0)
		{
			$html .= '<li class="jsnis-list-menu-item">'.JText::_('JSN_MENU_RECENTLY_UPDATED').'</li>';

			foreach ($arrayItems as $item):
				$html .= '<li class="jsnis-list-item"><a href="index.php?option=com_imageshow&controller='.$type.'&task=edit&cid[]='.$item->$itemID.'">
						  	'.htmlspecialchars($item->$itemTitle).'
						  </a></li>';
			endforeach;
			$html .= '<li class="separator"></li>';
		}

		$html .= '<li><a class="additem icon-16" href="index.php?option=com_imageshow&controller='.$type.'&task=add" title="'.${$type.'AddText'}.'"><span>'.${$type.'AddText'}.'</span></a></li>';
		$html .= '</ul>';

		return $html;
	}

	function getExtensionInfoByID($id)
	{
		$query 	= 'SELECT * FROM #__extensions WHERE extension_id='.(int) $id;
		$this->_db->setQuery($query);
		return $this->_db->loadObject();
	}

	function getRemoteElementInfor($name, $edition)
	{
		$objJSNUtil     = JSNISFactory::getObj('classes.jsn_is_utils');

		$link		 	= CHECKUPDATE_LINK.'&name='.urlencode($name).'&edition='.urlencode($edition);
		$objJSNHTTP   	= JSNISFactory::getObj('classes.jsn_is_httprequest', null, $link);
		$objJSNJSON     = JSNISFactory::getObj('classes.jsn_is_json');
		$result    		= $objJSNHTTP->DownloadToString();
		$data			= array();
		if (!$result)
		{
			$data = array('connection' => false, 'version' => '', 'commercial'=>'');
		}
		else
		{
			$parse = $objJSNJSON->decode($result);
			$data  = array('connection' => true, 'version' => @$parse->version, 'commercial' => @$parse->commercial);
		}

		return $data;
	}

	function getAllCoreElements()
	{
		$datas				= array();
		$objJSNJSON     	= JSNISFactory::getObj('classes.jsn_is_json');
		$objJSNXML 			= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$infoXmlDetail    	= $objJSNXML->parserXMLDetails();
		$model 				= JModel::getInstance('plugins', 'imageshowmodel');
		$themes				= $model->getFullData();
		$coreInfo  			= $this->getComponentInfo();
		$coreName			= strtolower($infoXmlDetail['realName']);
		if(!is_null($coreInfo) && isset($coreInfo->manifest_cache) && $coreInfo->manifest_cache != '')
		{
			$coreData 						= $objJSNJSON->decode($coreInfo->manifest_cache);
			$datas[$coreName]['version'] 	= trim($coreData->version);
		}
		else
		{
			$datas[$coreName]['version'] = trim($infoXmlDetail['version']);
		}
		$datas[$coreName]['edition']	=	strtolower(trim($infoXmlDetail['edition']));
		$datas[$coreName]['name'] 		=	$infoXmlDetail['realName'];
		$datas[$coreName]['id'] 		=	$coreName;
		if (count($themes))
		{
			for($i = 0, $count = count($themes); $i < $count; $i++)
			{
				$themeItem 	= $themes[$i];
				$element	= strtolower(trim($themeItem->element));
				$datas[$element]['version'] = trim($themeItem->version);
				$datas[$element]['edition']	= '' ;
				$datas[$element]['name'] 	= strtolower(trim($themeItem->name));
				$datas[$element]['id'] 		= $element;
			}
		}
		return $datas;
	}

	function parseVersionString ($str)
	{
		return explode('.', $str);
	}

	function compareVersion($runningVersionParam, $latestVersionParam)
	{
		$check	= false;
		$runningVersion 		= $this->parseVersionString($runningVersionParam);
		$countRunningVersion 	= count($runningVersion);
		$latestVersion 			= $this->parseVersionString($latestVersionParam);
		$countLatestVersion 	= count($latestVersion);
		$count 					= 0;
		if	($countRunningVersion > $countLatestVersion)
		{
			$count = $latestVersion;
		}
		else
		{
			$count = $countRunningVersion;
		}

		$minIndex = $count - 1;

		for($i = 0; $i < $count; $i++)
		{
			if ($runningVersion[$i] < $latestVersion[$i])
			{
				$check = true;
				break;
			}
			elseif($runningVersion[$i] == $latestVersion[$i] && $i == $minIndex && $countRunningVersion < $countLatestVersion)
			{
				$check = true;
				break;
			}
			elseif($runningVersion[$i] == $latestVersion[$i])
			{
				continue;
			}
			else
			{
				break;
			}
		}

		return $check;
	}
}
?>