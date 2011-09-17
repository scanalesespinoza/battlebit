<?php



/**



* @version $Id: mod_jqueryimagescroller.php 1.5.0 2006-03-11 18:15:33Z INWEBPRO LTD   http://www.inwebpro.gr



* @package Joomla



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



global $mainframe;



if($selectedtheme==2){



	$css='imagescroll2.css';



}else if($selectedtheme==3){



	$css='imagescroll3.css';



}else if($selectedtheme==4){



	$css='imagescroll4.css';



}else if($selectedtheme==5){



	$css='imagescroll5.css';



}else{



	$css='imagescroll1.css';



}



?>



<link href="<?php echo JURI::base()?>modules/mod_jqueryimagescroller/assets/css/<?php echo $css ?>" rel="stylesheet" type="text/css"/>



<script src="<?php echo JURI::base()?>modules/mod_jqueryimagescroller/assets/js/jquery.min.js"></script>

<?php



$mode =$params->get('mode');

$baseurl 		 = JURI::base();



 if ($mode=='simple'){

echo "<script src='".$baseurl."modules/mod_jqueryimagescroller/assets/js/scroll.js'></script>"; 

}

if ($mode=='auto'){?>

<script  type='text/javascript'>

(function () {

$.fn.infiniteCarousel = function (width) {

function repeat(str, n) {

return new Array( n + 1 ).join(str);

}

return this.each(function () {

var $wrapper = $('> div', this).css('overflow', 'hidden'),

$slider = $wrapper.find('> ul'),

$items = $slider.find('> li'),

$single = $items.filter(':first')

singleWidth = $single.outerWidth(),

visible = Math.ceil($wrapper.innerWidth() / singleWidth),

currentPage = 1,

pages = Math.ceil($items.length / visible);

/* TASKS */

// 1. pad the pages with empty element if required

if ($items.length % visible != 0) {

// pad

$slider.append(repeat('<li class="empty" style="width:'+width+' />', visible - ($items.length % visible)));

$items = $slider.find('> li');

}



// 2. create the carousel padding on left and right (cloned)

$items.filter(':first').before($items.slice(-visible).clone().addClass('cloned'));

$items.filter(':last').after($items.slice(0, visible).clone().addClass('cloned'));

$items = $slider.find('> li');

// 3. reset scroll

$wrapper.scrollLeft(singleWidth * visible);

// 4. paging function

function gotoPage(page) {

var dir = page < currentPage ? -1 : 1,

n = Math.abs(currentPage - page),

left = singleWidth * dir * visible * n;

$wrapper.filter(':not(:animated)').animate({

scrollLeft : '+=' + left

}, 500, function () {

// if page == last page - then reset position

if (page > pages) {

$wrapper.scrollLeft(singleWidth * visible);

page = 1;

} else if (page == 0) {

page = pages;

$wrapper.scrollLeft(singleWidth * visible * pages);

}

currentPage = page;

});

}

// 5. insert the back and forward link

$wrapper.after('<a href="#" class="arrow back">&lt;</a><a href="#" class="arrow forward">&gt;</a>');

// 6. bind the back and forward links

$('a.back', this).click(function () {

gotoPage(currentPage - 1);

return false;

});

$('a.forward', this).click(function () {

gotoPage(currentPage + 1);

return false;

});

$(this).bind('goto', function (event, page) {

gotoPage(page);

});

// THIS IS NEW CODE FOR THE AUTOMATIC INFINITE CAROUSEL

$(this).bind('next', function () {

gotoPage(currentPage + 1);

});

});

};

})(jQuery);

$(document).ready(function () {

// THIS IS NEW CODE FOR THE AUTOMATIC INFINITE CAROUSEL

var autoscrolling = true;

$('.infiniteCarousel').infiniteCarousel().mouseover(function () {

autoscrolling = false;

}).mouseout(function () {

autoscrolling = true;

});

setInterval(function () {

if (autoscrolling) {

$('.infiniteCarousel').trigger('next');

}

}, <?php echo $delay?>000);

}); 

</script>

<?php 	}?>







<?php 



// check if directory exists



if (is_dir($abspath_folder)) {



	if ($handle = opendir($abspath_folder)) {



		while (false !== ($file = readdir($handle))) {



			if ($file != '.' && $file != '..' && $file != 'CVS' && $file != 'index.html' ) {



				$the_array[] = $file;



		}



		}



	}



	closedir($handle);



	foreach ($the_array as $img) {



		if (!is_dir($abspath_folder .'/'. $img)) {



		//	if (eregi('jpg', $img) || eregi('JPG', $img) || eregi('png', $img)) {



				$the_image[] = $img;



		//	}



		}



	}



	if (!$the_image) {



		echo @_NO_IMAGES;



	} else {



	  	$i= count($the_image);



		if($i<$noimage)



		{



		$noimage=$i;



		}



		else



		{



		$noimage=$noimage;



		}



		if($noimage==1)



		{



				$blockwidth=($width*$noimage)+($noimage*28);



			 	$parentblockwidth=$blockwidth+90;



		}



		else



		{



				if(empty($modulewidth))



				{



					 $blockwidth=($width*$noimage)+($noimage*28);



					 $parentblockwidth=$blockwidth+90;



				}



				else 



				{	



					 $blockwidth=($modulewidth)+($noimage*30);



					 $parentblockwidth= $blockwidth+100;



				}



		}



		if(empty($moduleheight))



		{



			$blockheight=150;



		}



		else



		{



			$blockheight=$moduleheight;



			//$height=$moduleheight-100;



		}



?>	



<?php	



echo "<script LANGUAGE='javascript'>$('.infiniteCarousel').infiniteCarousel($width)</script>";	



$j=1;



echo '      		 <div class="infiniteCarousel" style="width:'.@$parentblockwidth.'px;height:'.@$blockheight.'px">



					<div class="wrapper" style="width:'.$blockwidth.'px" >



					<ul>';



					$k=0;



//allaksa to the_image me imagepath



					foreach($imagepath as $image_name)



{ 



  	$abspath_image	= $abspath_folder . '/'. $image_name;



	 	$size 			= getimagesize ($abspath_image);		



	  	if ($width == '') {



	  		($size[0] > 100 ? $width = 100 : $width = $size[0]);



	  	}



	  	if ($height == '') {



			$coeff 	= $size[0]/$size[1];



	  		$height = (int) ($width/$coeff);



	  	}



$image = JURI::base() . $folder .'/'. $image_name;



if  ($image_name!=null){



					echo ' <li class="cloned" style="width:'.$width.'" >  <div><a  title="'.$imagename[$j].'" target="_blank" href="'.$link[$j].'"><img  src="'.$image.'" border="0"  height='.$height.'  width='.$width.' /></a></div> <div></div> </li>';



			 $j++;		



}



			}



					echo '</ul>



					</div></div>';



}



}



?>