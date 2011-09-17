<?php
/**
 * @copyright Copyright (C) 2009 - 2011 inch communications ltdg
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die ('Direct Access to this location is not allowed.');

  function FlexBannerQuery($details) {
  if ($details['sectionid'] == "com_weblinks") { $details['sectionid'] = "1";}
     $menu = &JSite::getMenu();
	if ($menu->getActive() == $menu->getDefault()) { 
        $contentif = "AND IF((select count(distinct bannerid) from `#__fabanner` 
        				where `#__fabanner`.`frontpage` = 1)>0,
                       `#__fabanner`.`bannerid` in (SELECT `#__fabanner`.`bannerid` FROM `#__fabanner` 
                       								WHERE `#__fabanner`.`frontpage` = 1) 
                       								AND `#__fabanner`.`restrictbyid`=1,
                       `#__fabanner`.`restrictbyid`=0)";
	}
  	    elseif (!is_null($details['contentid']) and $details['categoryid'] == 0 and $details['sectionid'] == 0)
    {
        $contentif = "AND IF((select count(distinct bannerid) from `#__fabannerin`
                        where `#__fabannerin`.`contentid` = ".$details['contentid'].")>0,
                       `#__fabanner`.`bannerid` in (SELECT `#__fabannerin`.`bannerid` FROM `#__fabannerin` 
                                                    WHERE `#__fabannerin`.`contentid` = '".$details['contentid']."') 
                                                    AND `#__fabanner`.`restrictbyid`=1,
                       `#__fabanner`.`restrictbyid`=0)";

    } elseif (!is_null($details['contentid']))
    {
        $contentif = "AND IF((select count(distinct bannerid) from `#__fabannerin` where `#__fabannerin`.`contentid` = ".$details['contentid'].")>0,
                       `#__fabanner`.`bannerid` in (SELECT `#__fabannerin`.`bannerid` FROM `#__fabannerin` WHERE `#__fabannerin`.`contentid` = '".$details['contentid']."') AND `#__fabanner`.`restrictbyid`=1,
                       IF(`#__fabanner`.`restrictbyid` and (select count(distinct bannerid) from `#__fabannerin` where `#__fabannerin`.`categoryid` = ".$details['categoryid'].")>0,
                          `#__fabanner`.`bannerid` in (SELECT `#__fabannerin`.`bannerid` FROM `#__fabannerin` WHERE `#__fabannerin`.`categoryid` = '".$details['categoryid']."') AND `#__fabanner`.`restrictbyid`=1,
                          IF(`#__fabanner`.`restrictbyid` and (select count(distinct bannerid) from `#__fabannerin` where `#__fabannerin`.`sectionid` = ".$details['sectionid'].")>0,
                             `#__fabanner`.`bannerid` in (SELECT `#__fabannerin`.`bannerid` FROM `#__fabannerin` WHERE `#__fabannerin`.`sectionid` = '".$details['sectionid']."') AND `#__fabanner`.`restrictbyid`=1,
                             `#__fabanner`.`restrictbyid`=0)))";

    } elseif (!is_null($details['categoryid']))
    {
        $contentif = "AND IF((select count(distinct bannerid) from `#__fabannerin` where `#__fabannerin`.`categoryid` = ".$details['categoryid'].")>0,
                        `#__fabanner`.`bannerid` in (SELECT `#__fabannerin`.`bannerid` FROM `#__fabannerin` WHERE `#__fabannerin`.`categoryid` = '".$details['categoryid']."') AND `#__fabanner`.`restrictbyid`=1,
                        IF(`#__fabanner`.`restrictbyid` and (select count(distinct bannerid) from `#__fabannerin` where `#__fabannerin`.`sectionid` = ".$details['sectionid'].")>0,
                           `#__fabanner`.`bannerid` in (SELECT `#__fabannerin`.`bannerid` FROM `#__fabannerin` WHERE `#__fabannerin`.`sectionid` = '".$details['sectionid']."') AND `#__fabanner`.`restrictbyid`=1,
                           `#__fabanner`.`restrictbyid`=0))";
    } elseif (!is_null($details['sectionid']))
    {
        $contentif = "AND IF((select count(distinct bannerid) from `#__fabannerin` where `#__fabannerin`.`sectionid` = ".$details['sectionid'].")>0,
                       `#__fabanner`.`bannerid` in (SELECT `#__fabannerin`.`bannerid` FROM `#__fabannerin` WHERE `#__fabannerin`.`sectionid` = '".$details['sectionid']."') AND `#__fabanner`.`restrictbyid`=1,
                       `#__fabanner`.`restrictbyid`=0)";
    } else
    {
        $contentif = "AND `#__fabanner`.`restrictbyid`=0";
    }
	return $contentif;
  }
   
  function FlexBannerSWF($bannerwidth, $bannerheight, $link, $imageurl, $blankimageurl, $newwindow, $moduleclass_sfx, $nofollow)    {
        echo '<div class="flexbannergroup' . $moduleclass_sfx . '">';
        echo '<div class="flexbanneritem' . $moduleclass_sfx .'">';
        echo '<div id="flashcontent'.$moduleclass_sfx.'" style="overflow: hidden; width: '.$bannerwidth . 'px; height: ' .  $bannerheight . 'px;">';
		echo '<!-- this iframe is above the Flash, but below the div -->';
		echo '
<iframe src="javascript:false" style="position:relative; top: 0px; left: 0px; display: none; width: ' . $bannerwidth . 'px; height: ' . $bannerheight . 'px; z-index: 5;" id="iframe" frameborder="0" scrolling="no">
    ';
    echo '
</iframe>'; 
echo '<!-- iframe width is width of the div + borders, so 100 + 1 + 1 = 102 -->'; 
echo '<!-- the div we want to be displayed above the Flash -->';
echo '
<div style="position: relative; top: 0px; left: 0px; z-index: 10; display: block; width: ' . $bannerwidth . 'px; height: ' . $bannerheight . 'px; background: none">'; 
    echo '<div class="advert' . $moduleclass_sfx . '" style="width: ' . $bannerwidth . 'px;height: ' . $bannerheight . 'px;">';
        echo '<a ' . $nofollow . ' href="' . $link . '" style="width: ' . $bannerwidth . 'px;height: ' . $bannerheight . 'px;display:block;margin:0;padding:0;border:0;text-decoration:none;" ';
 if ($newwindow){
 echo 'target="_blank"';
};
 echo '><img src="' . $blankimageurl . '" style="position: relative;float:left; top: 0px; left: 0px;width: ' . $bannerwidth . 'px;height: ' . $bannerheight . 'px;display:block;cursor: pointer;" alt="trans" />&nbsp;</a></div>';
  echo '</div>';
  echo '<!-- this is the Flash element which we want as background -->	';
  echo '<script type="text/javascript" src="' . JURI::base() . 'modules/mod_flexbanner/swfobject.js"></script>';
  echo '<script type="text/javascript">';
  echo 'var params = { wmode: "transparent", movie: "' . $imageurl . '" };';
  echo 'swfobject.registerObject("myFlashContent", "9.0.0");';
  echo '</script>';
  echo '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" onclick="window.location.href=\'' . $link . '\'" width=" ' . $bannerwidth . '" height=" ' . $bannerheight . '" style="position:relative;top:-' . $bannerheight . 'px!important;top:-';
  $bannerie = $bannerheight+20;
  echo $bannerie . 'px;">';
  echo '<param name="movie" value="' . $imageurl . '" /><param name="wmode" value="transparent"/>';
  echo '<!--[if !IE]>-->';
  echo '<object type="application/x-shockwave-flash" data="' . $imageurl .   '" width="' . $bannerwidth . '" height="' . $bannerheight . '" >';
  echo '<param name="wmode" value="transparent"/>';
  echo '<!--<![endif]-->';
  echo '<a href="http://www.adobe.com/go/getflashplayer" >';
  echo '<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />';
  echo '</a>';
  echo '<!--[if !IE]>-->';
  echo '</object>';
  echo '<!--<![endif]-->';
  echo '</object>';
  echo '</div>';
  echo '</div>';
  echo '</div>';
}

  function FlexBannerloadlast($bannerwidth, $bannerheight, $link, $imageurl, $bannerimagealt, $newwindow, $moduleclass_sfx, $nofollow) {
  echo '<div class="flexbannergroup' . $moduleclass_sfx .   '" >';
  echo '<div class="flexbanneritem' . $moduleclass_sfx .   '" >';
  echo '<div class="advert' . $moduleclass_sfx . '" style="display:block;width:'.$bannerwidth.'px;height:'.$bannerheight.'px;background:url('.$imageurl.') no-repeat;">';
  echo '<a'. $nofollow . ' href="' . $link . '" style="width:'.$bannerwidth.'px;height:'.$bannerheight.'px;display:block;margin:0;padding:0;border:0;text-decoration:none;" ';
  if ($newwindow){   echo ' target="_blank"'; }
  echo ' >&nbsp;';
  echo '</a>';
  echo '</div>';
  echo '</div>';
  echo '</div>';
}

  function FlexBannerloadfirst($bannerwidth, $bannerheight, $link, $imageurl, $bannerimagealt, $newwindow, $moduleclass_sfx, $nofollow) {
  echo '<div class="flexbannergroup' . $moduleclass_sfx .   '" >';
  echo '<div class="flexbanneritem' . $moduleclass_sfx .   '" >';
  echo '<div class="advert' . $moduleclass_sfx .   '">';
  echo '<a'. $nofollow .   ' href="' . $link .   '"';
  if ($newwindow){   echo ' target="_blank"'; }
  echo ' >';
  echo '<img src="' . $imageurl . '" alt="' . $bannerimagealt .'" title="' . $bannerimagealt  .'" '.($bannerwidth?'width="' . $bannerwidth . '"':'').($bannerheight?' height="' . $bannerheight . '"':'').'  />';
  echo '</a>';
  echo '</div>';
  echo '</div>';
  echo '</div>';
}


 ?>
