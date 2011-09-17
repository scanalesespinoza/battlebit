<?php
/**
 * @author    JoomlaShine.com http://www.joomlashine.com
 * @copyright Copyright (C) 2008 - 2011 JoomlaShine.com. All rights reserved.
 * @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version   $Id$
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted index access' );

require_once(dirname(__FILE__).DS.'includes'.DS.'lib'.DS.'jsn_httprequest.php');
require_once(dirname(__FILE__).DS.'includes'.DS.'lib'.DS.'jsn_ajax.php');
$obj_ajax = new JSNAjax();
$task = JRequest::getCmd('task');
switch($task)
{
	case 'checkCacheFolder':
		$obj_ajax->checkCacheFolder();
	break;
	case 'checkVersion':
		$obj_ajax->checkVersion();
	break;
	case 'checkFilesIntegrity':
		$obj_ajax->checkFilesIntegrity();
	break;
	default:
	break;
}
?>
