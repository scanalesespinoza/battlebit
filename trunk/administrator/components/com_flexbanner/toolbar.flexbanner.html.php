<?php
/**
* @copyright Copyright (C) 20011 Inch Communications Ltd. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class TOOLBAR_flexBannerBanners {
	/**
	* Draws the menu for editing banners
	*/
	public static function _EDIT() {
		global $id;

		JToolBarHelper::title('FlexBanner');
		JToolBarHelper::save('saveBanner');
		JToolBarHelper::spacer();
		if ( $id ) {
			// for existing content items the button is renamed `close`
			JToolBarHelper::cancel( 'cancelBanner', 'Close');
		} else {
			JToolBarHelper::cancel('cancelBanner');
		}
		JToolBarHelper::spacer();
		JToolBarHelper::help( 'screen.flexbanner', true );
	}
	public static function _DEFAULT() {
		JToolBarHelper::title('FlexBanner');
		JToolBarHelper::deleteList('', 'deleteBanner');
		JToolBarHelper::spacer();
		JToolBarHelper::publishList('publishBanners');
		JToolBarHelper::spacer();
		JToolBarHelper::unpublishList('unpublishBanners');
		JToolBarHelper::spacer();
		JToolBarHelper::editListX('editBannerC');
		JToolBarHelper::spacer();
		JToolBarHelper::addNewX('newBanner');
		JToolBarHelper::spacer();
		JToolBarHelper::help( 'screen.flexbanner', true );
		JToolBarHelper::spacer();
	}
	public static function _DEFAULTSIZES() {
		JToolBarHelper::title('FlexBanner');
		JToolBarHelper::deleteList('', 'deleteSize');
		JToolBarHelper::spacer();
		JToolBarHelper::editListX('editSize');
		JToolBarHelper::spacer();
		JToolBarHelper::addNewX('newSize');
		JToolBarHelper::spacer();
		JToolBarHelper::help( 'screen.flexbanner', true );
		JToolBarHelper::spacer();
	}
	public static function _DEFAULTLOCATIONS() {
		JToolBarHelper::title('FlexBanner');
		JToolBarHelper::deleteList('', 'deleteLocation');
		JToolBarHelper::spacer();
		JToolBarHelper::editListX('editLocation');
		JToolBarHelper::spacer();
		JToolBarHelper::addNewX('newLocation');
		JToolBarHelper::spacer();
		JToolBarHelper::help( 'screen.flexbanner', true );
		JToolBarHelper::spacer();
	}
	public static function _DEFAULTCLIENTS() {
		JToolBarHelper::title('FlexBanner');
		JToolBarHelper::deleteList('', 'deleteClient');
		JToolBarHelper::spacer();
		JToolBarHelper::editListX('editClient');
		JToolBarHelper::spacer();
		JToolBarHelper::addNewX('newClient');
		JToolBarHelper::spacer();
		JToolBarHelper::help( 'screen.flexbanner', true );
		JToolBarHelper::spacer();
	}
	public static function _DEFAULTLINKS() {
		JToolBarHelper::title('FlexBanner');
		JToolBarHelper::deleteList('', 'deleteLink');
		JToolBarHelper::spacer();
		JToolBarHelper::editListX('editLink');
		JToolBarHelper::spacer();
		JToolBarHelper::addNewX('newLink');
		JToolBarHelper::spacer();
		JToolBarHelper::help( 'screen.flexbanner', true );
		JToolBarHelper::spacer();
	}

	public static function _EDITSIZE() {
		global $id;

		JToolBarHelper::title('FlexBanner');
		JToolBarHelper::save('saveSize');
		JToolBarHelper::spacer();
		if ( $id ) {
			// for existing content items the button is renamed `close`
			JToolBarHelper::cancel( 'cancelSize', 'Close');
		} else {
			JToolBarHelper::cancel('cancelSize');
		}
		JToolBarHelper::spacer();
		JToolBarHelper::help( 'screen.flexbanner', true );
	}

	public static function _EDITLOCATION() {
		global $id;

		JToolBarHelper::title('FlexBanner');
		JToolBarHelper::save('saveLocation');
		JToolBarHelper::spacer();
		if ( $id ) {
			// for existing content items the button is renamed `close`
			JToolBarHelper::cancel( 'cancelLocation', 'Close');
		} else {
			JToolBarHelper::cancel('cancelLocation');
		}
		JToolBarHelper::spacer();
		JToolBarHelper::help( 'screen.flexbanner', true );
	}

	public static function _EDITCLIENT() {
		global $id;

		JToolBarHelper::title('FlexBanner');
		JToolBarHelper::save('saveClient');
		JToolBarHelper::spacer();
		if ( $id ) {
			// for existing content items the button is renamed `close`
			JToolBarHelper::cancel( 'cancelClient', 'Close');
		} else {
			JToolBarHelper::cancel('cancelClient');
		}
		JToolBarHelper::spacer();
		JToolBarHelper::help( 'screen.flexbanner', true );
	}
	public static function _EDITLINK() {
		global $id;

		JToolBarHelper::title('FlexBanner');
		JToolBarHelper::save('saveLink');
		JToolBarHelper::spacer();
		if ( $id ) {
			// for existing content items the button is renamed `close`
			JToolBarHelper::cancel( 'cancelLink', 'Close');
		} else {
			JToolBarHelper::cancel('cancelLink');
		}
		JToolBarHelper::spacer();
		JToolBarHelper::help( 'screen.flexbanner', true );
	}
}

?>