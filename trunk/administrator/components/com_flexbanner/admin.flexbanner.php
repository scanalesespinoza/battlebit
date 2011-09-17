<?php

/**
* @copyright Copyright (C) 2009 inch communications ltd. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

// no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

// ensure user has access to this function
//$user = &JFactory::getUser();
//if (!($user->usertype == 'Super Administrator' || $user->usertype == 'Administrator')) {
//  $mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
//}
// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_flexbanner')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

require_once (JApplicationHelper::getPath('admin_html'));
require_once (JApplicationHelper::getPath('class'));
jimport('joomla.application.component.controller');
jimport('joomla.application.component.helper');
$database = JFactory::getDBO();

$cid = JRequest::getVar('cid', array (
	0
));
if (!is_array($cid)) {
	$cid = array (
		0
	);
}

$task = JRequest::getVar('task', NULL);
$option = JRequest::getVar('option', NULL);
$bannerid = JRequest::getVar('bannerid', NULL);
$id = JRequest::getVar('id', NULL);

JSubMenuHelper::addEntry(JText::_('ADMIN_FLEXBANNER_MENU_BANNERS'), 'index.php?option=com_flexbanner');
JSubMenuHelper::addEntry(JText::_('ADMIN_FLEXBANNER_MENU_CLIENTS'), 'index.php?option=com_flexbanner&amp;task=listClients');
JSubMenuHelper::addEntry(JText::_('ADMIN_FLEXBANNER_MENU_LINKS'), 'index.php?option=com_flexbanner&amp;task=listLinks');
JSubMenuHelper::addEntry(JText::_('ADMIN_FLEXBANNER_MENU_LOCATIONS'), 'index.php?option=com_flexbanner&amp;task=listLocations');
JSubMenuHelper::addEntry(JText::_('ADMIN_FLEXBANNER_MENU_SIZES'), 'index.php?option=com_flexbanner&amp;task=listSizes');

switch ($task) {
       case 'newBanner':
            editFlexBannerBanner(0, $option);
            break;
       case 'editBanner':
            editFlexBannerBanner($bannerid, $option);
            break;
       case 'editBannerC':
            editFlexBannerBanner($cid[0], $option);
            break;
       case 'saveBanner':
            saveFlexBannerBanner($task, $option);
            break;
       case 'resethits':
            resetFlexBannerBanner($task, $option);
            break;
       case 'listBanners':
            viewFlexBannerBanners($option);
            break;
       case 'deleteBanner':
            deleteFlexBannerBanner($cid, $option);
            break;
       case 'publishBanners':
            publishFlexBannerBanners($cid, $option);
            break;
       case 'unpublishBanners':
            unpublishFlexBannerBanners($cid, $option);
            break;

       case 'cancelSize':
          $row = new flexAdSize($database);
          if ($sizeid) {
            $row->load($sizeid);
  	    $row->checkin();
	  }
       case 'listSizes':
            viewFlexBannerSizes($option);
            break;
       case 'newSize': $id=0;
       case 'editSize':
            editFlexBannerSize($id, $option);
            break;
       case 'saveSize':
            saveFlexBannerSize($task, $option);
            break;
       case 'deleteSize':
            deleteFlexBannerSize($cid, $option);
            break;

       case 'cancelLocation':
          $row = new flexAdLocation($database);
          if ($locationid) {
            $row->load($locationid);
  	    $row->checkin();
	  }
       case 'listLocations':
            viewFlexBannerLocations($option);
            break;
       case 'newLocation': $id=0;
       case 'editLocation':
            editFlexBannerLocation($id, $option);
            break;
       case 'saveLocation':
            saveFlexBannerLocation($task, $option);
            break;
       case 'deleteLocation':
            deleteFlexBannerLocation($cid, $option);
            break;

       case 'cancelClient':
          $row = new flexAdClient($database);
          if ($clientid) {
            $row->load($clientid);
  	    $row->checkin();
	  }
       case 'listClients':
           viewFlexBannerClients($option);
            break;
       case 'newClient': $id=0;
       case 'editClient':
            editFlexBannerClient($id, $option);
            break;
       case 'saveClient':
            saveFlexBannerClient($task, $option);
            break;
       case 'deleteClient':
            deleteFlexBannerclient($cid, $option);
            break;

       case 'cancelLink':
          $row = new flexAdLink($database); 
          if ($linkid) {
            $row->load($linkid);
  	    $row->checkin();
	  }
       case 'listLinks':
            viewFlexBannerLinks($option); 
            break;
       case 'newLink': $id=0;
       case 'editLink':
            editFlexBannerLink($id, $option);
            break;
       case 'saveLink':
            saveFlexBannerLink($task, $option);
            break;
       case 'deleteLink':
            deleteFlexBannerLink($cid, $option);
            break;

       case 'cancelBanner':
          $row = new flexAdBanner($database);
          if ($bannerid) {
            $row->load($bannerid);
  	    $row->checkin();
	  }
       default :
            viewFlexBannerBanners($option);
	    break;
}

function viewFlexBannerBanners($option) {
	$mainframe = JFactory::getApplication();
	$database = JFactory::getDBO();
	$limit = intval($mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mainframe->getCfg('list_limit'), 'int'));
	$limitstart = intval($mainframe->getUserStateFromRequest("viewban{$option}limitstart", 'limitstart', 0));

	// get the total number of records
	$query = "SELECT COUNT(*)" . "\n FROM #__fabanner";
	$database->setQuery($query);
	$total = $database->loadResult();

	require_once ( JPATH_SITE . '/plugins/content/pagenavigation/pagenavigation.php');
	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);

	$query = "SELECT b.*, u.name AS editor" . "\n FROM #__fabanner AS b
                  LEFT JOIN #__users AS u ON u.id = b.checked_out
                  ORDER BY b.imagealt
                  LIMIT $pageNav->limitstart, $pageNav->limit";
	$database->setQuery($query);

	if (!$result = $database->query()) {
		echo $database->stderr();
		return;
	}
	$rows = $database->loadObjectList();

	HTML_FlexBanner :: showBanners($rows, $pageNav, $option);
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

function editFlexBannerBanner($bannerid, $option) {
	$mainframe = JFactory::getApplication();
	$database = JFactory::getDBO();
	$my = JFactory::getUser();
	$fb_language = 0;
	if (file_exists(JPATH_SITE . '/administrator/components/com_joomfish/joomfish.php')) {
	$fb_language = 1; }
	$lists = array ();

	$sql = "SELECT * FROM #__falocation";
	$database->setQuery($sql);
	$result = $database->query();
	if ($database->getNumRows() == 0) {
		$mainframe->redirect("index.php?option=$option&task=listLocations", JText::_('ADMIN_FLEXBANNER_ADDLOCATION'), 'message'); 
		}

	$row = new flexAdBanner($database);

	if ($bannerid) {
          $row->load($bannerid);
 	 	  $row->checkout($my->id);
	}


	// Imagelist
	$javascript = 'onchange="changeDisplayImage();"';
	$directory = '/images/banners';
	$lists['imageurl'] = JHTML::_('list.images','imageurl', $row->imageurl, $javascript, $directory);
        $lists['imageurl'] = FlexImages('imageurl', $row->imageurl, $javascript, $directory);

    $sql = "SELECT linkid, linkurl FROM #__falink";
	$database->setQuery($sql);
	if (!$database->query()) {
		echo $database->stderr();
		return;
	}

	$linklist[] = JHTML::_( 'select.option', '0', JText::_('ADMIN_FLEXBANNER_NEWLINK'), 'linkid', 'linkurl');
	$linklist = array_merge($linklist, $database->loadObjectList());
	$lists['linkid'] = JHTML ::_('select.genericlist',$linklist, 'linkid', 'class="inputbox" size="1" onchange="toggleLinkFields();"', 'linkid', 'linkurl', $row->linkid);


    $sql = "SELECT clientid, clientname FROM #__faclient WHERE barred=0";
	$database->setQuery($sql);
	if (!$database->query()) {
		echo $database->stderr();
		return;
	}

	$clientlist[] = JHTML::_( 'select.option','0', JText::_('ADMIN_FLEXBANNER_NEWCLIENT'), 'clientid', 'clientname');
	$clientlist = array_merge($clientlist, $database->loadObjectList());
	$lists['clientid'] = JHTML ::_( 'select.genericlist',$clientlist, 'clientid', 'class="inputbox" size="1" onchange="toggleClientFields();"', 'clientid', 'clientname', $row->clientid);

// create linked to list 
    $sql = "SELECT DISTINCT (#__fabanner.juserid),#__users.id,Concat(#__users.name,' (', #__users.username,')') AS userfield FROM #__fabanner RIGHT JOIN #__users ON #__fabanner.juserid=#__users.id";
	$database->setQuery($sql);
	if (!$database->query()) {
		echo $database->stderr();
		return;
	}

	$linkedlist[] = JHTML::_( 'select.option','0', JText::_('ADMIN_FLEXBANNER_LINKEDTO'), 'id', 'userfield');
	$linkedlist = array_merge($linkedlist, $database->loadObjectList());
	$lists['linkedto'] = JHTML ::_( 'select.genericlist',$linkedlist, 'juserid', 'class="inputbox" size="1"', 'id', 'userfield', $row->juserid);

// Build Location select list
	$sql = "SELECT locationid, locationname FROM #__falocation";
	$database->setQuery($sql);
	if (!$database->query()) {
		echo $database->stderr();
		return;
	}
	$locations = $database->loadObjectList();
	$loclist = array();
	$locsel = array();

        if ($bannerid ){
  	  $selsql = "SELECT bannerid, locationid
                     FROM #__fabannerlocation
                     WHERE bannerid = $bannerid";
	  $database->setQuery($selsql);
	  if (!$database->query()) {
		echo $database->stderr();
		return;
	  }
	  $locsel = $database->loadObjectList();
        }
        foreach ($locations as $location){
          $loclist[] = JHTML::_('select.option',$location->locationid,$location->locationname, 'locationid', 'locationname');
        }
	$lists['locationid'] = JHTML ::_('select.genericlist',$loclist, 'locationid[]', 'class="inputbox" size="5" multiple="true"', 'locationid', 'locationname', $locsel);

        // Build Category select list
	$sql = "SELECT id, title FROM #__categories";
	$database->setQuery($sql);
	if (!$database->query()) {
		echo $database->stderr();
		return;
	}
	$categorylist = $database->loadObjectList();

       	$selectedcategories = array();

	if ($bannerid){
  	  $sql = "SELECT bannerid, categoryid as id FROM #__fabannerin WHERE bannerid= $bannerid";
       	  $database->setQuery($sql);
	  if (!$database->query()) {
		echo $database->stderr();
		return;
	  }
	  $selectedcategories = $database->loadObjectList();
	}

	foreach ($categorylist as $categoryentry){
          $catlist[] = JHTML::_('select.option',$categoryentry->id, $categoryentry->title, 'id','title');
        }

        $lists['categoryid'] = JHTML ::_('select.genericlist',$catlist, 'categoryid[]', 'class="inputbox" size="5" multiple="true"', 'id', 'title', $selectedcategories);

        // Build Content select list
//        $lists['contentbox'] = JHTML ::_('select.integerlist',5,50,5,'contentbox', 'onchange="changeContentBox();"', $contentbox);

	$sql = "SELECT id, title FROM #__content order by title";
	$database->setQuery($sql);
	if (!$database->query()) {
		echo $database->stderr();
		return;
	} 

	$contentlist = $database->loadObjectList();
	if(count($contentlist) < 1)
	$contentlist = array(id=> 0);

       	$selectedcontent = array();

       	if ($bannerid){
	  $sql = "SELECT bannerid, contentid as id FROM #__fabannerin WHERE bannerid= $bannerid";
       	  $database->setQuery($sql);
	  if (!$database->query()) {
		echo $database->stderr();
		return;
	  }
	  $selectedcontent = $database->loadObjectList();
	}

	foreach ($contentlist as $contententry){
          $contentfield = $contententry->title." (".$contententry->id.")";
          $contlist[] = JHTML::_('select.option',$contententry->id, $contentfield, 'id','title');
        }
        $lists['contentid'] = JHTML ::_('select.genericlist',$contlist, 'contentid[]', 'class="inputbox2" size="20", multiple="true"', 'id', 'title', $selectedcontent);

if ($fb_language == 1) {
       // Build Language select list
	$sql = "SELECT code AS shortcode, name FROM #__languages";
	$database->setQuery($sql);
	if (!$database->query()) {
		echo $database->stderr();
		return;
	}
	$languagelist = $database->loadObjectList();
       	$selectedlanguages = array();
       	if ($bannerid){
	  $sql = "SELECT bannerid, languageid as shortcode FROM #__fabannerlang WHERE bannerid= $bannerid";
       	  $database->setQuery($sql);
	  if (!$database->query()) {
		echo $database->stderr();
		return;
	  }
	  $selectedlanguages = $database->loadObjectList();
	}

	foreach ($languagelist as $languageentry){
          $langlist[] = JHTML::_('select.option',$languageentry->shortcode, $languageentry->name, 'shortcode','name');
       }
	  if (empty($selectedlanguages)) {$selectedlanguages = array($langlist[0]);} 
        $lists['languageid'] = JHTML ::_('select.genericlist',$langlist, 'languageid[]', 'class="inputbox" size="5" multiple="true"', 'shortcode', 'name', $selectedlanguages);
}
        $sizelist = array();
        $sql = "SELECT sizeid AS value, sizename AS text FROM #__fasize";
	$database->setQuery($sql);
	if (!$database->query()) {
		echo $database->stderr();
		return;
	}

	$sizelist = array_merge($sizelist, $database->loadObjectList());
	$lists['sizeid'] = JHTML ::_('select.genericlist',$sizelist, 'sizeid', 'class="inputbox" size="1" ', 'value', 'text', $row->sizeid);


	// make the select list for the image positions
	$yesno[] = JHTML::_('select.option','0', JText::_('ADMIN_FLEXBANNER_NO'));
	$yesno[] = JHTML::_('select.option','1', JText::_('ADMIN_FLEXBANNER_YES'));

        $lists['published'] = JHTML ::_('select.genericlist',$yesno, 'published', 'class="inputbox" size="1"', 'value', 'text', $row->published);
        $lists['restrictbyid'] = JHTML ::_('select.genericlist',$yesno, 'restrictbyid', 'class="inputbox" size="1"  onchange="toggleRestrictFields();"', 'value', 'text', $row->restrictbyid);
        $lists['frontpage'] = JHTML ::_('select.genericlist',$yesno, 'frontpage', 'class="inputbox" size="1"  onchange="toggleRestrictFields();"', 'value', 'text', $row->frontpage);

	HTML_FlexBanner :: bannerForm($row, $lists, $option);
}

function resetFlexBannerBanner($task) {
$mainframe = JFactory::getApplication();
$database = JFactory::getDBO();
	$banner = new flexAdBanner($database);
	$bannerid = intval(JArrayHelper::getValue( $_REQUEST, 'bannerid' ,NULL ));
	if (!is_null($bannerid)){
          $banner->load($bannerid);
	}
		// Resets clicks when `Reset Clicks` button is used instead of `Save` button
        $sql = "update #__fabanner SET clicks = 0 where bannerid =  $banner->bannerid";
        $database->setQuery( $sql );
	    $database->query();

	$mainframe->redirect('index.php?option=com_flexbanner&task=listBanners', $msg);
	echo JText::_('ADMIN_FLEXBANNER_SAVEBANNER');

}

function saveFlexBannerBanner($task) {
$mainframe = JFactory::getApplication();
$database = JFactory::getDBO();
	$fb_language = 0;

	if (file_exists(JPATH_SITE . '/administrator/components/com_joomfish')) {
	$fb_language = 1; }

        $clientid = intval(JArrayHelper::getValue( $_REQUEST, 'clientid' ,NULL ));
        $juserid = intval(JArrayHelper::getValue( $_REQUEST, 'juserid' ,NULL ));
        $linkid = intval(JArrayHelper::getValue( $_REQUEST, 'linkid' ,NULL ));
        $restrictbyid =intval(JArrayHelper::getValue( $_REQUEST, 'restrictbyid', NULL ));
        $frontpage =intval(JArrayHelper::getValue( $_REQUEST, 'frontpage', NULL ));
        if ($clientid == 0){
          $clientname = JArrayHelper::getValue( $_REQUEST, 'clientname' ,NULL );
          $contactname = JArrayHelper::getValue( $_REQUEST, 'contactname' ,NULL );
          $clientemail = JArrayHelper::getValue( $_REQUEST, 'clientemail' ,NULL );

          $client = new flexAdClient($database);
          $client->clientname = $clientname;
          $client->contactname = $contactname;
          $client->contactemail = $clientemail;
          $client->store();
          $clientid = $client->clientid;
        }

        if ($linkid == 0){
          $linkurl = JArrayHelper::getValue( $_REQUEST, 'linkurl' ,NULL );
          $link = new flexAdLink($database);

		    if((preg_match("/http:\/\//", $linkurl)) || (preg_match("/https:\/\//", $linkurl))) {
		        $linkurl=$linkurl;
		    } else {
		        $linkurl="http://".$linkurl;
		    }

          $link->linkurl = $linkurl;
          $link->clientid = $clientid;
          $link->store();
          $linkid = $link->linkid;
        }

	$banner = new flexAdBanner($database);
	$bannerid = intval(JArrayHelper::getValue( $_REQUEST, 'bannerid' ,NULL ));
	if (!is_null($bannerid)){
          $banner->load($bannerid);
        }
        $banner->clientid = $clientid;
        $banner->linkid = $linkid;
        $banner->imageurl = JArrayHelper::getValue( $_REQUEST, 'imageurl', NULL );
        $banner->sizeid = JArrayHelper::getValue( $_REQUEST, 'sizeid', 1); 
        $banner->imagealt = JArrayHelper::getValue( $_REQUEST, 'imagealt', NULL );
        $banner->startdate = JArrayHelper::getValue( $_REQUEST, 'startdate', NULL );
        $banner->enddate = JArrayHelper::getValue( $_REQUEST, 'enddate', NULL );
        $banner->maximpressions = intval(JArrayHelper::getValue( $_REQUEST, 'maximpressions', NULL ));
        $banner->maxclicks = intval(JArrayHelper::getValue( $_REQUEST, 'maxclicks', NULL ));
        $banner->maxclicks = intval(JArrayHelper::getValue( $_REQUEST, 'maxclicks', NULL ));
        $banner->customcode = JArrayHelper::getValue( $_REQUEST, 'customcode', NULL , _MOS_ALLOWRAW);
        $banner->published = intval(JArrayHelper::getValue( $_REQUEST, 'published', NULL ));
        $banner->restrictbyid = $restrictbyid;
        $banner->frontpage = $frontpage;
        $banner->lastreset = null;
        $banner->juserid = $juserid;

	if (!$banner->store()) {

		echo "<script> alert('" . $banner->getError() . "'); window.history.go(-1); </script>\n";
		exit ();
	}

        $banner->checkin();

        // Store the location details for the banner

        $sql = "DELETE FROM #__fabannerlocation WHERE bannerid = $banner->bannerid";
       	$database->setQuery( $sql );
	$database->query();

	$bannerlocations = JArrayHelper::getValue( $_POST, 'locationid', array() );
	if (empty($bannerlocations)) {
		$locerror = JText::_('ADMIN_FLEXBANNER_LOCATION_ERROR');
		echo "<script> alert('" . $locerror . "'); window.history.go(-1); </script>\n";
		exit();
		}
	foreach($bannerlocations as $bannerlocation){
          $sql = "INSERT into #__fabannerlocation SET bannerid= $banner->bannerid, locationid = $bannerlocation";
          $database->setQuery( $sql );
	  $database->query();
        }

		// Write finished flag
        $banner->startdate = JArrayHelper::getValue( $_REQUEST, 'startdate', NULL );
        $banner->enddate = JArrayHelper::getValue( $_REQUEST, 'enddate', NULL );
  $sql = "UPDATE `#__fabanner` SET finished=1, published=0
          WHERE (enddate < curdate() and enddate != '0000-00-00')
             OR (impressions >= maximpressions AND maximpressions != 0 )
             OR (clicks >= maxclicks AND maxclicks != 0)";
  $sql = "UPDATE `#__fabanner` SET finished=0
          WHERE (enddate >= curdate() OR enddate = '0000-00-00')
             AND (impressions < maximpressions OR maximpressions = 0 )
             AND (clicks < maxclicks OR maxclicks = 0)";
      $database->setQuery( $sql );
	  $database->query();

if ($fb_language == 1) {
        // Deal with language restriction
        $sql = "DELETE FROM #__fabannerlang WHERE bannerid = $banner->bannerid";
       	$database->setQuery( $sql );
	$database->query();

    $bannerlanguages = JArrayHelper::getValue( $_POST, 'languageid', array() );
	  foreach($bannerlanguages as $bannerlanguage){
            $sql = "INSERT into #__fabannerlang SET bannerid= $banner->bannerid, languageid = '$bannerlanguage'";
            $database->setQuery( $sql );
	    $database->query();
          }
}
        // Deal with restrictions
        $sql = "DELETE FROM #__fabannerin WHERE bannerid = $banner->bannerid";
       	$database->setQuery( $sql );
	$database->query();

	if ($restrictbyid){
          $bannersections = JArrayHelper::getValue( $_POST, 'sectionid', array() );
          $bannercategories = JArrayHelper::getValue( $_POST, 'categoryid', array() );
          $bannercontents = JArrayHelper::getValue( $_POST, 'contentid', array() );

	  foreach($bannercategories as $bannercategory){
            $sql = "INSERT into #__fabannerin SET bannerid= $banner->bannerid, categoryid = $bannercategory";
            $database->setQuery( $sql );
	    $database->query();
          }
	  foreach($bannercontents as $bannercontent){
            $sql = "INSERT into #__fabannerin SET bannerid= $banner->bannerid, contentid = $bannercontent";
            $database->setQuery( $sql );
	    $database->query();
          }
	}

	$mainframe->redirect('index.php?option=com_flexbanner&task=listBanners', $msg);
	echo JText::_('ADMIN_FLEXBANNER_SAVEBANNER');
}


function findSizeID($imagefile){
  $database = JFactory::getDBO();

  list($width, $height) = getimagesize( JURI::base() . '../images/banners/' . $imagefile);
  $filesize = filesize( JURI::base() . '../images/banners/' . $imagefile);

  $sql = "SELECT sizeid FROM #__fasize WHERE width=$width AND height=$height AND maxfilesize>$filesize";
  $database->setQuery($sql);
  if (!$database->query()) {
    echo $database->stderr();
    return;
  }
  $database->loadObject($size);
  
  return $size->sizeid;

}

function viewFlexBannerSizes($option) {
	$mainframe = JFactory::getApplication();
    $database = JFactory::getDBO();
	$limit = intval($mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mainframe->getCfg('list_limit'), 'int'));
	$limitstart = intval($mainframe->getUserStateFromRequest("viewban{$option}limitstart", 'limitstart', 0));

	// get the total number of records
	$query = "SELECT COUNT(*)" . "\n FROM #__fasize";
	$database->setQuery($query);
	$total = $database->loadResult();

	require_once ( JPATH_SITE . '/plugins/content/pagenavigation/pagenavigation.php');
	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);

	$query = "SELECT b.*, u.name AS editor" . "\n FROM #__fasize AS b
                  LEFT JOIN #__users AS u ON u.id = b.checked_out
                  LIMIT $pageNav->limitstart, $pageNav->limit";
	$database->setQuery($query);

	if (!$result = $database->query()) {
		echo $database->stderr();
		return;
	}
	$rows = $database->loadObjectList();

	HTML_FlexBanner :: showSizes($rows, $pageNav, $option);
}

function editFlexBannerSize($sizeid, $option) {
	global $my;
$database = JFactory::getDBO();	
	$lists = array ();

	$row = new flexAdSize($database);

	if ($sizeid) {
          $row->load($sizeid);
  	  $row->checkout($my->id);
	}

	HTML_FlexBanner :: sizeForm($row, $lists, $option);
}

function saveFlexBannerSize($sizeid, $option){
$mainframe = JFactory::getApplication();
$database = JFactory::getDBO();	
	
	$row = new flexAdSize($database);
	if (!$row->bind($_POST)) {
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit ();
	}
	if (!$row->check()) {
		$mainframe->redirect( "index.php?option=$option&task=editsize&cid[]=$row->sizeid", $row->getError());
	}

	if (!$row->store()) {
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit ();
	}
	$row->checkin();

	$mainframe->redirect( "index.php?option=$option&task=listSizes");

}

function viewFlexBannerLocations($option) {
	$mainframe = JFactory::getApplication();
$database = JFactory::getDBO();
	$limit = intval($mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mainframe->getCfg('list_limit'), 'int'));
	$limitstart = intval($mainframe->getUserStateFromRequest("viewban{$option}limitstart", 'limitstart', 0));

	// get the total number of records
	$query = "SELECT COUNT(*)" . "\n FROM #__falocation";
	$database->setQuery($query);
	$total = $database->loadResult();

	require_once (JPATH_ROOT . '/plugins/content/pagenavigation/pagenavigation.php');
	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);

	$query = "SELECT b.*, u.name AS editor" . "\n FROM #__falocation AS b
                  LEFT JOIN #__users AS u ON u.id = b.checked_out
                  LIMIT $pageNav->limitstart, $pageNav->limit";
	$database->setQuery($query);

	if (!$result = $database->query()) {
		echo $database->stderr();
		return;
	}
	$rows = $database->loadObjectList();

	HTML_FlexBanner :: showLocations($rows, $pageNav, $option);
}

function editFlexBannerLocation($locationid, $option) {
	global $my, $mainframe;

$database = JFactory::getDBO();
	$lists = array ();
	
	$row = new flexAdLocation($database);

	if ($locationid) {
          $row->load($locationid);
  	  $row->checkout($my->id);
	}

  	HTML_FlexBanner :: locationForm($row, $lists, $option);
}

function saveFlexBannerLocation($locationid, $option){
$mainframe = JFactory::getApplication();
$database = JFactory::getDBO();	
	$row = new flexAdLocation($database);
	if (!$row->bind($_POST)) {
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit ();
	}
	if (!$row->check()) {
		$mainframe->redirect( "index.php?option=$option&task=editlocation&cid[]=$row->locationid", $row->getError());
	}

	if (!$row->store()) {
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit ();
	}
	$row->checkin();
	
	$mainframe->redirect("index.php?option=$option&task=listLocations");

}

function viewFlexBannerClients($option) {
	$mainframe = JFactory::getApplication();
$database = JFactory::getDBO();
	$limit = intval($mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mainframe->getCfg('list_limit'), 'int'));
	$limitstart = intval($mainframe->getUserStateFromRequest("viewban{$option}limitstart", 'limitstart', 0));

	// get the total number of records
	$query = "SELECT COUNT(*)" . "\n FROM #__faclient";
	$database->setQuery($query);
	$total = $database->loadResult();

	require_once (JPATH_SITE . '/plugins/content/pagenavigation/pagenavigation.php');
	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);

	$query = "SELECT b.*, u.name AS editor" . "\n FROM #__faclient AS b
                  LEFT JOIN #__users AS u ON u.id = b.checked_out
                  LIMIT $pageNav->limitstart, $pageNav->limit";
	$database->setQuery($query);

	if (!$result = $database->query()) {
		echo $database->stderr();
		return;
	}
	$rows = $database->loadObjectList();

	HTML_FlexBanner :: showClients($rows, $pageNav, $option);
}

function editFlexBannerClient($clientid, $option) {
	global $my;
$database = JFactory::getDBO();
	$lists = array ();

	$row = new flexAdClient($database);

	if ($clientid) {
          $row->load($clientid);
  	  $row->checkout($my->id);
	}

	HTML_FlexBanner :: clientForm($row, $lists, $option);
}

function saveFlexBannerClient($clientid, $option){
	$mainframe = JFactory::getApplication();
$database = JFactory::getDBO();

	$row = new flexAdClient($database);
	if (!$row->bind($_POST)) {
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit ();
	}
	if (!$row->check()) {
		$mainframe->redirect("index.php?option=$option&task=editClient&cid[]=$row->clientid", $row->getError());
	}

	if (!$row->store()) {
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit ();
	}
	$row->checkin();

	$mainframe->redirect("index.php?option=$option&task=listClients");

}

function viewFlexBannerLinks($option) {
	$mainframe = JFactory::getApplication();
$database = JFactory::getDBO();

	$limit = intval($mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mainframe->getCfg('list_limit'), 'int'));
	$limitstart = intval($mainframe->getUserStateFromRequest("viewban{$option}limitstart", 'limitstart', 0));

	// get the total number of records
	$query = "SELECT COUNT(*)" . "\n FROM #__falink";
	$database->setQuery($query);
	$total = $database->loadResult();

	require_once (JPATH_SITE  . '/plugins/content/pagenavigation/pagenavigation.php');
	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);

	$query = "SELECT b.*, c.clientname, u.name AS editor" . "\n FROM #__falink AS b
                  LEFT JOIN #__users AS u ON u.id = b.checked_out
                  LEFT JOIN #__faclient AS c ON b.clientid=c.clientid
                  LIMIT $pageNav->limitstart, $pageNav->limit";
	$database->setQuery($query);

	if (!$result = $database->query()) {
		echo $database->stderr();
		return;
	}
	$rows = $database->loadObjectList();

	HTML_FlexBanner :: showLinks($rows, $pageNav, $option);
}

function editFlexBannerLink($linkid, $option) {
	global $my;
$database = JFactory::getDBO();
	$lists = array ();

	$row = new flexAdLink($database);

	if ($linkid) {
          $row->load($linkid);
  	  $row->checkout($my->id);
	}

	$sql = "SELECT clientid, clientname FROM #__faclient WHERE barred=0";
	$database->setQuery($sql);
	if (!$database->query()) {
		echo $database->stderr();
		return;
	}

	$clientlist = $database->loadObjectList();
	$lists['clientid'] = JHTML ::_('select.genericlist',$clientlist, 'clientid', 'class="inputbox" size="1"', 'clientid', 'clientname', $row->clientid);

	HTML_FlexBanner :: linkForm($row, $lists, $option);
}

function saveFlexBannerLink($linkid, $option){
$database = JFactory::getDBO();
$mainframe = JFactory::getApplication();
	$row = new flexAdLink($database);

	if (!$row->bind($_POST)) {
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit ();
	}

	if (!$row->check()) {
		$mainframe->redirect( "index.php?option=$option&task=editLink&cid[]=$row->linkid", $row->getError());
	}

		    if((preg_match("/http:\/\//", $row->linkurl)) || (preg_match("/https:\/\//", $row->linkurl))) {
		        $row->linkurl=$row->linkurl;
		    } else {
		        $row->linkurl="http://".$row->linkurl;
		    }


	if (!$row->store()) {
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit ();
	}

	$row->checkin();

	$mainframe->redirect( "index.php?option=$option&task=listLinks");
}

function deleteFlexBannerBanner($bannerid, $option){
 	$mainframe = JFactory::getApplication();
 $database = JFactory::getDBO(); 
  if (is_array($bannerid)){
    foreach ($bannerid as $banner){
      $sql = "DELETE FROM #__fabanner WHERE bannerid = $banner";
      $database->setQuery($sql);
      if (!$database->query()) {
        echo $database->stderr();
        return;
      }
    }
  }else{
    $sql = "DELETE FROM #__fabanner WHERE bannerid = $bannerid";
    $database->setQuery($sql);
    if (!$database->query()) {
      echo $database->stderr();
      return;
    }
  }
  $mainframe->redirect("index.php?option=$option&task=listBanners");
}

function deleteFlexBannerClient($clientid, $option){
$mainframe = JFactory::getApplication();
$database = JFactory::getDBO();  
  if (is_array($clientid)){
    foreach ($clientid as $client){
      $sql = "DELETE FROM #__faclient WHERE clientid = $client";
      $database->setQuery($sql);
      if (!$database->query()) {
        echo $database->stderr();
        return;
      }
    }
  }else{
    $sql = "DELETE FROM #__faclient WHERE clientid = $clientid";
    $database->setQuery($sql);
    if (!$database->query()) {
      echo $database->stderr();
      return;
    }
  }
  $mainframe->redirect("index.php?option=$option&task=listClients");
}

function deleteFlexBannerLink($linkid, $option){
$mainframe = JFactory::getApplication();
$database = JFactory::getDBO();  
  if (is_array($linkid)){
    foreach ($linkid as $link){
      $sql = "DELETE FROM #__falink WHERE linkid = $link";
      $database->setQuery($sql);
      if (!$database->query()) {
        echo $database->stderr();
        return;
      }
    }
  }else{
    $sql = "DELETE FROM #__falink WHERE linkid = $linkid";
    $database->setQuery($sql);
    if (!$database->query()) {
      echo $database->stderr();
      return;
    }
  }
  $mainframe->redirect( "index.php?option=$option&task=listLinks");
}

function deleteFlexBannerLocation($locationid, $option){
$mainframe = JFactory::getApplication();
$database = JFactory::getDBO();  
  if (is_array($locationid)){
    foreach ($locationid as $location){
      $sql = "DELETE FROM #__falocation WHERE locationid = $location";
      $database->setQuery($sql);
      if (!$database->query()) {
        echo $database->stderr();
        return;
      }
    }
  }else{
    $sql = "DELETE FROM #__falocation WHERE locationid = $locationid";
    $database->setQuery($sql);
    if (!$database->query()) {
      echo $database->stderr();
      return;
    }
  }
  $mainframe->redirect("index.php?option=$option&task=listLocations");
}

function deleteFlexBannerSize($sizeid, $option){
$mainframe = JFactory::getApplication();
$database = JFactory::getDBO();  
  if (is_array($sizeid)){
    foreach ($sizeid as $size){
      $sql = "DELETE FROM #__fasize WHERE sizeid = $size";
      $database->setQuery($sql);
      if (!$database->query()) {
        echo $database->stderr();
        return;
      }
    }
  }else{
    $sql = "DELETE FROM #__fasize WHERE sizeid = $sizeid";
    $database->setQuery($sql);
    if (!$database->query()) {
      echo $database->stderr();
      return;
    }
  }
  $mainframe->redirect("index.php?option=$option&task=listSizes");
}

function publishFlexBannerBanners($bannerid, $option){
$mainframe = JFactory::getApplication();
$database = JFactory::getDBO();  
  if (is_array($bannerid)){
    foreach ($bannerid as $banner){
      $sql = "UPDATE #__fabanner SET published=1 WHERE bannerid = $banner";
      $database->setQuery($sql);
      if (!$database->query()) {
        echo $database->stderr();
        return;
      }
    }
  }else{
    $sql = "UPDATE #__fabanner SET published=1 WHERE bannerid = $banner";
    $database->setQuery($sql);
    if (!$database->query()) {
      echo $database->stderr();
      return;
    }
  }
  $mainframe->redirect("index.php?option=$option&task=listBanners");

}

function unpublishFlexBannerBanners($bannerid, $option){
$mainframe = JFactory::getApplication();
$database = JFactory::getDBO();  
  if (is_array($bannerid)){
    foreach ($bannerid as $banner){
      $sql = "UPDATE #__fabanner SET published=0 WHERE bannerid = $banner";
      $database->setQuery($sql);
      if (!$database->query()) {
        echo $database->stderr();
        return;
      }
    }
  }else{
    $sql = "UPDATE #__fabanner SET published=0 WHERE bannerid = $banner";
    $database->setQuery($sql);
    if (!$database->query()) {
      echo $database->stderr();
      return;
    }
  }
  $mainframe->redirect("index.php?option=$option&task=listBanners");

}

?>