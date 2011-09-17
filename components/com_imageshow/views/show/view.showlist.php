<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.showlist.php 7764 2011-08-16 03:34:25Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class ImageShowViewShow extends JView
{
	function display($tpl = null)
	{
		$showlistID 		= JRequest::getInt('showlist_id', 0);
		$objUtils			= JSNISFactory::getObj('classes.jsn_is_utils');
		$URL				= $objUtils->overrideURL();
		$objJSNShowlist		= JSNISFactory::getObj('classes.jsn_is_showlist');
		$showlistInfo 		= $objJSNShowlist->getShowListByID($showlistID, true);

		if (count($showlistInfo) <=0)
		{
			header("HTTP/1.0 404 Not Found");
			exit();
		}

		$objJSNShowlist->insertHitsShowlist($showlistID);

		$objJSNJSON = JSNISFactory::getObj('classes.jsn_is_json');
		$dataObj 	= $objJSNShowlist->getShowlist2JSON($URL, $showlistID);

		echo $objJSNJSON->encode($dataObj);

		jexit();
	}
}
