<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_json.php 6648 2011-06-08 10:13:51Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
class JSNISJSON 
{
	public static function getInstance()
	{
		static $instanceJSON;
		if ($instanceJSON == null)
		{
			$instanceJSON = new JSNISJSON();
		}
		return $instanceJSON;
	}
	
	function encode($dataObj)
	{
		return json_encode($dataObj);
	}
	
	function decode($dataObj)
	{
		return json_decode($dataObj);
	}
}