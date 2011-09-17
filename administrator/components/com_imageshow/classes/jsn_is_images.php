<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_images.php 6670 2011-06-09 09:25:55Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

class JSNISImages
{
	var $_db = null;

	function JSNISImages()
	{
		if($this->_db == null)
		{
			$this->_db = JFactory::getDBO();
		}
	}

	public static function getInstance()
	{
		static $instanceImages;

		if ($instanceImages == null)
		{
			$instanceImages = new JSNISImages();
		}
		return $instanceImages;
	}

	function getImagesByShowlistID($showListID, $resultType = 'loadObjectList')
	{
		$query 	= 'SELECT
						image_id, image_title,
						image_small, image_big, image_medium,
						image_description, image_link,
						image_extid, album_extid, image_size, custom_data
				   FROM #__imageshow_images
				   WHERE showlist_id =' .(int) $showListID . '
				   ORDER BY ordering ASC';
		$this->_db->setQuery($query);
		return $this->_db->$resultType();
	}

	function prepareImagesByShowlistID($showlistID, $sourceType)
	{
		$images = $this->getImagesByShowlistID($showlistID);
		return $this->addOriginalInfoImagesBySource($sourceType, $images);
	}

	function addOriginalInfoImagesBySource($source, $images)
	{
		if (!empty($source))
		{
			switch ($source)
			{
				case 1:
					$control = JSNISFactory::getObj('classes.jsn_is_folder');
				break;
				case 2:
					$control = JSNISFactory::getObj('classes.jsn_is_flickr');
				break;
				case 3:
					$control = JSNISFactory::getObj('classes.jsn_is_picasa');
				break;
				case 4:
					$control = JSNISFactory::getObj('classes.jsn_is_phoca');
				break;
				case 5:
					$control = JSNISFactory::getObj('classes.jsn_is_joomga');
				break;
			}

			$data = $control->addOriginalInfoImages($images);
			return $data;
		}
		return $images;
	}

	function deleteObsoleteImage($obsoleteImageID, $showlistID)
	{
		$imagesTable 	= JTable::getInstance('images','Table');

		$query 			= 'DELETE FROM #__imageshow_images
						   WHERE image_id in ('.$obsoleteImageID.')
						   AND showlist_id ='.(int) $showlistID;
		$this->_db->setQuery($query);

		if (!$this->_db->query())
		{
			return false;
		}
		else
		{
			$totalImage = $this->countImagesShowList($showlistID);
			for ( $i=0; $i < $totalImage[0]; $i++ )
			{
				$imagesTable->reorder('showlist_id = '.(int) $showlistID);
			}
		}

		return true;
	}

	function countImagesShowList($showlistID)
	{
		$query 	= 'SELECT COUNT(*) FROM #__imageshow_images
				   WHERE showlist_id ='.(int) $showlistID;

		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}

	function getImageExtByShowlistID($showlistID, $resultType = 'loadResultArray')
	{
		$query = "SELECT image_extid
				  FROM #__imageshow_images
				  WHERE showlist_id = ".(int) $showlistID;
		$this->_db->setQuery($query);
		return $this->_db->$resultType();
	}

	function getMaxOrderingByShowlistID($showListID)
	{
		$query 	= "SELECT MAX(ordering) FROM #__imageshow_images WHERE showlist_id = ".$showListID." GROUP BY showlist_id";
		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}

	function updateOrder($arrayOrdering, $showlistID)
	{
		if (count($arrayOrdering) > 0 && !empty($showlistID))
		{
			foreach ($arrayOrdering as $key => $value)
			{
				$query = "UPDATE #__imageshow_images
						  SET ordering = ".$this->_db->quote( $this->_db->getEscaped( $value ), false )."
						  WHERE image_extid = ".$this->_db->quote( $this->_db->getEscaped( $key ), false )."
						  AND showlist_id = ".(int)$showlistID;

				$this->_db->setQuery($query);

				$result = $this->_db->query();
			}

			if ($result)
			{
				return true;
			}
		}
		return false;
	}

	function getImagesByAlbumID($albumId, $showlistID)
	{
		$query = "SELECT
					image_id, image_extid,
					album_extid, showlist_id
				  FROM #__imageshow_images
				  WHERE showlist_id = ".(int)$showlistID."
				  AND album_extid = ".$this->_db->Quote($this->_db->getEscaped( $albumId, false ), false );

		$this->_db->setQuery($query);
		return $this->_db->loadAssocList();
	}

	function updateImageDetail($arrayKeyImages, $arrayPost)
	{
		$imageTable 	= JTable::getInstance('images','Table');
		$objJSNShowlist 	= JSNISFactory::getObj('classes.jsn_is_showlist');

		if (count($arrayKeyImages) > 0)
		{
			foreach ($arrayKeyImages as $imageExtID)
			{
				$query = 'SELECT image_id
						  FROM #__imageshow_images
						  WHERE showlist_id = '.(int)$arrayPost->showlistID.'
						  AND image_extid = '.$this->_db->quote($imageExtID).'
						  GROUP BY image_id';
				$this->_db->setQuery($query);
				$result = $this->_db->loadResult();

				if ($result)
				{
					if ($imageTable->load((int)$result))
					{
						//image folder
						$realPath 	= str_replace('/', DS, $arrayPost->imgBig[$imageExtID]);
						$realPath 	= JPATH_ROOT.DS.$realPath;
						$imageSize 	= @filesize($realPath);
						$imageTable->image_size			= @$imageSize;
						// image folder

						$imageTable->image_title 		= @$arrayPost->imgTitle[$imageExtID];
						$imageTable->image_description 	= @$arrayPost->imgDescription[$imageExtID];
						$imageTable->image_link 		= @$arrayPost->imgLink[$imageExtID];
						$imageTable->image_small		= @$arrayPost->imgSmall[$imageExtID];
						$imageTable->image_medium		= @$arrayPost->imgMedium[$imageExtID];
						$imageTable->image_big			= @$arrayPost->imgBig[$imageExtID];
						$imageTable->custom_data 		= @$arrayPost->customData[$imageExtID];
						$imageTable->encodeURL($replaceSpace = true);
						$imageTable->trim();

						if ($imageTable->store())
						{
							$objJSNShowlist->updateDateModifiedShowlist((int)$imageTable->showlist_id);
						}
					}
				}
			}
		}
	}

	function getImagesByImageID($arrayImageID)
	{
		if (count($arrayImageID) > 0)
		{
			$stringImageID = implode(',', $arrayImageID);

			$query 	= 'SELECT
							image_id, image_title,
							image_description, image_link,
							image_extid, custom_data
					   FROM #__imageshow_images
					   WHERE image_id IN ('.$stringImageID.')';

			$this->_db->setQuery($query);
			return $this->_db->loadObjectList();
		}
		return false;
	}

	function checkImageLimition($showlistID)
	{
		$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
		$limitStatus	    = $objJSNUtils->checkLimit();
		$count 				= $this->countImagesShowList($showlistID);

		if (@$count[0] >= 10 && $limitStatus == true)
		{
			return true;
		}
		return false;
	}

	function getSyncImagesByShowlistID($showlistID, $limitEdition = true)
	{
		$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');
		$showlist 		= $objJSNShowlist->getShowlistByID($showlistID);
		$images 		= null;

		if	(!empty($showlist))
		{
			if ($showlist['showlist_source'] == 1)
			{
				$objJSNFolder 	= JSNISFactory::getObj('classes.jsn_is_folder');
				$images 		= $objJSNFolder->getSyncImages($showlistID, $limitEdition);
			}
			else if ($showlist['showlist_source'] == 4)
			{
				$objJSNPhoca 	= JSNISFactory::getObj('classes.jsn_is_phoca');
				$images 		= $objJSNPhoca->getSyncImages($showlistID, $limitEdition);
			}
			else if ($showlist['showlist_source'] == 5)
			{
				$objJSNJoomga 	= JSNISFactory::getObj('classes.jsn_is_joomga');
				$images 		= $objJSNJoomga->getSyncImages($showlistID, $limitEdition);
			}
		}

		return $images;
	}

	function getSyncAlbumsByShowlistID($showlistID)
	{
		$query = 'SELECT album_extid
				  FROM #__imageshow_images
				  WHERE showlist_id ='.(int)$showlistID.'
				  AND sync = 1
				  GROUP BY album_extid';

		$this->_db->setQuery($query);
		return $this->_db->loadResultArray();
	}

	function saveSyncAlbum($showlistID, $arrayAlbum)
	{
		$query = 'DELETE FROM #__imageshow_images WHERE sync = 1 AND showlist_id ='.(int)$showlistID;
		$this->_db->setQuery($query);

		if ($this->_db->query() && count($arrayAlbum) > 0)
		{
			$imagesTable = JTable::getInstance('images', 'Table');

			foreach ($arrayAlbum as $album)
			{
				$imagesTable->showlist_id = $showlistID;
				$imagesTable->album_extid = $album;
				$imagesTable->sync		  = 1;

				if ($imagesTable->store() == false)
				{
					return false;
				}

				$imagesTable->image_id = null;
			}
		}
		return true;
	}
	
	function getImageSrc($imageObj , $showlistInfo = array(), $URL = '')
	{

		$imgSrc = '';

		if (is_object($imageObj))
		{
			if ($showlistInfo['showlist_source'] == 2)
			{
				$objJSNFlickr 	= JSNISFactory::getObj('classes.jsn_is_flickr');
				$imgSrc 		= $objJSNFlickr->getBigImageBySizeConfig($showlistInfo['configuration_id'], $imageObj->image_big);
			}
			else
			{
				if ($showlistInfo['showlist_source'] == 3)// picasa
				{
					$imgSrc = $imageObj->image_big;
				}
				else // folder , joomga, phoca
				{
					$imgSrc = $URL.$imageObj->image_big;
				}
			}

			$imgSrc = $this->_db->getEscaped($imgSrc);
		}

		return $imgSrc;
	}
}