<?php
/**
 * @version		Id: media.php 14401 2010-01-26 14:10:00Z louis 
 * @package		Joomla
 * @subpackage	Media
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

/**
 * @package		Joomla
 * @subpackage	Media
 */
 
/**
 * @Modified by JoomlaShine.com Team
 * @version $Id: media.php 6650 2011-06-08 10:30:26Z giangnd $
 */
 
class MediaHelper
{
	/**
	 * Checks if the file is an image
	 * @param string The filename
	 * @return boolean
	 */
	function isImage( $fileName )
	{
		static $imageTypes = 'xcf|odg|gif|jpg|png|bmp';
		return preg_match("/$imageTypes/i",$fileName);
	}

	/**
	 * Checks if the file is an image
	 * @param string The filename
	 * @return boolean
	 */
	function getTypeIcon( $fileName )
	{
		// Get file extension
		return strtolower(substr($fileName, strrpos($fileName, '.') + 1));
	}

	/**
	 * Checks if the file can be uploaded
	 *
	 * @param array File information
	 * @param string An error message to be returned
	 * @return boolean
	 */
	function canUpload( $file, &$err )
	{
		$params = JComponentHelper::getParams( 'com_media' );

		if(empty($file['name'])) {
			$err = 'Please input a file for upload';
			return false;
		}

		jimport('joomla.filesystem.file');
		if ($file['name'] !== JFile::makesafe($file['name'])) {
			$err = 'WARNFILENAME';
			return false;
		}

		$format = strtolower(JFile::getExt($file['name']));

		$allowable = explode( ',', $params->get( 'upload_extensions' ));
		$ignored = explode(',', $params->get( 'ignore_extensions' ));
		if (!in_array($format, $allowable) && !in_array($format,$ignored))
		{
			$err = 'WARNFILETYPE';
			return false;
		}

		$maxSize = (int) $params->get( 'upload_maxsize', 0 );
		$maxSize = $maxSize*1024*1024;
		if ($maxSize > 0 && (int) $file['size'] > $maxSize)
		{
			$err = 'WARNFILETOOLARGE';
			return false;
		}

		$user = JFactory::getUser();
		$imginfo = null;
		if($params->get('restrict_uploads',1) ) {
			$images = explode( ',', $params->get( 'image_extensions' ));
			if(in_array($format, $images)) { // if its an image run it through getimagesize
				if(($imginfo = getimagesize($file['tmp_name'])) === FALSE) {
					$err = 'WARNINVALIDIMG';
					return false;
				}
			} else if(!in_array($format, $ignored)) {
				// if its not an image...and we're not ignoring it
				$allowed_mime = explode(',', $params->get('upload_mime'));
				$illegal_mime = explode(',', $params->get('upload_mime_illegal'));
				if(function_exists('finfo_open') && $params->get('check_mime',1)) {
					// We have fileinfo
					$finfo = finfo_open(FILEINFO_MIME);
					$type = finfo_file($finfo, $file['tmp_name']);
					if(strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime)) {
						$err = 'WARNINVALIDMIME';
						return false;
					}
					finfo_close($finfo);
				} else if(function_exists('mime_content_type') && $params->get('check_mime',1)) {
					// we have mime magic
					$type = mime_content_type($file['tmp_name']);
					if(strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime)) {
						$err = 'WARNINVALIDMIME';
						return false;
					}
				} else if(!$user->authorize( 'login', 'administrator' )) {
					$err = 'WARNNOTADMIN';
					return false;
				}
			}
		}

		$xss_check =  JFile::read($file['tmp_name'],false,256);
		$html_tags = array('abbr','acronym','address','applet','area','audioscope','base','basefont','bdo','bgsound','big','blackface','blink','blockquote','body','bq','br','button','caption','center','cite','code','col','colgroup','comment','custom','dd','del','dfn','dir','div','dl','dt','em','embed','fieldset','fn','font','form','frame','frameset','h1','h2','h3','h4','h5','h6','head','hr','html','iframe','ilayer','img','input','ins','isindex','keygen','kbd','label','layer','legend','li','limittext','link','listing','map','marquee','menu','meta','multicol','nobr','noembed','noframes','noscript','nosmartquotes','object','ol','optgroup','option','param','plaintext','pre','rt','ruby','s','samp','script','select','server','shadow','sidebar','small','spacer','span','strike','strong','style','sub','sup','table','tbody','td','textarea','tfoot','th','thead','title','tr','tt','ul','var','wbr','xml','xmp','!DOCTYPE', '!--');
		foreach($html_tags as $tag) {
			// A tag is '<tagname ', so we need to add < and a space or '<tagname>'
			if(stristr($xss_check, '<'.$tag.' ') || stristr($xss_check, '<'.$tag.'>')) {
				$err = 'WARNIEXSS';
				return false;
			}
		}
		return true;
	}

	function parseSize($size)
	{
		if ($size < 1024) {
			return $size . ' bytes';
		}
		else
		{
			if ($size >= 1024 && $size < 1024 * 1024) {
				return sprintf('%01.2f', $size / 1024.0) . ' Kb';
			} else {
				return sprintf('%01.2f', $size / (1024.0 * 1024)) . ' Mb';
			}
		}
	}

	function imageResize($width, $height, $target)
	{
		//takes the larger size of the width and height and applies the
		//formula accordingly...this is so this script will work
		//dynamically with any size image
		if ($width > $height) {
			$percentage = ($target / $width);
		} else {
			$percentage = ($target / $height);
		}

		//gets the new value and applies the percentage, then rounds the value
		$width = round($width * $percentage);
		$height = round($height * $percentage);

		return array($width, $height);
	}

	function countFiles( $dir )
	{
		$total_file = 0;
		$total_dir = 0;

		if (is_dir($dir)) {
			$d = dir($dir);

			while (false !== ($entry = $d->read())) {
				if (substr($entry, 0, 1) != '.' && is_file($dir . DIRECTORY_SEPARATOR . $entry) && strpos($entry, '.html') === false && strpos($entry, '.php') === false) {
					$total_file++;
				}
				if (substr($entry, 0, 1) != '.' && is_dir($dir . DIRECTORY_SEPARATOR . $entry)) {
					$total_dir++;
				}
			}

			$d->close();
		}

		return array ( $total_file, $total_dir );
	}

}