<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.jsgallery.php 7788 2011-08-18 09:46:22Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class ImageShowViewShow extends JView
{
	function display($tpl = null)
	{
		$showlistID = JRequest::getString('showlist_id');
		$imageIndex = JRequest::getInt('image_index');
		$backPage   = JRequest::getVar('back_page');

		if ($showlistID)
		{
			$objJSNShow 	= JSNISFactory::getObj('classes.jsn_is_show');
			$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');
			$objJSNImages 	= JSNISFactory::getObj('classes.jsn_is_images');

			$showlistInfo 	= $objJSNShowlist->getShowListByID($showlistID);
			$imagesData 	= $objJSNImages->getImagesByShowlistID($showlistID);

			// showlist which sync images feature is enabled
			$syncData = $objJSNImages->getSyncImagesByShowlistID($showlistID);

			if (!empty($syncData))
			{
				$imagesData = $syncData;
			}

			echo $objJSNShow->renderJSGallery($showlistInfo, $imagesData, $imageIndex, $backPage);
		}
		
		jexit();
	}
}


?>
