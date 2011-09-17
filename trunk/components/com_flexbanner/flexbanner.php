<?php
/**
* @copyright Copyright (C) 2011 Inch Communications Ltd. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/


// no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
require_once( JApplicationHelper::getPath('class', 'com_flexbanner'));
require_once ( JPATH_SITE . '/components/com_flexbanner/flexbanner.html.php');
jimport('joomla.application.component.helper');
$id = intval(JRequest::getVar('bannerid' ,NULL ));
if(JRequest::getVar('view') == 'client') { JRequest::setVar('task','client'); }
$task = JRequest::getVar('task' ,NULL );

switch($task) {
	case 'click':
		clickFlexBanner($id);
		break;
	case 'client':
	     viewFlexBannerClientBanners($option);
	     break;
	default:
          disableOldBanners();
          activateBanners();
          resetImpressions();
          break;
}

function viewFlexBannerClientBanners($option) {
	$user =& JFactory::getUser();
	$clid = $user->id; 	

	$mainframe = JFactory::getApplication();
	$database = &JFactory::getDBO();
	$limit = intval($mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mainframe->getCfg('list_limit'), 'int'));
	$limitstart = intval($mainframe->getUserStateFromRequest("viewban{$option}limitstart", 'limitstart', 0));

	// get the total number of records
	$query = "SELECT COUNT(*)" . "\n FROM #__fabanner";
	$database->setQuery($query);
	$total = $database->loadResult();

	require_once ( JPATH_SITE . '/plugins/content/pagenavigation/pagenavigation.php');
	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);

	$query = "SELECT * FROM #__fabanner
    		 WHERE juserid = $clid
             LIMIT $pageNav->limitstart, $pageNav->limit";
	$database->setQuery($query);

	if (!$result = $database->query()) {
		echo $database->stderr();
		return;
	}
	$rows = $database->loadObjectList();

	HTML_FlexBanner :: showClientbanners($rows, $pageNav, $option);

}

/* This function is taken almost completely from the Joomla standard joomla.php file 
   See that file, or any other standard Joomla file for copyright details in full */

	function FlexImages( $name, $active = NULL, $javascript = NULL, $directory = NULL, $extensions =  "/bmp|gif|jpg|png|swf/" )
	{
		if ( !$directory ) {
			$directory = '/images/stories/';
		}

		if ( !$javascript ) {
			$javascript = "onchange=\"javascript:if (document.forms.adminForm." . $name . ".options[selectedIndex].value!='') {document.imagelib.src='..$directory' + document.forms.adminForm." . $name . ".options[selectedIndex].value} else {document.imagelib.src='../images/blank.png'}\"";
		}

		jimport( 'joomla.filesystem.folder' );
		$imageFiles = JFolder::files( JPATH_SITE.DS.$directory );
		$images 	= array(  JHTML::_('select.option',  '', '- '. JText::_( 'Select Image' ) .' -' ) );
		foreach ( $imageFiles as $file ) {
		   if ( preg_match( $extensions, $file ) ) {
				$images[] = JHTML::_('select.option',  $file );
			}
		}
		$images = JHTML::_('select.genericlist',  $images, $name, 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );

		return $images;
	}
function clickFlexBanner($bannerid){
	$mainframe = JFactory::getApplication();
  $database = &JFactory::getDBO();
  $config = &JFactory::getConfig();
  $banner = new flexAdBanner($database);
  $banner->load($bannerid);
  $banner->clicks += 1;
  $banner->store();

  $link = new flexAdLink($database);
  $link->load($banner->linkid);
  $mainframe->redirect($link->linkurl);
}

function resetImpressions(){
	$mainframe = JFactory::getApplication();
  $database = &JFactory::getDBO();
  $config = &JFactory::getConfig();
  $sql = "UPDATE `#__fabanner` SET dailyimpressions=0, lastreset=curdate()
          WHERE lastreset<curdate() or lastreset IS NULL ";
  $database->setQuery($sql);
  $database->query();
}

function disableOldBanners(){
	$mainframe = JFactory::getApplication();
  $database = &JFactory::getDBO();
  $config = &JFactory::getConfig();
  $sql = "UPDATE `#__fabanner` SET finished=1, published=0
          WHERE (enddate < curdate() and enddate != '0000-00-00')
             OR (impressions >= maximpressions AND maximpressions != 0 )
             OR (clicks >= maxclicks AND maxclicks != 0)";
  $database->setQuery($sql);
  $database->query();
}

function activateBanners(){
	$mainframe = JFactory::getApplication();
  $database = &JFactory::getDBO();
  $config = &JFactory::getConfig();
  $sql = "UPDATE `#__fabanner` SET published=1
          WHERE startdate<=curdate() and finished=0  and startdate != '0000-00-00' and published!=2";
  $database->setQuery($sql);
  $database->query();
}

?>