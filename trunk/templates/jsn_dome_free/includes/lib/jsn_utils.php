<?php
/**
 * @author    JoomlaShine.com http://www.joomlashine.com
 * @copyright Copyright (C) 2008 - 2011 JoomlaShine.com. All rights reserved.
 * @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
	// No direct access
	defined( '_JEXEC' ) or die( 'Restricted index access' );

	class JSNUtils {

		public function JSNUtils() {}
		/**
		 * Return class instance
		 *
		 */
		public static function getInstance() {
			static $instance;

			if ($instance == null) {
				$instance = new JSNUtils();
			}

			return $instance;
		}
		/**
		 * Get and store template attributes
		 *
		 */
		public function getTemplateAttributes($attrs_array, $template_prefix, $pageclass) {
			$template_attrs = null;
			if(count($attrs_array)) {
				foreach ($attrs_array as $attr_name => $attr_values) {
					$t_attr = null;

					// Get template settings from page class suffix
					if(!empty($pageclass)){
						$pc = 'custom-'.$attr_name.'-';
						$pc_len = strlen($pc);
						$pclasses = explode(" ", $pageclass);
						foreach($pclasses as $pclass){
							if(substr($pclass, 0, $pc_len) == $pc) {
								$t_attr = substr($pclass, $pc_len, strlen($pclass)-$pc_len);
							}
						}
					}
					if( isset( $_GET['jsn_setpreset'] ) && $_GET['jsn_setpreset'] == 'default' ) {
						setcookie($template_prefix.$attr_name, '', time() - 3600, '/');
					} else {
						// Apply template settings from cookies
						if (isset($_COOKIE[$template_prefix.$attr_name])) {
							$t_attr = $_COOKIE[$template_prefix.$attr_name];
						}

						// Apply template settings from permanent request parameters
						if (isset($_GET['jsn_set'.$attr_name])) {
							setcookie($template_prefix.$attr_name, trim($_GET['jsn_set'.$attr_name]), time() + 3600, '/');
							$t_attr = trim($_GET['jsn_set'.$attr_name]);
						}
					}

					// Store template settings
					$template_attrs[$attr_name] = null;
					if(is_array($attr_values)){
						if (in_array($t_attr, $attr_values)) {
							$template_attrs[$attr_name] = $t_attr;
						}
					} else if($attr_values == 'integer'){
						$template_attrs[$attr_name] = intval($t_attr);
					}
				}
			}

			return $template_attrs;
		}
		/**
		 * Get template details
		 *
		 */
		public function getTemplateDetails($templateBaseDir, $templateDir)
		{
			// Check of the xml file exists
			if (!is_file($templateBaseDir.DS.'templateDetails.xml')) {
				return false;
			}

			$xml = $this->parseXMLInstallFile($templateBaseDir.DS.'templateDetails.xml');

			if ($xml['type'] != 'template') {
				return false;
			}

			$data = new StdClass();
			$data->directory = $templateDir;

			foreach($xml as $key => $value) {
				$data->$key = $value;
			}

			$data->checked_out = 0;
			$data->mosname = JString::strtolower(str_replace(' ', '_', $data->name));

			return $data;
		}
		/**
		 * Get template parameters
		 *
		 */
		function getTemplateParameters()
		{
			return JFactory::getApplication()->getTemplate(true)->params;
		}
		/**
		 * Get the front-end template name
		 *
		 */
		public function getTemplateName()
		{
			$templateName 	= explode( DS, str_replace( array( '\includes\lib', '/includes/lib' ), '', dirname(__FILE__) ) );
			$templateName 	= $templateName [ count( $templateName ) - 1 ];

			return $templateName;
		}
		/**
		 * Template XML installation file parser
		 *
		 */
		public function parseXMLInstallFile($path)
		{
			$xml = JFactory::getXMLParser('Simple');

			if (!$xml->loadFile($path)) {
				unset($xml);
				return false;
			}
			if ( !is_object($xml->document) /*|| ($xml->document->name() != 'install' && $xml->document->name() != 'mosinstall')*/) {
				unset($xml);
				return false;
			}

			$data = array();
			//$data['legacy'] = $xml->document->name() == 'mosinstall';

			$element = $xml->document->name[0];
			$data['name'] = $element ? $element->data() : '';
			$data['type'] = $element ? $xml->document->attributes("type") : '';

			$element = $xml->document->creationDate[0];
			$data['creationdate'] = $element ? $element->data() : JText::_('Unknown');

			$element = $xml->document->author[0];
			$data['author'] = $element ? $element->data() : JText::_('Unknown');

			$element = $xml->document->copyright[0];
			$data['copyright'] = $element ? $element->data() : '';

			$element = $xml->document->authorEmail[0];
			$data['authorEmail'] = $element ? $element->data() : '';

			$element = $xml->document->authorUrl[0];
			$data['authorUrl'] = $element ? $element->data() : '';

			$element = $xml->document->version[0];
			$data['version'] = $element ? $element->data() : '';

			$element = @$xml->document->edition[0];
			$data['edition'] = $element ? $element->data() : '';

			$element = $xml->document->license[0];
			$data['license'] = $element ? $element->data() : '';

			$element = $xml->document->description[0];
			$data['description'] = $element ? $element->data() : '';

			$element = @$xml->document->group[0];
			$data['group'] = $element ? $element->data() : '';

			return $data;
		}
		/**
		 * Add template attribute to URL, used by Site Tools
		 *
		 */
		public function addAttributeToURL($key, $value) {
			$url = $_SERVER['REQUEST_URI'];
			$url = JFilterOutput::ampReplace($url);
			for($i = 0, $count_key = substr_count($url, 'jsn_set'); $i < $count_key; $i ++) {
				$url = preg_replace('/(.*)(\?|&)jsn_set[a-z]{0,30}=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
				$url = substr($url, 0, -1);
			}
		    if (strpos($url, '?') === false) {
		        return ($url . '?' . $key . '=' . $value);
		    } else {
		        return ($url . '&amp;' . $key . '=' . $value);
		    }
		}
		/**
		 * Return the number of module positions count
		 *
		 */
		public function countPositions($t, $positions) {
			$positionCount = 0;
			for($i=0;$i < count($positions); $i++){
				if ($t->countModules($positions[$i])) $positionCount++;
			}
			return $positionCount;
		}
		/**
		 * Get template positions
		 *
		 */
		public function getPositions($template)
		{
			jimport('joomla.filesystem.folder');
			$result 		= array();
			$client 		= JApplicationHelper::getClientInfo(0);

			if ($client === false)
			{
				return false;
			}

			$positions =  array();

			$path = $client->path.DS.'templates'.DS.$template;

			$xml = JFactory::getXMLParser('Simple');
			if ($xml->loadFile($path.DS.'templateDetails.xml'))
			{
				$p = $xml->document->getElementByPath('positions');
				if (is_a($p, 'JSimpleXMLElement') && count($p->children()))
				{
					foreach ($p->children() as $child)
					{
						if (!in_array($child->data(), $positions))
						{
							$positions[] = $child->data();

						}
					}

				}
			}

			$positions = array_unique($positions);
			if(count($positions))
			{
				foreach ($positions as $value)
				{
					$classModule 	= new stdClass();
					$classModule->value = $value;
					$classModule->text = $value;
					if(preg_match("/-m+$/", $value))
					{
						$result['mobile'] [] = $classModule;
					}
					else
					{
						$result['desktop'] [] = $classModule;
					}
				}
			}
			return $result;
		}
		/**
		 * render positions ComboBox
		 *
		 */
		public function renderPositionComboBox($ID, $data, $elementText, $elementName, $parameters = '')
		{
			array_unshift($data, JHTML::_('select.option', 'none', JText::_('NO_MAPPING'), 'value', 'text'));
			return JHTML::_('select.genericlist', $data, $elementName, $parameters, 'value', 'text', $ID);
		}
		/**
		 * Wrap first word inside a <span>
		 *
		 */
		public function wrapFirstWord( $value )
		{
		 	$processed_string =  null;
		 	$explode_string = explode(' ', trim( $value ) );
		 	for ( $i=0; $i < count( $explode_string ); $i++ )
		 	{
		 		if( $i == 0 )
		 		{
		 			$processed_string .= '<span>'.$explode_string[$i].'</span>';
		 		}
		 		else
		 		{
		 			$processed_string .= ' '.$explode_string[$i];
		 		}
		 	}

		 	return $processed_string;
		 }

		/**
		 * Trim precedding slash
		 *
		 */
		public function trimPreceddingSlash($string)
		{
			$string = trim($string);

			if (substr($string, 0, 1) == '\\' || substr($string, 0, 1) == '/') {
				$string = substr($string, 1);
			}

			return $string;
		}
		/**
		 * Trim ending slash
		 *
		 */
		public function trimEndingSlash($string)
		{
			$string = trim($string);

			if (substr($string, -1) == '\\' || substr($string, -1) == '/') {
				$string = substr($string, 0, -1);
			}

			return $string;
		}
		/**
		 * Trim both ending slash
		 *
		 */
		public function trimSlash($string)
		{
			$string = trim($string);

			$string = $this->trimPreceddingSlash($string);
			$string = $this->trimEndingSlash($string);

			return $string;
		}
		/**
		 * Strip extra space
		 *
		 */
		public function StripExtraSpace($s)
		{
			$newstr = "";
			for($i = 0; $i < strlen($s); $i++)
			{
				$newstr = $newstr.substr($s, $i, 1);
				if(substr($s, $i, 1) == ' ')
				while(substr($s, $i + 1, 1) == ' ')
				$i++;
			}
			return $newstr;
		}
		/**
		 * Get mobile device
		 *
		 */
		public function getMobileDevice()
		{
			$user_agent = $_SERVER['HTTP_USER_AGENT'];

			$mobileDeviceName = null;
			switch( true )
			{
				case ( preg_match( '/ipod/i', $user_agent ) || preg_match( '/iphone/i', $user_agent ) ):
					$mobileDeviceName = 'iphone';
				break;
				case ( preg_match( '/ipad/i', $user_agent ) ):
					$mobileDeviceName = 'ipad';
				break;
				case ( preg_match( '/android/i', $user_agent ) ):
					$mobileDeviceName = 'android';
				break;
				case ( preg_match( '/opera mini/i', $user_agent ) ):
					$mobileDeviceName = 'opera';
				break;
				case ( preg_match( '/blackberry/i', $user_agent ) ):
					$mobileDeviceName = 'blackberry';
				break;
				case ( preg_match( '/(pre\/|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i', $user_agent ) ):
					$mobileDeviceName = 'palm';
				break;
				case ( preg_match( '/(windows ce; ppc;|windows mobile;|windows ce; smartphone;|windows ce; iemobile)/i', $user_agent ) ):
					$mobileDeviceName = 'windows';
				break;
			}
			return $mobileDeviceName;
		}
		/**
		 * Check folder is writable or not.
		 *
		 */
		public function checkFolderWritable($path)
		{
			if (!is_writable($path)) {
				return false;
			}
			return true;
		}
		/**
		 * Clean up cache folder.
		 *
		 */
		public function cleanupCacheFolder($template_name = '', $css_js_compression = 0, $cache_folder_path)
		{
			if( $css_js_compression !=  1 && $css_js_compression != 2 ) {
				if( $handle = opendir($cache_folder_path) ) {
					while (false !== ($file = readdir($handle))) {
						$pattern = '/^'.$template_name.'_css/';
						if( preg_match($pattern, $file) > 0 ) {
						    @unlink($cache_folder_path.'/'.$file);
						}
				    }
				}
			}
			if( $css_js_compression !=  1 && $css_js_compression != 3 ) {
				if( $handle = opendir($cache_folder_path) ) {
					while (false !== ($file = readdir($handle))) {
						$pattern = '/^'.$template_name.'_javascript/';
						if( preg_match($pattern, $file) > 0 ) {
						    @unlink($cache_folder_path.'/'.$file);
						}
				    }
				}
			}
		}

		public function getAllFileInHeadSection(&$header_stuff, $type, &$ref_data)
		{
			$uri = JURI::base(true);

			if ($type == 'css')
			{
				$datas 	=& $header_stuff['styleSheets'];
				$file_extensions = '.css';
			}
			elseif ($type == 'js')
			{
				$datas =& $header_stuff['scripts'];
				$file_extensions = '.js';
			}

			foreach ($datas as $key=>$script)
			{
				if(stristr($key, $file_extensions) !== false)
				{
					if(substr($key, -(int)strlen($type)) == $type) {
						// Add to compression queue
						$last_sep 	= strrpos($key, '/');
						$file_name 	= substr($key, $last_sep + 1);
						$file_url	= str_replace($uri, "", substr($key, 0, $last_sep));
						// Determine the abs path of CSS files
						if(strpos($key, 'http://') !== false)
						{
							$juri_root_length = strlen(JURI::root());
							$file_abs_path = JPATH_ROOT.DS.substr($key, $juri_root_length, $last_sep - $juri_root_length);
						}
						else
						{
							$file_abs_path = JPATH_ROOT.str_replace("/", DS, $file_url);
						}

						$ref_data[$file_url.'/'.$file_name]['file_abs_path'] 	= $file_abs_path;
						$ref_data[$file_url.'/'.$file_name]['file_name']		= $file_name;

						// Remove them from HEAD
						unset($datas[$key]);
					}
				}
			}
		}

		function arrangeFileInHeadSection(&$header_stuff, $enable_jquery)
		{
			$datas  =& $header_stuff['scripts'];
			$count	= count($datas);
			if ($count)
			{
				$index 	= 0;
				$first 	= array();
				$last 	= array();
				$max    = $count - 1;
				if ($enable_jquery) $max -= 1;
				foreach ($datas as $key => $value)
				{
					if (!$index)
					{
						$first[$key] = $value;
					}

					if ($index >= $max)
					{
						$last[][$key] = $value;
					}
					$index++;
				}
				@array_pop($datas);
				@array_shift($datas);
				$result = @array_merge($first, $datas);
				foreach ($last as $l) {
					$result = @array_merge($l, $result);
				}				
				$datas  = $result;
			}
		}
		/**
		 * Check item menu is the last menu
		 *
		 */
		public function isLastMenu($item)
		{
			$dbo = JFactory::getDbo();
			if(isset($item->tree[0]) && isset($item->tree[1])) {
				$query = 'SELECT lft, rgt FROM #__menu'
					.' WHERE id = '.$item->tree[0]
					.' OR id = '.$item->tree[1];
			 	$dbo->setQuery($query);
			 	$results = $dbo->loadObjectList();

			 	if($results[1]->rgt == ( (int) $results[0]->rgt - 1) && $item->deeper) {
			 		return true;
			 	} else {
			 		return false;
			 	}
			} else {
				return false;
			}
		}

		/**
		 * Get browser specific information
		 *
		 */
		public function getBrowserInfo($agent = null)
		{
			$browser = array("browser"=>'', "version"=>'');
			$known = array("firefox", "msie", "opera", "chrome", "safari",
						"mozilla", "seamonkey", "konqueror", "netscape",
			            "gecko", "navigator", "mosaic", "lynx", "amaya",
			            "omniweb", "avant", "camino", "flock", "aol");
			$agent = strtolower($agent ? $agent : $_SERVER['HTTP_USER_AGENT']);
			foreach($known as $value)
			{
				if (preg_match("#($value)[/ ]?([0-9.]*)#", $agent, $matches))
				{
					$browser['browser'] = $matches[1];
					$browser['version'] = $matches[2];
					break;
				}
			}
			return $browser;
		}
		/**
		 * Get current URL
		 *
		 */
		public function getCurrentUrl() {
			$pageURL = 'http';
			if (!empty($_SERVER['HTTPS'])) {
				$pageURL .= "s";
			}
			$pageURL .= "://";
			if ($_SERVER["SERVER_PORT"] != "80") {
				$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			} else {
				$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			}
			return JFilterOutput::ampReplace($pageURL);
		}
		/**
		 * check System Cache - Plugin
		 *
		 */
		public function checkSystemCache() {
			$db = JFactory::getDbo();
			$query = "SELECT enabled " .
					" FROM #__extensions" .
					" WHERE name='plg_system_cache'"
			;
			$db->setQuery($query);
			return (bool) $db->loadResult();
		}
	}
?>