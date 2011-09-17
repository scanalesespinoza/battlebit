<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_show.php 7785 2011-08-18 08:09:38Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

class JSNISShow
{
	var $_db = null;

	function JSNISShow()
	{
		if ($this->_db == null)
		{
			$this->_db = JFactory::getDBO();
		}
	}

	public static function getInstance()
	{
		static $instanceShow;
		if ($instanceShow == null)
		{
			$instanceShow = new JSNISShow();
		}
		return $instanceShow;
	}

	function getArticleAlternate($showlistID)
	{
		$query 	= 'SELECT c.introtext, c.fulltext
				   FROM #__imageshow_showlist sl
				   INNER JOIN #__content c ON sl.alter_id = c.id
				   WHERE sl.showlist_id='.(int)$showlistID;
		$this->_db->setQuery($query);

		return $this->_db->loadAssoc();
	}

	function getModuleAlternate($showlistID)
	{
		$query = 'SELECT m.*
				  FROM #__imageshow_showlist sl
				  INNER JOIN #__modules m ON sl.alter_module_id = m.id
				  WHERE sl.showlist_id = '.(int)$showlistID;

		$this->_db->setQuery($query);
		return $this->_db->loadAssoc();
	}

	function getModuleSEO($showlistID)
	{
		$query = 'SELECT m.*
				  FROM #__imageshow_showlist sl
				  INNER JOIN #__modules m ON sl.seo_module_id = m.id
				  WHERE sl.showlist_id = '.(int)$showlistID;

		$this->_db->setQuery($query);
		return $this->_db->loadAssoc();
	}

	function getArticleSEO($showlistID)
	{
		$query = 'SELECT c.introtext, c.fulltext
		          FROM #__imageshow_showlist sl
		          INNER JOIN #__content c ON sl.seo_article_id = c.id
		          WHERE sl.showlist_id='.(int)$showlistID;
		$this->_db->setQuery($query);
		return $this->_db->loadAssoc();
	}

	function getArticleAuth($showlistID)
	{
		$query 	= 'SELECT c.introtext, c.fulltext
				   FROM #__imageshow_showlist sl
				   INNER JOIN #__content c ON sl.alter_autid = c.id
				   WHERE sl.showlist_id='.(int)$showlistID;
		$this->_db->setQuery($query);

		return $this->_db->loadAssoc();
	}

	function getModuleByID($ID)
	{
		$query = 'SELECT id, title, module, position, content, showtitle, params FROM #__modules WHERE id = '.(int)$ID;
		$this->_db->setQuery($query);
		$row 			= $this->_db->loadObject();
		$file 			= $row->module;
		$custom         = substr($file, 0, 4) == 'mod_' ?  0 : 1;
	    $row->user      = $custom;
	    $row->name      = $custom ? $row->title : substr($file, 4);
	    return $row;
	}

	function renderAlternativeImage($path)
	{
		jimport('joomla.filesystem.file');

		$rootPath 	= JPATH_ROOT;
		$imagePath 	= $rootPath.DS.str_replace('/', DS, $path);
		$dimension	= array();

		if (JFile::exists($imagePath))
		{
			list($width, $height) = @getimagesize($imagePath);
			$dimension ['width']  = $width;
			$dimension ['height'] = $height;
		}
		return $dimension;
	}

	function renderAlternativeGallery($images = array(), $showlistInfo = array(), $URL, $random, $width = '100%', $height = 650)
	{
		$html 				= '';
		$countImage 		= count($images);
		$randomImageIndex 	= rand(0, $countImage - 1);
		$objJSNImages 		= JSNISFactory::getObj('classes.jsn_is_images');
		$uri 				= JURI::getInstance();
		$backURL 			= base64_encode($uri->toString());

		if ($countImage > 0)
		{
			$imgSrc = $objJSNImages->getImageSrc($images[$randomImageIndex], $showlistInfo, $URL);

			if ($imgSrc != '')
			{
				$html .= '<script type="text/javascript">

							window.addEvent("domready", function(){
								JSNISImageShow.resizeIMG("jsnis-js-gallery-index-'.$random.'", "jsnis-slide-gallery-'.$random.'");
							});
							window.addEvent("load", function(){
								JSNISImageShow.resizeIMG("jsnis-js-gallery-index-'.$random.'", "jsnis-slide-gallery-'.$random.'");
							});
						</script>'."\n";

				$html .= '<div id="jsnis-slide-gallery-'.$random.'" class="jsnis-slide-gallery" style="position: relative; width:'.$width.'; height:'.$height.'px">';
				$html .= '<a href="?option=com_imageshow&amp;view=show&amp;format=jsgallery&amp;showlist_id='.$showlistInfo['showlist_id'].'&amp;image_index='.$randomImageIndex.'&amp;back_page='.$backURL.'">
								<img id="jsnis-js-gallery-index-'.$random.'" style="position:absolute;" src="'.$imgSrc.'" alt=""/>
						  </a></div>';
			}
		}
		else
		{
			$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
			$html .= $objJSNUtils->displayShowlistNoImages();
		}

		return $html;
	}

	function renderJSGallery($showlist, $images, $imageIndex = 0, $backURL = '')
	{
		$objJSNShow 		 = JSNISFactory::getObj('classes.jsn_is_show');
		$objISNUtils 		 = JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNImages		 = JSNISFactory::getObj('classes.jsn_is_images');
		if ($showlist['showlist_source'] == 2) $objJSNFlickr = JSNISFactory::getObj('classes.jsn_is_flickr');
		$randString			 = $objISNUtils->randSTR(6);
		$countImage			 = count($images);
		$URL 				 = $objISNUtils->overrideURL();
		$string 			 = '';
		$document 			 = JFactory::getDocument();

		$string .= '<!DOCTYPE html>
			<html>
			<head>
			<title>JS Gallery - JSN ImageShow</title>
			<link rel="stylesheet" href="'.$URL.'components/com_imageshow/assets/js/lib/jquery.mobile-1.0a4.1/jquery.mobile-1.0a4.1.min.css" />
			<link href="'.$URL.'components/com_imageshow/assets/css/photoswipe.css" type="text/css" rel="stylesheet" />
			<script type="text/javascript" src="'.$URL.'components/com_imageshow/assets/js/lib/jquery-1.6.1.min.js"></script>
			<script type="text/javascript" src="'.$URL.'components/com_imageshow/assets/js/lib/jquery.mobile-1.0a4.1/jquery.mobile-1.0a4.1.min.js"></script>
			<script type="text/javascript" src="'.$URL.'components/com_imageshow/assets/js/lib/simple-inheritance.min.js"></script>
			<script type="text/javascript" src="'.$URL.'components/com_imageshow/assets/js/lib/jquery.animate-enhanced.min.js"></script>
			<script type="text/javascript" src="'.$URL.'components/com_imageshow/assets/js/code-photoswipe-jQuery-1.0.10.min.js"></script>'."\n";

		if($countImage)
		{
			$backPage = '';
			if ($backURL != '')
			{
				$backPage = 'document.location = "'.$this->_db->getEscaped(base64_decode($backURL)).'"';
			}

			$string .= '<script type="text/javascript">

							$(document).ready(function()
							{
								$("div.gallery-page").live("pageshow", function(e)
								{
									Code.PhotoSwipe.Current.addEventListener(Code.PhotoSwipe.EventTypes.onBeforeHide, function(e){
										'.$backPage.';
									});

									$("div.gallery a", e.target).photoSwipe({ backButtonHideEnabled: false });

									$("#jsnis-js-gallery-link-index-'.$randString.'").trigger("click");

									return true;

								}).queue(function(){
									$("#jsnis-slide-gallery-header-'.$randString.'").trigger("click");
								});

							});
						</script>
						</head>
						<body>
							<div data-role="page" id="Home">
								<div data-role="content">
									<ul data-role="listview" data-insert="true">
										<li><a id="jsnis-slide-gallery-header-'.$randString.'" href="#jsnis-slide-gallery-'.$randString.'">JS Gallery</a></li>
									</ul>
								</div>
							</div>
							<div data-role="page" id="jsnis-slide-gallery-'.$randString.'" class="gallery-page">
							<div data-role="header">
								<h1>'.$showlist['showlist_title'].'</h1>
							</div>
							<div data-role="content">
								<div class="gallery">'."\n";

			for ($i = 0; $i < $countImage; $i++)
			{
				$imgSrc = $objJSNImages->getImageSrc($images[$i], $showlist, $URL);

				$indexLink 	= ($i ==  $imageIndex) ? ' id="jsnis-js-gallery-link-index-'.$randString.'" ' : '';

				$string .= '<a rel="external" '.$indexLink.' href="'.$imgSrc.'"></a>'."\n";

			}

			$string .= '</div></div></div></body></html>';
		}
		return  $string;
	}
	
	function renderAlternativeListImages($imagesData = array(), $showlistInfo = array())
	{
		$html = '';

		if (count( $imagesData ))
		{
			$html .= '<div>';
			$html .= '<p>'.htmlspecialchars(html_entity_decode($showlistInfo['showlist_title'])).'</p>';
			$html .= '<p>'.htmlspecialchars(html_entity_decode($showlistInfo['description'])).'</p>';
			$html .= '<ul>';

			foreach ($imagesData as $image)
			{
				$html .= '<li>';

				if ($image->image_title !='')
				{
					$html .= '<p>'.htmlspecialchars(html_entity_decode($image->image_title)).'</p>';
				}

				if ($image->image_description !='')
				{
					$html .= '<p>'.htmlspecialchars(html_entity_decode($image->image_description)).'</p>';
				}

				if ($image->image_link !='')
				{
					$html .= '<p><a href="'.htmlspecialchars(html_entity_decode($image->image_link)).'">'.htmlspecialchars(html_entity_decode($image->image_link)).'</a></p>';
				}

				$html .= '</li>';
			}
			$html .= '</ul></div>';
		}

		return $html;
	}
}