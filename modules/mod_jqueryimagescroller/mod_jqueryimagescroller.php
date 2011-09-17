<?php



/**



* @version $Id: mod_jqueryimagescroller.php 1.5.0 2006-03-11 18:15:33Z INWEBPRO LTD  http://www.inwebpro.gr



* @package Joomla 1.5



* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.



* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php



* Joomla! is free software. This version may have been modified pursuant



* to the GNU General Public License, and as distributed it includes or



* is derivative of works licensed under the GNU General Public License or



* other free or open source software licenses.



* See COPYRIGHT.php for copyright notices and details.



*/



// no direct access



defined('_JEXEC') or die('Restricted access');



$imagename=array();



//$type 			= $params->get( 'type', 'jpg' );



$folder 		= $params->get( 'folder' );



$noimage		=$params->get( 'noofimage');



$selectedtheme		=$params->get( 'theme');



$moduleheight=$params->get( 'moduleheight');



$modulewidth=$params->get( 'modulewidth');



//    <param name="imagepath1" type="text" default="" label="Path 1" description="A  Path th the image" />



$imagepath[1]     = $params->get( 'imagepath1' );



$imagepath[2]     = $params->get( 'imagepath2' );



$imagepath[3]     = $params->get( 'imagepath3' );



$imagepath[4]     = $params->get( 'imagepath4' );



$imagepath[5]     = $params->get( 'imagepath5' );



$imagepath[6]     = $params->get( 'imagepath6' );



$imagepath[7]     = $params->get( 'imagepath7' );



$imagepath[8]     = $params->get( 'imagepath8' );



$imagepath[9]     = $params->get( 'imagepath9' );



$imagepath[10]     = $params->get( 'imagepath10' );



$imagepath[11]     = $params->get( 'imagepath11' );



$imagepath[12]     = $params->get( 'imagepath12' );



$imagepath[13]     = $params->get( 'imagepath13' );



$imagepath[14]     = $params->get( 'imagepath14' );



$imagepath[15]     = $params->get( 'imagepath15' );



////



$link[1] 		= $params->get( 'link1' );



$link[2] 		= $params->get( 'link2' );



$link[3] 		= $params->get( 'link3' );



$link[4] 		= $params->get( 'link4' );



$link[5] 		= $params->get( 'link5' );



$link[6] 		= $params->get( 'link6' );



$link[7] 		= $params->get( 'link7' );



$link[8] 		= $params->get( 'link8' );



$link[9] 		= $params->get( 'link9' );



$link[10] 		= $params->get( 'link10' );



$link[11] 		= $params->get( 'link11' );



$link[12] 		= $params->get( 'link12' );



$link[13] 		= $params->get( 'link13' );



$link[14] 		= $params->get( 'link14' );



$link[15] 		= $params->get( 'link15' );



$name			= $params->get( 'name1' );



$imagename[1]=$name;



$name			= $params->get( 'name2' );



$imagename[2]=$name;



$name			= $params->get( 'name3' );



$imagename[3]=$name;



$name 			= $params->get( 'name4' );



$imagename[4]=$name;



$name			= $params->get( 'name5' );



$imagename[5]=$name;



$name 			= $params->get( 'name6' );



$imagename[6]=$name;



$name 			= $params->get( 'name7' );



$imagename[7]=$name;



$name 			= $params->get( 'name8' );



$imagename[8]=$name;



$name			= $params->get( 'name9' );



$imagename[9]=$name;



$name 			= $params->get( 'name10' );



$imagename[10]=$name;



$name 			= $params->get( 'name11' );



$imagename[11]=$name;



$name 			= $params->get( 'name12' );



$imagename[12]=$name;



$name 			= $params->get( 'name13' );



$imagename[13]=$name;



$name 			= $params->get( 'name14' );



$imagename[14]=$name;



$name 			= $params->get( 'name15' );



$imagename[15]=$name;



 $width 			= $params->get( 'width' );



 $height 		= $params->get( 'height' );

 $delay 		= $params->get( 'delay' );



if(empty($width))



{



	$width=85;



}



if(empty($height))



{



    $height=85;



}



$the_array 		= array();



$the_image 		= array();



$image_name		= array();



// if folder includes livesite info, remove



if ( strpos($folder, JURI::base()) === 0 ) {



	$folder = str_replace( JURI::base(), '', $folder );



}



// if folder includes absolute path, remove



if ( strpos($folder, JPATH_SITE) === 0 ) {



	$folder= str_replace( JPATH_SITE, '', $folder );



}



// if folder doesnt contain slash to start, add



if ( strpos($folder, '/') !== 0 ) {	



	$folder = '/'. $folder;



}



// construct absolute path to directory



$abspath_folder = JPATH_SITE . $folder;



require(JModuleHelper::getLayoutPath('mod_jqueryimagescroller'));



?>