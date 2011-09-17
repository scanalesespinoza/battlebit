<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_cache.php 6648 2011-06-08 10:13:51Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

class JSNISCache {	
	
	public static function getInstance()
	{
		static $instanceCache;
		
		if ($instanceCache == null)
		{
			$instanceCache = new JSNISCache();
		}
		return $instanceCache;
	}
	
	function setCache ($request, $response, $extension='cache') {		
		$reqhash = md5(serialize($request));
		$file = JPATH_CACHE.DS.'jsnim_'.$reqhash . '.'.$extension;
		$fstream = fopen($file, "w");
		$result = fwrite($fstream,$response);
		fclose($fstream);
		return $result;	
	}
		
	function getCached ($request, $extension='cache') {
		$reqhash = md5(serialize($request));	
		$file = JPATH_CACHE.DS.'jsnim_'.$reqhash . '.'.$extension;	
		if (file_exists($file)) {											
			return file($file);
		}	
		return false;
	}
}
?>