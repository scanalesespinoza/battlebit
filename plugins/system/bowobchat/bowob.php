<?php

/**
 * bowob.php,v 3.0 2011/05/17 17:25:17
 * @copyright (C) Jonatan Linares
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);
// Next two lines depend on the path of this file: /plugins/system/bowobchat/bowob.php
$_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'] = substr($_SERVER['SCRIPT_NAME'], 0, -34) . 'index.php';
define('JPATH_BASE', dirname(dirname(dirname(dirname(realpath(__FILE__))))));

require(JPATH_BASE . DS . 'includes' . DS . 'defines.php');
require(JPATH_BASE . DS . 'includes' . DS . 'framework.php');
$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();

include(dirname(realpath(__FILE__)) . DS . 'bowob_functions.php');

$bowob_type = JRequest::getInt('type', '-1', 'GET');

if($bowob_type == 2)
{
  bowob_server_sync();
}
elseif($bowob_type == 3)
{
  bowob_client_sync();
}
elseif($bowob_type == 4)
{
  bowob_redirect_login();
}
elseif($bowob_type == 5)
{
  bowob_redirect_profile();
}

?>
