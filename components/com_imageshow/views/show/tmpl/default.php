<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 7738 2011-08-12 01:22:50Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
$Itemid = $this->Itemid;
$objJSNShow 		= JSNISFactory::getObj('classes.jsn_is_show');
$objJSNLang			= JSNISFactory::getObj('classes.jsn_is_language');
$showListID 		= JRequest::getInt('showlist_id', 0);
$showCaseID 		= JRequest::getInt('showcase_id', 0);
$URLOriginal		= $this->objUtils->overrideURL();
$dispatcher			=& JDispatcher::getInstance();

if(empty($this->showlistInfo))
{
	echo  $this->objUtils->displayShowlistMissingMessage();
	return;
}

if(empty($this->showcaseInfo->theme_name))
{
	echo  $this->objUtils->displayShowcaseMissingMessage();
	return;
}

$URL 				= $URLOriginal.'plugins/jsnimageshow/'.$this->showcaseInfo->theme_name.'/'.$this->showcaseInfo->theme_name.'/assets/swf/';
$widthOverlap		= JRequest::getInt('w', 0);
$heightOverlap		= JRequest::getInt('h', 0);
$display			= false;
$user 				=& JFactory::getUser();
$authAvailable 		= $user->getAuthorisedViewLevels();
$filterLangSys		= $objJSNLang->getFilterLangSystem();
$language			= '';
$random			    = uniqid('').rand(1, 99);
if ($this->objUtils->checkSupportLang())
{
	$objLanguage 		= JFactory::getLanguage();
	$language           = $objLanguage->getTag();
}

if ($widthOverlap !=0 and $heightOverlap!=0)
{
	$height 			= $heightOverlap;
	$width 				= $widthOverlap;
}
else
{
	$height 			= @$this->showcaseInfo->general_overall_height;
	$width 				= @$this->showcaseInfo->general_overall_width;
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

if (@$this->showcaseInfo->background_color =='')
{
	$bgcolorCom = '#ffffff';
}
else
{
	$bgcolorCom = @$this->showcaseInfo->background_color;
}
?>
<?php
	if (!in_array($this->showlistInfo['access'],  $authAvailable))
	{
		$display = false;
	}
	else
	{
		$display = true;
	}

	if ($this->generalSWFLibrary == '')
	{
		$generalSWFLibrary = 0;
	}
	else
	{
		$generalSWFLibrary = $this->generalSWFLibrary;
	}

	$shortEdition   = $this->objUtils->getShortEdition();
	$objJSNTheme 	= JSNISFactory::getObj('classes.jsn_is_showcasetheme');
	$themeInfo 		= $objJSNTheme->getThemeInfo($this->showcaseInfo->theme_name);
?>

<!-- <?php echo JText::_('JSN') .' '. @$this->infoXmlDetail['realName'].' '.strtoupper(@$this->infoXmlDetail ['edition']).' '.@$this->infoXmlDetail ['version'].' - '.@$themeInfo->name. ' '. @$themeInfo->version; ?> -->
<div class="com-imageshow <?php echo @$this->pageclassSFX; ?>">
<?php if(!empty($this->titleWillShow)){ ?>
	<h1 class="componentheading"><?php echo $this->titleWillShow; ?></h1>
<?php } ?>
	<div class="standard-gallery">
		<div class="jsnis-container">
			<div class="jsnis-gallery">
			<!-- fix error: click back browser, no event onclick of flash -->
			<script type="text/javascript"> window.onbeforeunload = function() {} </script>
			<?php if($display == true): ?>
				<?php if($generalSWFLibrary == 1):
					$strShowcase ='';
					$strShowlist = '';
				?>
					<?php
						if($showListID == 0 and $showCaseID == 0){
							$strShowcase = $URLOriginal.$filterLangSys.'option=com_imageshow%26view=show%26Itemid='.$Itemid.'%26format=showcase';
							$strShowlist = $URLOriginal.$filterLangSys.'option=com_imageshow%26view=show%26Itemid='.$Itemid.'%26format=showlist';
						}

						if($showListID != 0 and $showCaseID != 0) {
							$strShowcase = $URLOriginal.$filterLangSys.'option=com_imageshow%26view=show%26showcase_id='.$showCaseID.'%26format=showcase';
							$strShowlist = $URLOriginal.$filterLangSys.'option=com_imageshow%26view=show%26showlist_id='.$showListID.'%26format=showlist';
						}
					?>
					<script type="text/javascript">
						swfobject.embedSWF(
								'<?php echo $URL; ?>Gallery.swf',
								'jsn-imageshow-<?php echo $this->randomNumber; ?>',
								'<?php echo $width;?>',
								'<?php echo $height; ?>',
								'9.0.45',
								'<?php echo $URL; ?>assets/js/expressInstall.swf',
								{
									baseurl:'<?php echo $URL;?>',
									showcase:'<?php echo $strShowcase; ?>',
									showlist:'<?php echo $strShowlist; ?>',
									language:'<?php echo $language; ?>',
									edition:'<?php echo $shortEdition; ?>'
								},
								{
									wmode: 'opaque',
									bgcolor: '<?php echo $bgcolorCom; ?>',
									menu: 'false',
									allowFullScreen:'true'
								});
					</script>
					<div id="jsn-imageshow-<?php echo $this->randomNumber; ?>"></div>
				<?php else:?>
					<object height="<?php echo $height; ?>" class="jsnis-flash-object" width="<?php echo $width;?>" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" id="jsn-imageshow-<?php echo $this->randomNumber; ?>" align="middle">
							<param name="bgcolor" value="<?php echo $bgcolorCom; ?>"/>
							<param name="wmode" value="opaque"/>
							<param name="menu" value="false"/>
							<param name="allowFullScreen" value="true"/>
							<param name="allowScriptAccess" value="sameDomain" />
							<param name="movie" value="<?php echo $URL; ?>Gallery.swf"/>
					<?php if($showListID == 0 and $showCaseID == 0):?>
							<param name="flashvars" value="baseurl=<?php echo $URL;?>&amp;showcase=<?php echo $URLOriginal.$filterLangSys;?>option=com_imageshow%26view=show%26Itemid=<?php echo $Itemid; ?>%26format=showcase&amp;showlist=<?php echo $URLOriginal.$filterLangSys;?>option=com_imageshow%26view=show%26Itemid=<?php echo $Itemid; ?>%26format=showlist&amp;language=<?php echo $language; ?>&amp;edition=<?php echo $shortEdition; ?>"/>
					<?php endif;?>
					<?php if($showListID != 0 and $showCaseID != 0):?>
							<param name="flashvars" value="baseurl=<?php echo $URL;?>&amp;showcase=<?php echo $URLOriginal.$filterLangSys;?>option=com_imageshow%26view=show%26showcase_id=<?php echo $showCaseID; ?>%26format=showcase&amp;showlist=<?php echo $URLOriginal.$filterLangSys;?>option=com_imageshow%26view=show%26showlist_id=<?php echo $showListID; ?>%26format=showlist&amp;language=<?php echo $language; ?>&amp;edition=<?php echo $shortEdition; ?>"/>
					<?php endif;?>
						<embed src="<?php echo $URL; ?>Gallery.swf" menu="false" bgcolor="<?php echo $bgcolorCom; ?>" width="<?php echo $width;?>" height="<?php echo $height; ?>" name="jsn-imageshow-<?php echo $this->randomNumber; ?>" align="middle" allowScriptAccess="sameDomain" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" wmode="opaque"
					<?php if($showListID == 0 and $showCaseID == 0):?>
							flashvars="baseurl=<?php echo $URL;?>&amp;showcase=<?php echo $URLOriginal.$filterLangSys;?>option=com_imageshow%26view=show%26Itemid=<?php echo $Itemid; ?>%26format=showcase&amp;showlist=<?php echo $URLOriginal.$filterLangSys;?>option=com_imageshow%26view=show%26Itemid=<?php echo $Itemid; ?>%26format=showlist&amp;language=<?php echo $language; ?>&amp;edition=<?php echo $shortEdition; ?>"/>
					<?php endif;?>
					<?php if($showListID != 0 and $showCaseID != 0):?>
							flashvars="baseurl=<?php echo $URL;?>&amp;showcase=<?php echo $URLOriginal.$filterLangSys;?>option=com_imageshow%26view=show%26showcase_id=<?php echo $showCaseID; ?>%26format=showcase&amp;showlist=<?php echo $URLOriginal.$filterLangSys;?>option=com_imageshow%26view=show%26showlist_id=<?php echo $showListID; ?>%26format=showlist&amp;language=<?php echo $language; ?>&amp;edition=<?php echo $shortEdition; ?>"/>
					<?php endif;?>
						</object>
				<?php endif;?>
			</div>
			<!-- ALTERNATIVE CONTENT BEGIN -->
				<?php $width = (preg_match('/%/', $width)) ? $width : $width.'px'; ?>
				<div class="jsnis-altcontent" style="width:<?php echo $width;?>; height:<?php echo $height;?>px;">
					<?php if($this->showlistInfo['alternative_status'] == 0){  ?>
						<div>
							<p><?php echo JText::_('SITE_SHOW_YOU_NEED_FLASH_PLAYER'); ?>!</p>
							<p>
								<a href="http://www.adobe.com/go/getflashplayer">
									<?php echo JText::_('SITE_SHOW_GET_FLASH_PLAYER'); ?>
								</a>
							</p>
						</div>
					<?php }?>

					<?php if($this->showlistInfo['alternative_status']==1) {
						 	if($this->moduleAlternate['published'] == 1 && $this->moduleAlternate['module'] != 'mod_imageshow'){
						 		$module = $objJSNShow->getModuleByID($this->moduleAlternate['id']);
						 		echo JModuleHelper::renderModule($module);
							}
					}?>

					<?php if($this->showlistInfo['alternative_status']==2) { ?>
						<div>
							<?php echo $this->articleAlternate['introtext'].$this->articleAlternate['fulltext']; ?>
						</div>
					<?php } ?>

					<?php if($this->showlistInfo['alternative_status']==3) { ?>
						<?php
							$id 		= 'jsnis-alternative-image-'.$random;
							$dimension  = $objJSNShow->renderAlternativeImage($this->showlistInfo['alter_image_path']);
						?>
							<?php if(count($dimension)) { ?>
							<script type='text/javascript'>
								window.addEvent('load', function(){
									JSNISImageShow.scaleResize(<?php echo $dimension['width']; ?>, <?php echo $dimension['height']; ?>, "<?php echo $id; ?>");
								});
								window.addEvent('domready', function(){
									JSNISImageShow.scaleResize(<?php echo $dimension['width']; ?>, <?php echo $dimension['height']; ?>, "<?php echo $id; ?>");
								});
							</script>
							<img id="<?php echo $id; ?>" style="display:none; position: absolute;" src="<?php echo $URLOriginal.$this->showlistInfo['alter_image_path']; ?>" />
							<?php } ?>
					<?php } ?>

					<?php
					if ($this->showlistInfo['alternative_status'] == 4)
					{
						echo $objJSNShow->renderAlternativeGallery($this->imagesData, $this->showlistInfo, $URLOriginal, $random, $width, $height);
					}
					?>
				</div>
			<!-- ALTERNATIVE CONTENT END -->
			<?php else:?>
				<?php if($this->showlistInfo['authorization_status'] == 1): ?>
					<div>
						<?php echo $this->articleAuth['introtext'].$this->articleAuth['fulltext']; ?>
					</div>
				<?php endif;?>
			<?php endif;?>
			<!-- SEO CONTENT BEGIN -->
			<div class="jsnis-seocontent">
				<p><a href="http://www.joomlashine.com" title="Joomla gallery">Joomla gallery</a> by joomlashine.com</p>
				<?php if ($this->showlistInfo['seo_status'] == 0)
				{
					if (count( $this->imagesData ))
					{
						echo $objJSNShow->renderAlternativeListImages($this->imagesData, $this->showlistInfo);
					}
				} ?>

				<?php if($this->showlistInfo['seo_status'] == 1){ ?>
					<div>
						<?php echo $this->seoArticle['introtext'].$this->seoArticle['fulltext']; ?>
					</div>
				<?php } ?>

				<?php if($this->showlistInfo['seo_status'] == 2) {
					 	if($this->seoModule['published'] == 1 && $this->seoModule['module'] != 'mod_imageshow'){
					 		$module = $objJSNShow->getModuleByID($this->seoModule['id']);
							echo JModuleHelper::renderModule($module);
						}
				}?>
			</div>
			<!-- SEO CONTENT END -->
		</div>
	</div>
</div>