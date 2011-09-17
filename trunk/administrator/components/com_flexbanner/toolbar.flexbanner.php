<?php
// no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once( JApplicationHelper::getPath('toolbar_html'));

switch ($task) {
	case 'listBanners':
		TOOLBAR_flexBannerBanners::_DEFAULT();
		break;
	case 'cancelSize':
	case 'listSizes':
		TOOLBAR_flexBannerBanners::_DEFAULTSIZES();
		break;
	case 'cancelLocations':
	case 'listLocations':
		TOOLBAR_flexBannerBanners::_DEFAULTLOCATIONS();
		break;
	case 'cancelClient':
	case 'listClients':
		TOOLBAR_flexBannerBanners::_DEFAULTCLIENTS();
		break;
	case 'cancelLink':
	case 'listLinks':
		TOOLBAR_flexBannerBanners::_DEFAULTLINKS();
		break;
	case 'editBanner':
	case 'newBanner':
	case 'editBannerC':
		TOOLBAR_flexBannerBanners::_EDIT();
		break;
	case 'editSize':
        case 'newSize':
             TOOLBAR_flexBannerBanners::_EDITSIZE();
             break;
	case 'editLocation':
        case 'newLocation':
             TOOLBAR_flexBannerBanners::_EDITLOCATION();
             break;
	case 'editClient':
        case 'newClient':
             TOOLBAR_flexBannerBanners::_EDITCLIENT();
             break;
	case 'editLink':
	case 'newLink':
		TOOLBAR_flexBannerBanners::_EDITLINK();
		break;
	default:
		TOOLBAR_flexBannerBanners::_DEFAULT();
		break;
}
?>