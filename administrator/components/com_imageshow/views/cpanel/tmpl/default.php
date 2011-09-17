<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 7427 2011-07-21 04:56:21Z hieudm $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JToolBarHelper::title( JText::_('JSN_IMAGESHOW').': '.JText::_('CPANEL_LAUNCH_PAD'), 'launchpad' );
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
?>

<script language="javascript">
function enableButton()
{
	var showlistElement 	= $('showlist_id');
	var showcaseElement 	= $('showcase_id');
	var showlistID 			= showlistElement.options[showlistElement.selectedIndex].value;
	var showcaseID 			= showcaseElement.options[showcaseElement.selectedIndex].value;
	var presentationMethod	= $('presentation_method');
	$('menutype').setStyle('display', 'none');
	$('jsnis-go-link').href = "javascript:void(0);";
	$('jsnis-go-link-modal').setStyle('display', 'none');
	$('jsnis-go-link').setStyle('display', '');
	$('jsnis-go-link-modal').href = "javascript:void(0);";
	$('jsnis-go-link').addClass("disabled");
	$('jsnis-go-link-modal').addClass("disabled");
	presentationMethod.selectedIndex = 0;

	if (showcaseID != '0' && showlistID != '0')
	{
		$('presentation_method').className = "jsnis-gallery-selectbox active";
		presentationMethod.disabled = false;
	}
	else
	{
		$('presentation_method').className = "jsnis-gallery-selectbox";
		presentationMethod.disabled = true;
	}

	if(showcaseID != 0)
	{
		$('edit-showcase').className = "icon-edit";
		$('edit-showcase').href = 'index.php?option=com_imageshow&controller=showcase&task=edit&cid[]='+showcaseID;
		$('edit-showcase').target = "_blank";
	}
	else
	{
		$('edit-showcase').className = "icon-edit icon-disabled";
		$('edit-showcase').href = "javascript:void(0);";
		$('edit-showcase').target = "";
	}

	if(showlistID != 0)
	{

		$('edit-showlist').className = "icon-edit";
		$('edit-showlist').href = 'index.php?option=com_imageshow&controller=showlist&task=edit&cid[]='+showlistID;
		$('edit-showlist').target = "_blank";

	}
	else
	{
		$('edit-showlist').className = "icon-edit icon-disabled";
		$('edit-showlist').href = "javascript:void(0);";
		$('edit-showlist').target = "";
	}
}

function createViaMenu(create)
{
	var showlistElement = $('showlist_id');
	var showcaseElement = $('showcase_id');
	var menu		    = $('menutype');
	var showlistID 		= showlistElement.options[showlistElement.selectedIndex].value;
	var showcaseID 		= showcaseElement.options[showcaseElement.selectedIndex].value;
	var menutype 		= menu.options[menu.selectedIndex].value;
	if(menutype == "")
	{
		$('jsnis-go-link').href = "javascript:void(0);";
	}
	else
	{
		$('jsnis-go-link').href	= 'index.php?option=com_imageshow&task=launchAdapter&type=menu&menutype=' + menutype + '&' + 'showlist_id=' + showlistID + '&showcase_id=' + showcaseID;
	}
}

function choosePresentMethode()
{
	var showlistElement = $('showlist_id');
	var showcaseElement = $('showcase_id');
	var method		    = $('presentation_method');

	var linkMenu 		= $('link-menu');
	var showlistID 		= showlistElement.options[showlistElement.selectedIndex].value;
	var showcaseID 		= showcaseElement.options[showcaseElement.selectedIndex].value;
	var methodValue		= method.options[method.selectedIndex].value;
	$('menutype').setStyle('display', 'none');
	$('jsnis-go-link-modal').setStyle('display', 'none');
	$('jsnis-go-link').setStyle('display', '');
	$('jsnis-go-link').href = "javascript:void(0);";
	$('jsnis-go-link').removeClass("disabled");
	$('jsnis-go-link-modal').removeClass("disabled")
	if(methodValue == "module")
	{
		$('jsnis-go-link').href = 'index.php?option=com_imageshow&task=launchAdapter&type=module&' + 'showlist_id=' + showlistID + '&showcase_id=' + showcaseID;
	}
	else if(methodValue == "plugin")
	{
		$('jsnis-go-link-modal').href = 'index.php?option=com_imageshow&task=plugin&tmpl=component&' + 'showlist_id=' + showlistID + '&showcase_id=' + showcaseID;
		$('jsnis-go-link-modal').setStyle('display', '');
		$('jsnis-go-link').setStyle('display', 'none');
	}
	else if(methodValue == "menu")
	{
		$('menutype').setStyle('display', 'block');
		$('menutype').selectedIndex = 0;
	}
	else
	{
		$('jsnis-go-link').href = "javascript:void(0);";
		$('jsnis-go-link').addClass("disabled");
		$('jsnis-go-link-modal').addClass("disabled")
	}
}
</script>

<?php
	$objJSNMsg = JSNISFactory::getObj('classes.jsn_is_displaymessage');
	echo $objJSNMsg->displayMessage('LAUNCH_PAD');
?>
<!--[if IE 7]>
        <link rel="stylesheet" type="text/css" href="components/com_imageshow/assets/css/fixie7.css">
<![endif]-->
<div class="jsnis-cpanel-container">
	<div class="jsnis-cpanel-block clearafter">
        <div class="jsnis-process-step"><h3>1</h3></div>
        <div class="jsnis-gallery-option jsnis-dgrey-container jsnis-dgrey-container-style">
			<h3 class="jsnis-element-heading"><?php echo JText::_('CPANEL_CPANEL_SHOWLIST'); ?></h3>
			<p><?php echo JText::_('CPANEL_SETUP_WHAT_IMAGES_TO_BE_SHOWN_IN_THE_GALLERY'); ?></p>
			<div class="jsnis-gallery-selection clearafter">
				<?php echo $this->lists['showlist']; ?>
				<span class="jsnis-gallery-nav-button">
					<a href="javascript:void(0);" id="edit-showlist" target="" class="icon-edit icon-disabled" title="<?php echo JText::_('CPANEL_EDIT_SELECTED_SHOWLIST'); ?>">&nbsp;</a>
					<a href="index.php?option=com_imageshow&controller=showlist&task=add" target="_blank" class="icon-add" title="<?php echo JText::_('CPANEL_CREATE_NEW_SHOWLIST'); ?>">&nbsp;</a>
					<a href="index.php?option=com_imageshow&controller=showlist" target="_blank" class="icon-folder" title="<?php echo JText::_('CPANEL_SEE_ALL_SHOWLISTS'); ?>">&nbsp;</a>
				</span>
			</div>
        </div>
    </div>
    <div class="jsnis-cpanel-block clearafter">
        <div class="jsnis-process-step"><h3>2</h3></div>
        <div class="jsnis-gallery-option jsnis-dgrey-container jsnis-dgrey-container-style">
			<h3 class="jsnis-element-heading"><?php echo JText::_('CPANEL_CPANEL_SHOWCASE'); ?></h3>
			<p><?php echo JText::_('CPANEL_SETUP_HOW_TO_PRESENT_IMAGES_IN_THE_GALLERY'); ?></p>
			<div class="jsnis-gallery-selection clearafter">
				<?php echo $this->lists['showcase']; ?>
				<span class="jsnis-gallery-nav-button">
					<a href="javascript:void(0);" id="edit-showcase" target="" class="icon-edit icon-disabled" title="<?php echo JText::_('CPANEL_EDIT_SELECTED_SHOWCASE'); ?>">&nbsp;</a>
					<a href="index.php?option=com_imageshow&controller=showcase&task=add" target="_blank" class="icon-add" title="<?php echo JText::_('CPANEL_CREATE_NEW_SHOWCASE'); ?>">&nbsp;</a>
					<a href="index.php?option=com_imageshow&controller=showcase" target="_blank" class="icon-folder" title="<?php echo JText::_('CPANEL_SEE_ALL_SHOWCASES'); ?>">&nbsp;</a>
				</span>
			</div>
        </div>
    </div>
   	<div class="jsnis-cpanel-block clearafter">
        <div class="jsnis-process-step"><h3>3</h3></div>
        <div class="jsnis-gallery-option jsnis-orange-container jsnis-orange-container-style">
			<h3 class="jsnis-element-heading"><?php echo JText::_('CPANEL_PRESENTATION'); ?></h3>
			<p><?php echo JText::_('CPANEL_CONFIGURE_HOW_TO_PRESENT_THE_GALLERY'); ?></p>
			<div class="jsnis-gallery-selection clearafter">
				<a href="javascript:void(0);" class="jsnis-button disabled" title="<?php echo JText::_('CPANEL_GO'); ?>" id="jsnis-go-link"><?php echo JText::_('CPANEL_GO'); ?></a>
				<a href="javascript:void(0);" rel="{handler: 'iframe', size: {x: 550, y: 295}}" class="jsnis-button disabled modal" style="display: none;" id="jsnis-go-link-modal" title="<?php echo JText::_('CPANEL_GO'); ?>"><?php echo JText::_('CPANEL_GO'); ?></a>
				<?php echo $this->lists['presentationMethods']; ?>
				<?php echo $this->lists['menu']; ?>
			</div>
        </div>
     </div>
</div>
<div class="clr"></div>
<?php include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'footer.php'); ?>