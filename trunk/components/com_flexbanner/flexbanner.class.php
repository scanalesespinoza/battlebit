<?php
/**
* @copyright Copyright (C) 2011 Inch Communications Ltd. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

// no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
* @package Joomla
* @subpackage Banners
*/
class flexAdBanner extends JTable {
	var $bannerid         = null;
	var $clientid         = null;
	var $linkid           = null;
	var $sizeid           = null;
	var $imageurl         = null;
	var $imagealt         = null;
	var $customcode       = null;
	var $restrictbyid     = 0;
	var $frontpage    	  = 0;
	var $clicks           = 0;
	var $impressions      = 0;
	var $startdate        = null;
	var $enddate          = null;
	var $maximpressions   = null;
	var $maxclicks        = null;
	var $dailyimpressions = 0;
	var $lastreset        = '0000-00-00';
	var $published        = 0;
	var $finished         = 0;
    var $checked_out      = 0;
	var $checked_out_time = 0;
	var $editor           = '';
	var $juserid          = null;

	function flexAdBanner( &$_db ) {
		parent::__construct( '#__fabanner', 'bannerid', $_db );
	}

	function check() {

		return true;
	}
}

class flexAdClient extends JTable {
	var $clientid         = null;
	var $clientname       = null;
	var $contactname      = null;
	var $contactemail     = null;
	var $barred           = 0;
    var $checked_out      = 0;
	var $checked_out_time = 0;
	var $editor           = '';

	function flexAdClient( &$_db ) {
		parent::__construct( '#__faclient', 'clientid', $_db );
	}

	function check() {

		return true;
	}
}

class flexAdLink extends JTable {
	var $linkid      = null;
	var $clientid    = null;
	var $linkurl     = '';
	var $checked_out      = 0;
	var $checked_out_time = 0;
	var $editor           = '';

	function flexAdLink( &$_db ) {
		parent::__construct( '#__falink', 'linkid', $_db );
	}

	function check() {

		return true;
	}
}

class flexAdLocation extends JTable {
	var $locationid      = null;
	var $locationname     = '';
	var $checked_out      = 0;
	var $checked_out_time = 0;
	var $editor           = '';

	function flexAdLocation( &$_db ) {
		parent::__construct( '#__falocation', 'locationid', $_db );
	}

	function check() {

		return true;
	}
}

class flexAdImage extends JTable {
        var $imageid  = null;
        var $imageurl = '';
        var $width    = 0;
	var $height   = 0;
	var $filesize = 0;

	function flexAdImage( &$_db ) {
		parent::__construct( '#__faimage', 'imageid', $_db );
	}

	function check() {

		return true;
	}
}

class flexAdSize extends JTable {
        var $sizeid           = null;
        var $sizename         = '';
        var $width            = 0;
        var $height           = 0;
        var $maxfilesize      = 0;
        var $checked_out      = 0;
	var $checked_out_time = 0;
	var $editor           = '';

	function flexAdSize( &$_db ) {
		parent::__construct( '#__fasize', 'sizeid', $_db );
	}

	function check() {

		return true;
	}
}

class flexAdContent extends JTable {
        var $id               = null;
        var $sectionid        = 0;
        var $catid            = 0;
        /*var $title            = '';
        var $title_alias      = '';
        var $introtext        = '';
        var $fulltext         = '';
        var $state            = 0;
        var $mask             = 0;
        var $created          = '0000-00-00';
        var $created_by       = 0;
        var $created_by_alias = 0;
        var $modified         = 0;
        var $modified_by      = 0;
        var $checked_out      = 0;
	var $checked_out_time = 0;
        var $publish_up       = 0;
        var $publish_down     = 0;
        var $images           = 0;
        var $urls             = 0;
        var $attribs          = 0;
        var $version          = 0;
        var $parentid         = 0;
        var $ordering         = 0;
        var $metakey          = 0;
        var $metadesc         = 0;
        var $access           = 0;
	var $hits             = '';  */

	function flexAdContent( &$_db ) {
		parent::__construct( '#__content', 'id', $_db );
	}

	function check() {

		return true;
	}
}

class flexAdCategories extends JTable {
        var $id               = null;
        var $parent_id        = '';
        var $title            = '';
        var $name             = '';
        var $image            = '';
        var $section          = 0;
        var $image_position   = 0;
        var $description      = 0;
        var $published        = 0;
        var $checked_out      = 0;
	var $checked_out_time = 0;
        var $editor           = '';
        var $ordering         = 0;
        var $access           = 0;
        var $count            = 0;
        var $params           = 0;

	function flexAdCategories( &$_db ) {
		parent::__construct( '#__categories', 'id', $_db );
	}

	function check() {

		return true;
	}
}
?>