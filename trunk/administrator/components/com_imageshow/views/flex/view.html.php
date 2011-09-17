<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class ImageShowViewFlex extends JView
{
	function display($tpl = null)
	{
		$task 		= JRequest::getVar('task');
		$showlistID = JRequest::getVar('showlist_id');
		$objJSNFlex 	= JSNISFactory::getObj('classes.jsn_is_flex');
		$folder 	= JRequest::getVar('folder'); 
		$sourceType = JRequest::getVar('source_type');
		$album = JRequest::getVar('album');
		
		switch ($task)
		{
			case 'getToken':
				echo $objJSNFlex->$task();
				jexit();
			break;
			case 'init':
				echo $objJSNFlex->$task($showlistID);
				jexit();
			break;
			case 'removeAllImagesByShowlistID':
				echo $objJSNFlex->$task($showlistID);
				jexit();
			break;
			case 'loadImageInFolder':
				echo $objJSNFlex->$task($folder);
				jexit();
			break;
			case 'addImages':
				echo $objJSNFlex->$task();
				jexit();
			break;
			case 'loadAllProfileConfigBySourceType':
				echo $objJSNFlex->$task($sourceType);
				jexit();
			break;
			case 'createProfile':
				echo $objJSNFlex->$task();
				jexit();
			break;
			case 'saveProfile':
				echo $objJSNFlex->$task();
				jexit();
			break;
			case 'loadRemoteImage':
				echo $objJSNFlex->$task($showlistID, $album);
				jexit();
			break;
			case 'checkJoomgaPhoca':
				echo $objJSNFlex->$task();
				jexit();
			break;
			case 'getFlickrPicasaImageInfo':
				echo $objJSNFlex->$task();
				jexit();
			break;
			case 'synchronize':
				echo $objJSNFlex->$task();
				jexit();
			break;
			case 'loadLanguage':
				echo $objJSNFlex->$task();
				jexit();
			break;
			case 'saveSyncAlbum':
				echo $objJSNFlex->$task();
				jexit();
			break;
			default:
				parent::display();
			break;
		}
	}
}