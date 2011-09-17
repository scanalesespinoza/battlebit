<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: helper.php 7738 2011-08-12 01:22:50Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

class modImageShowHelper
{
	function render(&$params)
	{
		global $mainframe;
		$mainframe  = JFactory::getApplication();
		$dispatcher				=& JDispatcher::getInstance();
		$objUtils				= JSNISFactory::getObj('classes.jsn_is_utils');
		$URLOriginal 			= $objUtils->overrideURL();
		$objectReadxmlDetail 	= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$infoXmlDetail 			= $objectReadxmlDetail->parserXMLDetails();
		$paramsCom				=& $mainframe->getParams('com_imageshow');
		$parameterConfig 		= $objUtils->getParametersConfig();
		$generalSWFLibrary 		= (is_null($parameterConfig)?'0':$parameterConfig->general_swf_library);
		$language				= '';
		$shortEdition				= $objUtils->getShortEdition();
		$random			        = uniqid('').rand(1, 99); 
		$objJSNLang				= JSNISFactory::getObj('classes.jsn_is_language');
		$filterLangSys			= $objJSNLang->getFilterLangSystem();
		
		if ($objUtils->checkSupportLang())
		{
			$objLanguage = JFactory::getLanguage();
			$language    = $objLanguage->getTag();
		}

		$display			= false;
		$user 				=& JFactory::getUser();
		$authAvailable 		= $user->getAuthorisedViewLevels();
		$showcaseID 		= $params->get('showcase_id');
		$showlistID 		= $params->get('showlist_id');
		$objJSNShow			= JSNISFactory::getObj('classes.jsn_is_show');
		$objJSNShowcase		= JSNISFactory::getObj('classes.jsn_is_showcase');
		$objJSNShowlist		= JSNISFactory::getObj('classes.jsn_is_showlist');
		$randomString 		= $objUtils->randSTR(5);
		$articleAlternate 	= $objJSNShow->getArticleAlternate($showlistID);
		$moduleAlternate 	= $objJSNShow->getModuleAlternate($showlistID);
		$seoModule			= $objJSNShow->getModuleSEO($showlistID);
		$seoArticle			= $objJSNShow->getArticleSEO($showlistID);
		$articleAuth 		= $objJSNShow->getArticleAuth($showlistID);
		$showlistInfo 		= $objJSNShowlist->getShowListByID($showlistID);
		$showcaseInfo 		= $objJSNShowcase->getShowCaseByID($showcaseID);
		$html 				= '';

		if (is_null($showlistInfo))
		{
			$html .= $objUtils->displayShowlistMissingMessage();
			echo $html;
			return;
		}

		if (is_null($showcaseInfo))
		{
			$html .= $objUtils->displayShowcaseMissingMessage();
			echo $html;
			return;
		}

		$URL = $URLOriginal.'plugins/jsnimageshow/'.@$showcaseInfo->theme_name.'/'.@$showcaseInfo->theme_name.'/assets/swf/';

		if ($params->get('width') !='')
		{
			$width  = $params->get('width');
		}
		else
		{
			$width = @$showcaseInfo->general_overall_width;
		}

		if ($params->get('height') !='')
		{
			$height = $params->get('height');
		}
		else
		{
			$height = @$showcaseInfo->general_overall_height;
		}

		if (@$showcaseInfo->background_color =='')
		{
			$bgcolor = '#ffffff';
		}
		else
		{
			$bgcolor =  @$showcaseInfo->background_color;
		}

		$objJSNImages 	= JSNISFactory::getObj('classes.jsn_is_images');
		$imagesData 	= $objJSNImages->getImagesByShowlistID($showlistID);

		// showlist which sync images feature is enabled
		$syncData = $objJSNImages->getSyncImagesByShowlistID($showlistID);

		if (!empty($syncData))
		{
			$imagesData = $syncData;
		}
		// end sync images

		if (!in_array($showlistInfo['access'],  $authAvailable))
		{
			$display = false;
		}
		else
		{
			$display = true;
		}

		if ($generalSWFLibrary == '')
		{
			$generalSWFLibrary = 0;
		}

		if ($width =='')
		{
			$width = '100%';
		}

		if ($height == '')
		{
			$height = '100';
		}

		$posPercentageWidth = strpos($width, '%');

		if ($posPercentageWidth)
		{
			$width = substr($width, 0, $posPercentageWidth + 1);
		}
		else
		{
			$width = (int) $width;
		}

		$height = (int) $height;

		$objJSNTheme = JSNISFactory::getObj('classes.jsn_is_showcasetheme');
		$themeInfo 	 = $objJSNTheme->getThemeInfo($showcaseInfo->theme_name);

		$html .='<!-- '.JText::_('JSN').' '.@$infoXmlDetail['realName'].' '.strtoupper(@$infoXmlDetail['edition']).' '.@$infoXmlDetail['version'].' - ' .@$themeInfo->name. ' '.@$themeInfo->version .' -->';

		$html.='<div class="jsnis-container">';
		$html.='<div class="jsnis-gallery">';
		// fix error: click back browser, no event onclick of flash
		$html.='<script type="text/javascript"> window.onbeforeunload = function() {} </script>';
		if ($generalSWFLibrary == 1)
		{
			$html .= "<script type='text/javascript'>						
						swfobject.embedSWF('".$URL."Gallery.swf', 'jsn-imageshow-".$randomString."', '".$width."', '".$height."', '9.0.45', '".$URL."assets/js/expressInstall.swf', {baseurl:'".$URL."', showcase:'".$URLOriginal.$filterLangSys."option=com_imageshow%26view=show%26format=showcase%26showcase_id=".$showcaseID."',showlist:'".$URLOriginal.$filterLangSys."option=com_imageshow%26view=show%26format=showlist%26showlist_id=".$showlistID."', language:'".$language."', edition:'".$shortEdition."'}, {wmode: 'opaque',bgcolor: '".$bgcolor."', menu: 'false', allowFullScreen:'true'});
					</script>";	
			$html .='<div id="jsn-imageshow-'.$randomString.'" ></div>';
		}
		else
		{
			$html .='<object height="'.$height.'" width="'.$width.'" class="jsnis-flash-object" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" id="jsn-imageshow-'.$randomString.'">
				<param name="bgcolor" value="'.$bgcolor.'"/>
				<param name="menu" value="false"/>
				<param name="wmode" value="opaque"/>
				<param name="allowFullScreen" value="true"/>
				<param name="allowScriptAccess" value="sameDomain" />
				<param name="movie" value="'.$URL.'Gallery.swf"/>
				<param name="flashvars" value="baseurl='.$URL.'&amp;showcase='.$URLOriginal.$filterLangSys.'option=com_imageshow%26view=show%26showcase_id='.$showcaseID.'%26format=showcase&amp;showlist='.$URLOriginal.$filterLangSys.'option=com_imageshow%26view=show%26showlist_id='.$showlistID.'%26format=showlist&amp;language='.$language.'&amp;edition='.$shortEdition.'"/>';
				$html .='<embed src="'.$URL.'Gallery.swf" menu="false" bgcolor="'.$bgcolor.'" width="'.$width.'" height="'.$height.'" name="jsn-imageshow-'.$randomString.'" align="middle" allowScriptAccess="sameDomain" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" wmode="opaque" flashvars="baseurl='.$URL.'&amp;showcase='.$URLOriginal.$filterLangSys.'option=com_imageshow%26view=show%26showcase_id='.$showcaseID.'%26format=showcase&amp;showlist='.$URLOriginal.$filterLangSys.'option=com_imageshow%26view=show%26showlist_id='.$showlistID.'%26format=showlist&amp;language='.$language.'&amp;edition='.$shortEdition.'" /></object>';
		}
		$html .='</div>';
		// ALTERNATIVE CONTENT BEGIN
		$width = (preg_match('/%/', $width)) ? $width : $width.'px';
		$html .='<div class="jsnis-altcontent" style="width:'.$width.'; height:'.$height.'px;">';

		if ($showlistInfo['alternative_status'] == 0)
		{
			$html .= '<div>
					  	<p>'.JText::_('JSN_MODULE_YOU_NEED_FLASH_PLAYER').'!</p>
						<p>
							<a href="http://www.adobe.com/go/getflashplayer">'.JText::_('JSN_MODULE_GET_FLASH_PLAYER').'</a>
						</p>
					 </div>';
		}

		if ($showlistInfo['alternative_status'] == 1)
		{
			if ($moduleAlternate['published'] == 1 && $moduleAlternate['module'] != 'mod_imageshow')
			{
				$module = $objJSNShow->getModuleByID($moduleAlternate['id']);
				$html .= JModuleHelper::renderModule($module);
			}
		}

		if ($showlistInfo['alternative_status'] == 2)
		{
			$html .='<div>'.$articleAlternate['introtext'].$articleAlternate['fulltext'].'</div>';
		}

		if ($showlistInfo['alternative_status'] == 3)
		{
			$id 		  = 'jsnis-alternative-mimage-'.$random;
			$dimension    = $objJSNShow->renderAlternativeImage($showlistInfo['alter_image_path']);

			if (count($dimension))
			{
				$html .= '<script type="text/javascript">
							window.addEvent("domready", function(){
								JSNISImageShow.scaleResize('.$dimension['width'].','.$dimension['height'].', "'.$id.'");
							});
							window.addEvent("load", function(){
								JSNISImageShow.scaleResize('.$dimension['width'].','.$dimension['height'].', "'.$id.'");
							});
						</script>'."\n";
				$html .= '<img id="'.$id.'" style="display:none; position: absolute;" src="'.$URLOriginal.$showlistInfo['alter_image_path'].'" />';
			}
		}

		if ($showlistInfo['alternative_status'] == 4)
		{
			$html .= $objJSNShow->renderAlternativeGallery($imagesData, $showlistInfo, $URLOriginal, $random, $width, $height);
		}
		$html .="</div>";
		//ALTERNATIVE CONTENT END

		// SEO CONTENT BEGIN
		$html .= "<div class=\"jsnis-seocontent\">";
		$html .= '<p><a href="http://www.joomlashine.com" title="Joomla gallery">Joomla gallery</a> by joomlashine.com</p>';
		if ($showlistInfo['seo_status'] == 0)
		{
			if (count( $imagesData ))
			{
				$html .= $objJSNShow->renderAlternativeListImages($imagesData, $showlistInfo);
			}
		}

		if ($showlistInfo['seo_status'] == 1)
		{
			$html .= "<div>".$seoArticle['introtext'].$seoArticle['fulltext']."</div>";
		}

		if ($showlistInfo['seo_status'] == 2)
		{
			if ($seoModule['published'] == 1 && $seoModule['module'] != 'mod_imageshow')
			{
				$module = $objJSNShow->getModuleByID($seoModule['id']);
				$html .= JModuleHelper::renderModule($module);
			}
		}

		$html .= "</div>";
		// SEO CONTENT END

		$html .="</div>";

		if ($display == true)
		{
			echo $html;
		}
		else
		{
			if ($showlistInfo['authorization_status'] == 1)
			{
				echo '<div>'.$articleAuth['introtext'].$articleAuth['fulltext'].'</div>';
			}
			else
			{
				echo '&nbsp;';
			}
		}
	}

	function approveModule($moduleName, $publish = 1)
	{
		$db 	=& JFactory::getDBO();
		$query 	= 'UPDATE #__modules SET published ='.$publish.' WHERE module = '.$db->Quote($moduleName, false);
		$db->setQuery($query);
		if (!$db->query())
		{
			return false;
		}
		return true;
	}
}