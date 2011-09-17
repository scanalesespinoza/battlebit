<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 7764 2011-08-16 03:34:25Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class ImageShowViewShow extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;
		JHTML::_('behavior.mootools');
		$objectReadxmlDetail 	= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$infoXmlDetail 			= $objectReadxmlDetail->parserXMLDetails();
		$pageclassSFX 			= '';
		$titleWillShow 			= '';
		$app 					= JFactory::getApplication('site');
		$menu_params 			= $app->getParams('com_imageshow');
		$menus					= $app->getMenu();
		$menu 					= $menus->getActive();
		$jsnisID 				= JRequest::getInt('jsnisid', 0);

		$showCaseID = JRequest::getInt('showcase_id', 0);

		if ($jsnisID != 0)
		{
			$pageclassSFX 	= $menu_params->get('pageclass_sfx');
			$showPageTitle 	= $menu_params->get('show_page_heading');
			$pageTitle 		= $menu_params->get('page_title');

			if (!empty($showPageTitle))
			{
				if (!empty($pageTitle))
				{
					$titleWillShow = $pageTitle;
				}
				else if (!empty($item->name))
				{
					$titleWillShow = $item->name;
				}
			}

		}

		$showListID = JRequest::getInt('showlist_id', 0);

		$objJSNShow				= JSNISFactory::getObj('classes.jsn_is_show');
		$objUtils				= JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNShowlist         = JSNISFactory::getObj('classes.jsn_is_showlist');
		$objJSNShowcase         = JSNISFactory::getObj('classes.jsn_is_showcase');
		$objJSNImages			= JSNISFactory::getObj('classes.jsn_is_images');

		$paramsCom				= $mainframe->getParams('com_imageshow');
		$parameterConfig 		= $objUtils->getParametersConfig();
		$generalSWFLibrary 		= (is_null($parameterConfig)?'0':$parameterConfig->general_swf_library);

		$randomNumber 			= $objUtils->randSTR(5);
		$showlistInfo 			= $objJSNShowlist->getShowListByID($showListID);
		$articleAlternate 		= $objJSNShow->getArticleAlternate($showListID);
		$articleAuth 			= $objJSNShow->getArticleAuth($showListID);
		$moduleAlternate		= $objJSNShow->getModuleAlternate($showListID);
		$seoModule				= $objJSNShow->getModuleSEO($showListID);
		$seoArticle				= $objJSNShow->getArticleSEO($showListID);
		$row 					= $objJSNShowcase->getShowCaseByID($showCaseID);
		$imagesData 			= $objJSNImages->getImagesByShowlistID($showListID);

		// showlist which sync images feature is enabled
		$syncData = $objJSNImages->getSyncImagesByShowlistID($showListID);

		if (!empty($syncData))
		{
			$imagesData = $syncData;
		}
		// end sync images

		$this->assignRef('titleWillShow', $titleWillShow);
		$this->assignRef('showcaseInfo', $row);
		$this->assignRef('randomNumber', $randomNumber);
		$this->assignRef('imagesData', $imagesData);
		$this->assignRef('showlistInfo', $showlistInfo);
		$this->assignRef('articleAlternate', $articleAlternate);
		$this->assignRef('moduleAlternate', $moduleAlternate);
		$this->assignRef('articleAuth', $articleAuth);
		$this->assignRef('seoModule', $seoModule);
		$this->assignRef('seoArticle', $seoArticle);
		$this->assignRef('generalSWFLibrary', $generalSWFLibrary);
		$this->assignRef('pageclassSFX', $pageclassSFX);
		$this->assignRef('infoXmlDetail', $infoXmlDetail);
		$this->assignRef('objUtils', $objUtils);
		$this->assignRef('Itemid', $menu->id);
		parent::display($tpl);
	}
}