<?php
/**
 * @version		$Id: framework.php 21879 2011-07-17 22:33:34Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	Application
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

//
// Joomla system checks.
//

@ini_set('magic_quotes_runtime', 0);
@ini_set('zend.ze1_compatibility_mode', '0');

//
// Installation check, and check on removal of the install directory.
//

if (!file_exists(JPATH_CONFIGURATION.'/configuration.php') || (filesize(JPATH_CONFIGURATION.'/configuration.php') < 10) || file_exists(JPATH_INSTALLATION.'/index.php')) {

	if (file_exists(JPATH_INSTALLATION.'/index.php')) {
		header('Location: '.substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],'index.php')).'installation/index.php');
		exit();
	} else {
		echo 'No configuration file found and no installation code available. Exiting...';
		exit();
	}
}

//
// Joomla system startup.
//

// Import the cms version library if necessary.
if (!class_exists('JVersion')) {
    require JPATH_ROOT.'/includes/version.php';
}

// System includes.
require_once JPATH_LIBRARIES.'/import.php';

// Pre-Load configuration.
require_once JPATH_CONFIGURATION.'/configuration.php';

// System configuration.
$CONFIG = new JConfig();

if (@$CONFIG->error_reporting === 0) {
	error_reporting(0);
} else if (@$CONFIG->error_reporting > 0) {
	error_reporting($CONFIG->error_reporting);
	ini_set('display_errors', 1);
}

define('JDEBUG', $CONFIG->debug);

unset($CONFIG);

//
// Joomla framework loading.
//

// System profiler.
if (JDEBUG) {
	jimport('joomla.error.profiler');
	$_PROFILER = JProfiler::getInstance('Application');
}

//
// Joomla library imports.
//

jimport('joomla.application.menu');
jimport('joomla.user.user');
jimport('joomla.environment.uri');
jimport('joomla.filter.filterinput');
jimport('joomla.filter.filteroutput');
jimport('joomla.html.html');
jimport('joomla.utilities.utility');
jimport('joomla.event.event');
jimport('joomla.event.dispatcher');
jimport('joomla.language.language');
jimport('joomla.utilities.string');
jimport('joomla.utilities.arrayhelper');
