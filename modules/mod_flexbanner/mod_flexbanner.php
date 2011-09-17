<?php
/**
 * @copyright Copyright (C) 2009 inch communications ltd
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die ('Direct Access to this location is not allowed.');
//error_reporting(E_ALL);
//ini_set('display_errors',true);
require_once (JApplicationHelper::getPath('front', 'com_flexbanner'));
// Include the functions only once
require_once (dirname(__FILE__).DS.'helper.php');

// locationid must be an integer
$locationid = intval($params->get('locationid', ''));
$moduleclass_sfx = $params->get('moduleclass_sfx', '');
$id = intval(JRequest::getVar('id', NULL));
    $task = NULL;
    $menu = &JSite::getMenu();
	if ($menu->getActive() == $menu->getDefault()) 
		{ $task = "frontpage"; } 
		ELSE {
		$task = JRequest::getVar('view', NULL);}
$loadlast = ($params->get('loadlast', 0));
$newwindow = ($params->get('newwindow', 0));
$enablecsa = ($params->get('enablecsa', 0));
$enabletrans = ($params->get('enabletrans', 0));
$enablenofollow = ($params->get('enablenofollow', 0));
$details = array ("sectionid"=>NULL, "categoryid"=>NULL, "contentid"=>NULL, "langaugeid"=>NULL, "frontpage"=>NULL);
$blankimageurl = JURI::base().JRoute::_('modules/mod_flexbanner/trans.gif');
$nofollow = '';
if ($enablenofollow)
{
    $nofollow = ' rel="nofollow"';
}
$database = & JFactory::getDBO();
$conf = & JFactory::getConfig();
$fb_language = 0;
if (file_exists(JPATH_SITE.'/administrator/components/com_joomfish/joomfish.php'))
{
    $fb_language = 1;
}

// Get the active language
if ($fb_language == 1)
{
    $config = &JFactory::getConfig();
	$iso_client_lang = $config->getValue('language');
//	$iso_client_lang=substr($iso_client_lang, 0, 2);
	$iso_client_lang= '"'.$iso_client_lang.'"';
}
  
    //Get the active menu item
    switch($task)

    {

        case 'article':
            $contentitem = new flexAdContent($database);
            $contentitem->load($id);
            $details = array ("sectionid"=>$contentitem->sectionid, "categoryid"=>$contentitem->catid, "contentid"=>$contentitem->id);
            break;
        case 'blogcategory':
        case 'category':
            $categoryid = $id;
            $category = new flexAdCategories($database);
            $category->load($id);
            $details = array ("sectionid"=>$category->section, "categoryid"=>$category->id, "contentid"=>NULL);
            break;
        case 'blogsection':
        case 'section':
            $details = array ("sectionid"=>$id, "categoryid"=>NULL, "contentid"=>NULL);
            break;
		case 'frontpage':
            $details = array ("sectionid"=>NULL, "categoryid"=>NULL, "contentid"=>NULL, "langaugeid"=>NULL, "frontpage"=>1);
            break;
        default:
            // echo "Not in a category, section or content item view";
            break;
    }


$contentif = '';

if ($enablecsa)
{
	$contentif = FlexBannerQuery($details);
}
    if ($fb_language == 1)
    {
$sql = "SELECT `#__fabanner`.`bannerid`,
               `#__falocation`.`locationname`,
               `#__fabanner`.`imageurl`,
               `#__fabanner`.`imagealt`,
               `#__fabanner`.`customcode`,
               `#__fabanner`.`startdate`,
               `#__fabanner`.`enddate`,
               `#__fabanner`.`lastreset`,
               `#__fabanner`.`impressions`,
               `#__fabanner`.`clicks`,
               `#__fabanner`.`maximpressions`,
               `#__fabanner`.`maxclicks`,
               `#__fabanner`.`linkid`,
               `#__fasize`.`width`,
               `#__fasize`.`height`,
               `#__fabanner`.`restrictbyid`,
               `#__fabanner`.`dailyimpressions`,
               if(`#__faclient`.`barred` OR `#__fabanner`.`finished`OR NOT `#__fabanner`.`published`, 0, 1) as `valid`
        FROM   `#__fabanner`
        Inner Join `#__fabannerlocation` ON `#__fabanner`.`bannerid` = `#__fabannerlocation`.`bannerid`
        Inner Join `#__faclient` ON `#__fabanner`.`clientid` = `#__faclient`.`clientid`
        Inner Join `#__falocation` ON `#__fabannerlocation`.`locationid` = `#__falocation`.`locationid`
        Inner Join `#__fasize` ON `#__fabanner`.`sizeid` = `#__fasize`.`sizeid`
        Inner Join `#__fabannerlang` ON `#__fabanner`.`bannerid` = `#__fabannerlang`.`bannerid`
        WHERE `#__falocation`.`locationid` = $locationid $contentif
          AND `#__fabanner`.`published` = 1 
		  AND `#__fabannerlang`.`languageid` = $iso_client_lang
        ORDER BY `restrictbyid` desc, `dailyimpressions`";
	} else {
$sql = "SELECT `#__fabanner`.`bannerid`,
               `#__falocation`.`locationname`,
               `#__fabanner`.`imageurl`,
               `#__fabanner`.`imagealt`,
               `#__fabanner`.`customcode`,
               `#__fabanner`.`startdate`,
               `#__fabanner`.`enddate`,
               `#__fabanner`.`lastreset`,
               `#__fabanner`.`impressions`,
               `#__fabanner`.`clicks`,
               `#__fabanner`.`maximpressions`,
               `#__fabanner`.`maxclicks`,
               `#__fabanner`.`linkid`,
               `#__fasize`.`width`,
               `#__fasize`.`height`,
               `#__fabanner`.`restrictbyid`,
               `#__fabanner`.`dailyimpressions`,
               if(`#__faclient`.`barred` OR `#__fabanner`.`finished`OR NOT `#__fabanner`.`published`, 0, 1) as `valid`
        FROM   `#__fabanner`
        Inner Join `#__fabannerlocation` ON `#__fabanner`.`bannerid` = `#__fabannerlocation`.`bannerid`
        Inner Join `#__faclient` ON `#__fabanner`.`clientid` = `#__faclient`.`clientid`
        Inner Join `#__falocation` ON `#__fabannerlocation`.`locationid` = `#__falocation`.`locationid`
        Inner Join `#__fasize` ON `#__fabanner`.`sizeid` = `#__fasize`.`sizeid`
        WHERE `#__falocation`.`locationid` = $locationid $contentif
          AND `#__fabanner`.`published` = 1
        ORDER BY `restrictbyid` desc, `dailyimpressions`";
	
	}
global $mainframe;
$database = & JFactory::getDBO();
$database->setQuery($sql);
$database->query();


if ($database->getNumRows() > 0)
{
    $banner = $database->loadObjectList();
    $bannernumber = count($banner);
    $banner = $banner[rand(0, $bannernumber-1)];
    $bannerdetails = new flexAdBanner($database);
    $bannerdetails->load($banner->bannerid);

		        $link = JRoute::_('index.php?option=com_flexbanner&amp;task=click&amp;bannerid='.$banner->bannerid);
        		$imageurl = JURI::base().JRoute::_('images/banners/'.$banner->imageurl);
				$bannerwidth = $banner->width; 
				$bannerheight = $banner->height;
				$bannerimagealt = $banner->imagealt;
				
        if (trim($banner->customcode))
        {
            echo stripslashes($banner->customcode);
        } elseif (preg_match("/\.swf/", $banner->imageurl))
        {
            FlexBannerSWF($bannerwidth, $bannerheight, $link, $imageurl, $blankimageurl, $newwindow, $moduleclass_sfx, $nofollow);
    $bannerdetails->impressions += 1;
    $bannerdetails->dailyimpressions += 1;

        } else
        {
            if ($loadlast)
            {
                FlexBannerloadlast($bannerwidth, $bannerheight, $link, $imageurl, $bannerimagealt, $newwindow, $moduleclass_sfx, $nofollow);
    $bannerdetails->impressions += 1;
    $bannerdetails->dailyimpressions += 1;

            } else
            {
                FlexBannerloadfirst($bannerwidth, $bannerheight, $link, $imageurl, $bannerimagealt, $newwindow, $moduleclass_sfx, $nofollow);
    $bannerdetails->impressions += 1;
    $bannerdetails->dailyimpressions += 1;

            }
        }
    
    $bannerdetails->store();
}

 ?>
