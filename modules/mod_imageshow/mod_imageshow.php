<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: mod_imageshow.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.filesystem.file');
require_once (dirname(__FILE__).DS.'helper.php');
$fileFactory = JPATH_ROOT . DS. 'administrator'.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_factory.php';
if (JFile::exists($fileFactory))
{
	require_once $fileFactory;
	require(JModuleHelper::getLayoutPath('mod_imageshow'));
}
else
{
	$objModImageShowHelper = new modImageShowHelper();
	$objModImageShowHelper->approveModule('mod_imageshow', 0);
}