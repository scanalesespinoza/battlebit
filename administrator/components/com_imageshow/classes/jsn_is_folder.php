<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_folder.php 6648 2011-06-08 10:13:51Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

class JSNISFolder
{
	public static function getInstance()
	{
		static $instanceFolder;

		if ($instanceFolder == null)
		{
			$instanceFolder = new JSNISFolder();
		}
		return $instanceFolder;
	}

	function loadTreeFolder($syncAlbum = array())
	{
		$path 	= str_replace(DS, '/', JPATH_ROOT.DS.'images');
		$path 	= JPath::clean($path);

		if (!is_dir($path))
		{
			return false;
		}

		$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');

		//$xml = "<node label='Image Folder(s)' data=''>\n";
		$xml = "<node label='images' data='images' type='root'>\n";
		$xml .= $objJSNUtils->drawXMLTreeByPath($path, $syncAlbum);
		//$xml .= "</node>\n";

		return $xml;
	}

	function loadImageInFolder($folderPath, $hideImageSpecialName = false)
	{
		$data	 		= new stdClass();
		$arrayImageLoad = array();
		$data->images   		  = $arrayImageLoad;

		$matches = array();
		$pattern = '/^[A-Za-z0-9_-\s]+[A-Za-z0-9_\.-\s]*([\\\\\/\s][A-Za-z0-9_-\s]+[A-Za-z0-9_\.-\s]*)*$/';

		preg_match($pattern, (string) $folderPath, $matches);

		if (count($matches) == 0)
		{
			return false;
		}

		$path 			= str_replace(DS, '/', JPATH_ROOT.DS.$folderPath);
		$folderPath 	= str_replace(DS, '/', $folderPath);
		$objJSNUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNImages  	= JSNISFactory::getObj('classes.jsn_is_images');

		if (!JFolder::exists($path))
		{
			return false;
		}

		$dataFolder	= $objJSNUtils->getImageInPath($path);

		$validateImgName = '/^[A-Za-z0-9_-\s()]+[A-Za-z0-9_\.-\s()]*([\\\\\/\s][A-Za-z0-9_-\s()]+[A-Za-z0-9_\.-\s()]*)*$/';

		foreach ($dataFolder->images as $image)
		{
			$matchesInvalidImage = array();
			$imageInfo 			 = pathinfo($image);

			preg_match($validateImgName, (string)$imageInfo['basename'], $matchesInvalidImage);

			$ImageBaseName = str_replace(DS, '/', $folderPath.DS.$imageInfo['basename']);

			$objImage 						= new stdClass();
			$objImage->image_title 			= $imageInfo['basename'];
			$objImage->image_extid 			= $ImageBaseName;
			$objImage->album_extid 			= $folderPath;
			$objImage->image_link 			= str_replace(DS, '/', dirname(JURI::base()).DS.$folderPath.DS.$imageInfo['basename']);
			$objImage->image_description 	= '';
			$objImage->image_small 			= $ImageBaseName;
			$objImage->image_medium 		= $ImageBaseName;
			$objImage->image_big			= $ImageBaseName;

			if (count($matchesInvalidImage) == 0)
			{
				$objImage->invalid_file_name = true;
			}

			if ($hideImageSpecialName == true  && count($matchesInvalidImage) == 0){

			}else{
				$arrayImageLoad[] = $objImage;
			}
		}

//		$data->specialCharacter   = $dataFolder->specialCharacter;
		$data->images		      = $arrayImageLoad;

		return $data;
	}

	function deleteFolderImages($imgExtID, $showListID)
	{
		$db 				= JFactory::getDBO();
		$objJSNThumbnail 	= JSNISFactory::getObj('classes.jsn_is_imagethumbnail');

		if (is_array($imgExtID) && count($imgExtID))
		{
			$arrayImageThumb 	= array();
			$imageThumb 		= array();

			for ($i = 0 ; $i < count($imgExtID); $i++)
			{
				$imageThumb[] 	= $objJSNThumbnail->getOnceThumbImage(@$imgExtID[$i], $showListID);
				$query 			= 'DELETE FROM #__imageshow_images WHERE image_extid="'.@$imgExtID[$i].'" AND showlist_id='.$showListID;

				$db->setQuery($query);
				$result = $db->query();
			}

			if ($result)
			{
				if (is_array($imageThumb) && count($imageThumb) && $imageThumb != null)
				{
					foreach ($imageThumb as $value)
					{
						$arrayImageThumb [] = $value['image_small'];
					}
					$objJSNThumbnail->deleteThumbImage($arrayImageThumb);
				}
				return true;
			}
			return false;
		}
		return false;
	}

	function insertFolderImages($imgExtID, $imgSmall, $imgMedium, $imgBig, $imgTitle, $imgDescription, $imgLink, $albumID, $showListID, $customData)
	{
		$db 				= JFactory::getDBO();
		$objJSNThumbnail 	= JSNISFactory::getObj('classes.jsn_is_imagethumbnail');
		$imagesTable 		= JTable::getInstance('images', 'Table');
		$memory 			= (int) ini_get('memory_limit');

		if ($memory == 0)
		{
			$memory = 8;
		}

		if (count($imgExtID))
		{
			$objJSNImages = JSNISFactory::getObj('classes.jsn_is_images');
			$ordering = $objJSNImages->getMaxOrderingByShowlistID($showListID);

			if (count($ordering) < 0 || is_null($ordering))
			{
				$ordering = 1;
			}
			else
			{
				$ordering = $ordering[0] + 1;
			}

			for ($i = 0 ; $i < count($imgExtID); $i++)
			{
				if($objJSNImages->checkImageLimition($showListID))
				{
					$result = true;
					break;
				}

				$realPath 	= str_replace('/', DS, @$imgBig[$imgExtID[$i]]);
				$realPath 	= JPATH_ROOT.DS.$realPath;
				$imageSize 	= @filesize($realPath);

				if ($objJSNThumbnail->checkGraphicLibrary())
				{
					$imageThumbPath	= 'images/jsn_is_thumbs';
					$imageName 		= explode('/', @$imgExtID[$i]);
					$imageName 		= end($imageName);
					$imageName 		= uniqid('').rand(1, 99).'_'.$imageName;

					if	(!$objJSNThumbnail->createThumbnail($realPath, $imageName))
					{
						$imageThumbPath = @$imgBig[$imgExtID[$i]];
					}
					else
					{
						$imageThumbPath = $imageThumbPath.'/'.$imageName;
					}

					$imagesTable->showlist_id 	= $showListID;
					$imagesTable->image_extid 	= $imgExtID[$i];
					$imagesTable->album_extid 	= $albumID[$imgExtID[$i]];
					$imagesTable->image_small 	= $imageThumbPath;
					$imagesTable->image_medium 	= $imageThumbPath;
					$imagesTable->image_big		= $imgBig[$imgExtID[$i]];
					$imagesTable->image_title   = $imgTitle[$imgExtID[$i]];
					$imagesTable->image_link 	= $imgLink[$imgExtID[$i]];
					$imagesTable->image_description = $imgDescription[$imgExtID[$i]];
					$imagesTable->ordering		= $ordering;
					$imagesTable->custom_data 	= $customData[$imgExtID[$i]];
					$imagesTable->image_size 	= @$imageSize;
				}
				else
				{
					$imagesTable->showlist_id 	= $showListID;
					$imagesTable->image_extid 	= $imgExtID[$i];
					$imagesTable->album_extid 	= $albumID[$imgExtID[$i]];
					$imagesTable->image_small 	= $imgSmall[$imgExtID[$i]];
					$imagesTable->image_medium 	= $imgMedium[$imgExtID[$i]];
					$imagesTable->image_big		= $imgBig[$imgExtID[$i]];
					$imagesTable->image_title   = $imgTitle[$imgExtID[$i]];
					$imagesTable->image_description = $imgDescription[$imgExtID[$i]];
					$imagesTable->image_link 	= $imgLink[$imgExtID[$i]];
					$imagesTable->ordering		= $ordering;
					$imagesTable->custom_data 	= $customData[$imgExtID[$i]];
					$imagesTable->image_size 	= @$imageSize;
				}

				$imagesTable->encodeURL($replaceSpace = true);
				$imagesTable->trim();
				$result = $imagesTable->store();
				$imagesTable->image_id = null;
				$ordering ++;
			}
			$memoryString = $memory.'M';
			@ini_set('memory_limit', $memoryString);
			if($result)
			{
				return true;
			}
			return false;
		}
		return false;
	}

	function insertMultiFolderImages($showlistID, $arrayAlbum)
	{
		$showlistTable 	= JTable::getInstance('showlist', 'Table');
		$showlistTable->load((int)$showlistID);
		$showlistTable->showlist_source = 1;

		if (!$showlistTable->store())
		{
			return false;
		}

		foreach ($arrayAlbum as $album)
		{
			$images = $this->loadImageInFolder($album);

			foreach ($images as $img)
			{
				$imgExtID[] 						= $img->image_extid;
				$imgTitle[$img->image_extid] 		= $img->image_title;
				$albumID[$img->image_extid] 		= $img->album_extid;
				$imgLink[$img->image_extid] 		= $img->image_link;
				$imgSmall[$img->image_extid] 		= $img->image_small;
				$imgMedium[$img->image_extid] 		= $img->image_medium;
				$imgBig[$img->image_extid] 			= $img->image_big;
				$imgDescription[$img->image_extid]  = $img->image_description;
			}
		}

		$resutl = $this->insertFolderImages($imgExtID, $imgSmall, $imgMedium, $imgBig, $imgTitle, $imgDescription, $imgLink, $albumID, $showlistID);

		if ($resutl)
		{
			return true;
		}
		return false;
	}

	function getPhotoLocalList($arrayImageID, $showListID)
	{
		if (count($arrayImageID) > 0)
		{
			$imageTable 	= JTable::getInstance('images','Table');
			$imageRevert  	= array();

			foreach ($arrayImageID as $ID)
			{
				if ($imageTable->load((int)$ID))
				{
					$imgObj = new stdClass();
					$imgObj->image_id			= $imageTable->image_id;
					$imgObj->image_extid 		= $imageTable->image_extid;
					$imgObj->album_extid 		= $imageTable->album_extid;
					$imgObj->image_title 		= substr($imageTable->image_big, strrpos($imageTable->image_big,'/') + 1);
					$imgObj->image_description 	= '';
					$imgObj->image_link 		= JURI::root().$imageTable->image_big;
					$imgObj->custom_data 		= 0;
					$imageRevert[] = $imgObj;
				}
			}
			return $imageRevert;
		}
		return false;
	}

	function getSyncImages($showlistID, $limitEdition = true)
	{
		$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
		$db 		 = JFactory::getDBO();

		$query = 'SELECT i.album_extid
				  FROM #__imageshow_images as i
				  INNER JOIN #__imageshow_showlist as sl ON sl.showlist_id = i.showlist_id
				  WHERE i.sync = 1
				  AND sl.showlist_source = 1
				  AND sl.published = 1
				  AND i.showlist_id = '.(int)$showlistID. '
				  GROUP BY i.album_extid
				  ORDER BY i.image_id';

		$db->setQuery($query);

		$albums 	 = $db->loadObjectList();
		$images		 = array();
		$limitStatus = $objJSNUtils->checkLimit();

		if (count($albums) > 0)
		{
			$albumLimit = 0;
			foreach ($albums as $album)
			{
				$data 		  = $this->loadImageInFolder($album->album_extid, true);
				$imagesFolder = $data->images;

				if (is_array($imagesFolder))
				{
					$images = array_merge($images , $imagesFolder);
				}

				$albumLimit++;
				if ($limitStatus == true && $albumLimit >= 3 && $limitEdition == true)
				{
					break;
				}
			}

			if (count($images) > 0 && $limitStatus == true && $limitEdition == true)
			{
				$images = array_splice($images, 0, 10);
			}
		}
		
		return $images;
	}

	function addOriginalInfoImages($images)
	{
		$data = array();

		if (is_array($images))
		{
			foreach ($images as $img)
			{
				if ($img->custom_data == 1)
				{
					$img->original_title 	   = substr($img->image_big, strrpos($img->image_big,'/') + 1);
					$img->original_description = '';
					$img->original_link		   = JURI::root().$img->image_big;
				}
				else
				{
					$img->original_title 		= $img->image_title;
					$img->original_description 	= $img->image_description;
					$img->original_link			= $img->image_link;
				}

				$data[] = $img;
			}
		}

		return $data;
	}
}