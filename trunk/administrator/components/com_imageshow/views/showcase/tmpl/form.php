<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form.php 6637 2011-06-08 08:21:13Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JHTML::_('behavior.tooltip');
$edit 	= JRequest::getVar('edit',true);
$editor = JFactory::getEditor();
$cid 	= JRequest::getVar( 'cid', array(0), 'get', 'array' );
JToolBarHelper::title(JText::_('JSN_IMAGESHOW').': '.JText::_('SHOWCASE_SHOWCASE_SETTINGS'), 'showcase');
JToolBarHelper::apply();
JToolBarHelper::save();
if (!$edit)
{
	JToolBarHelper::cancel();
}
else
{
	JToolBarHelper::cancel('cancel', 'Close');
}
JToolBarHelper::divider();
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$showCaseID = (int) $this->items->showcase_id;
$user 		= JFactory::getUser();
?>
<script language="javascript" type="text/javascript">
	window.addEvent('domready', function()
	{
		JSNISImageShow.ShowcaseChangeBg();
		JSNISImageShow.simpleSlide('jsnis-showcase-detail-heading',
				'jsnis-showcase-detail-slide',
				'jsnis-showcase-detail-arrow',
				'jsnis-showcase-detail-title',
				'jsnis-element-heading-arrow-collapse',
				'jsnis-element-heading-title');

		$('jsnis-showcase-detail-heading').addEvent('click', function()
		{
			JSNISImageShow.setCookieHeadingTitleStatus('jsnis-heading-title-showcase-<?php echo $user->id; ?>');
		});

		var showcaseHeadingTitleStatus = JSNISUtils.getCookie('jsnis-heading-title-showcase-<?php echo $user->id; ?>');

		if (showcaseHeadingTitleStatus == 'close')
		{
			$('jsnis-showcase-detail-heading').fireEvent('click');
			JSNISUtils.setCookie('jsnis-heading-title-showcase-<?php echo $user->id; ?>', 'close', 15);
		}
	});

	var original_value = '';
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;

		if (pressbutton == 'cancel')
		{
			submitform( pressbutton );
			return;
		}

		if (form.showcase_title.value == "")
		{
			alert( "<?php echo JText::_('SHOWCASE_REQUIRED_FIELD_TITLE_CANNOT_BE_LEFT_BLANK', true); ?>");
		}

		else if(form.theme_name == undefined)
		{
			alert( "<?php echo JText::_('SHOWCASE_SELECT_A_SHOWCASE_THEME', true); ?>");
		}
		else
		{
			submitform( pressbutton );
		}
	}

	function getInputValue(object)
	{
		original_value = object.value;
	}

	function checkInputValue(object, percent)
	{
		var patt;
		var form 		= document.adminForm;
		var msg;
		if(percent == 1)
		{
			patt=/^[0-9]+(\%)?$/;
			msg = "<?php echo JText::_('SHOWCASE_ALLOW_ONLY_DIGITS_AND_THE_PERCENTAGE_CHARACTER', true); ?>";
		}
		else
		{
			patt=/^[0-9]+$/;
			msg = "<?php echo JText::_('SHOWCASE_ALLOW_ONLY_DIGITS', true); ?>";
		}
		if(!patt.test(object.value))
		{
			alert (msg);
			object.value = original_value;
			return;
		}
	}



</script>
<!--[if IE 7]>
	<link href="<?php echo JURI::base();?>components/com_imageshow/assets/css/fixie7.css" rel="stylesheet" type="text/css" />
<![endif]-->

<form action="index.php?option=com_imageshow&controller=showcase" method="POST" name="adminForm" id="adminForm">
<?php
	$uri	        = JURI::getInstance();
	$base['prefix'] = $uri->toString( array('scheme', 'host', 'port'));
	$base['path']   =  rtrim(dirname(str_replace(array('"', '<', '>', "'",'administrator'), '', $_SERVER["PHP_SELF"])), '/\\');
	$url 			= $base['prefix'].$base['path'].'/';
?>
	<div id="jsnis-showcase-detail-heading" class="jsnis-dgrey-heading jsnis-dgrey-heading-style">
		<h3 class="jsnis-element-heading">
			<?php echo JText::_('SHOWCASE_TITLE_SHOWCASE_DETAILS'); ?>
			<span id="jsnis-showcase-detail-title" class="jsnis-element-heading-title"><?php echo ($this->generalData['generalTitle'] != '') ? ': '.$this->generalData['generalTitle'] : ''; ?></span>
			<span id="jsnis-showcase-detail-arrow" class="jsnis-element-heading-arrow "></span>
		</h3>
	</div>
	<table id="jsnis-showcase-detail-slide" class="jsnis-showcase-settings" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td valign="top" style="width: 50%;"><fieldset>
					<legend><?php echo JText::_('SHOWCASE_GENERAL_GENERAL');?></legend>
					<table class="admintable showcase-details">
						<?php
							if($showCaseID != 0){
						?>
						<tr>
							<td class="key"><?php echo JText::_('ID');?></td>
							<td><?php echo $showCaseID; ?></td>
						</tr>
						<?php
							}
						?>
						<tr>
							<td class="key"><?php echo JText::_('SHOWCASE_GENERAL_TITLE');?></td>
							<td><input type="text" style="width: 90%;" name="showcase_title" id="showcase_title" value="<?php echo $this->generalData['generalTitle']; ?>" />
								<font color="Red"> *</font></td>
						</tr>
						<tr>
							<td class="key"><?php echo JText::_('SHOWCASE_GENERAL_PUBLISHED');?></td>
							<td><?php echo $this->lists['published']; ?></td>
						</tr>
						<tr>
							<td class="key"><?php echo JText::_('SHOWCASE_GENERAL_ORDER');?></td>
							<td><?php echo $this->lists['ordering']; ?></td>
						</tr>
					</table>
				</fieldset>
				<fieldset>
					<legend><?php echo JText::_('SHOWCASE_GENERAL_IMAGES_LOADING'); ?></legend>
					<table class="admintable" width="100%">
						<tbody>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWCASE_GENERAL_TITLE_IMAGES_PRELOADING_NUMBER');?>::<?php echo JText::_('SHOWCASE_GENERAL_DES_IMAGES_PRELOADING_NUMBER'); ?>"><?php echo JText::_('SHOWCASE_GENERAL_TITLE_IMAGES_PRELOADING_NUMBER'); ?></span></td>
								<td><input type="text" size="5" name="general_number_images_preload" value="<?php echo $this->generalData['generalImageLoad']; ?>" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWCASE_GENERAL_TITLE_GENERAL_IMAGES_LOADING_ORDER');?>::<?php echo JText::_('SHOWCASE_GENERAL_DES_GENERAL_IMAGES_LOADING_ORDER'); ?>"><?php echo JText::_('SHOWCASE_GENERAL_TITLE_GENERAL_IMAGES_LOADING_ORDER'); ?></span></td>
								<td><?php echo $this->lists['generalImagesOrder'];?></td>
							</tr>
						</tbody>
					</table>
				</fieldset>
			</td>
			<td valign="top">
				<fieldset>
					<legend><?php echo JText::_('SHOWCASE_GENERAL_APPEARANCE'); ?></legend>
					<table class="admintable" width="100%" >
						<tbody>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWCASE_GENERAL_OVERALL_WIDTH');?>::<?php echo JText::_('SHOWCASE_GENERAL_OVERALL_WIDTH_DESC'); ?>"><?php echo JText::_('SHOWCASE_GENERAL_OVERALL_WIDTH'); ?></span></td>
								<td><input type="text" size="5" name="general_overall_width" value="<?php echo (int) $this->generalData['generalWidth']; ?>" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" />&nbsp;<?php echo $this->lists['overallWidthDimension'];?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWCASE_GENERAL_OVERALL_HEIGHT');?>::<?php echo JText::_('SHOWCASE_GENERAL_OVERALL_HEIGHT_DESC'); ?>"><?php echo JText::_('SHOWCASE_GENERAL_OVERALL_HEIGHT'); ?></span></td>
								<td><input type="text" size="5" name="general_overall_height" value="<?php echo $this->generalData['generalHeight']; ?>" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" />&nbsp;<?php echo JText::_('px'); ?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWCASE_GENERAL_TITLE_ROUND_CORNER');?>::<?php echo JText::_('SHOWCASE_GENERAL_DES_ROUND_CORNER'); ?>"><?php echo JText::_('SHOWCASE_GENERAL_TITLE_ROUND_CORNER'); ?></span></td>
								<td><input type="text" size="5" name="general_round_corner_radius" value="<?php echo $this->generalData['generalCornerRadius']; ?>" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" />&nbsp;<?php echo JText::_('px'); ?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWCASE_GENERAL_TITLE_BORDER_STOKE');?>::<?php echo JText::_('SHOWCASE_GENERAL_DES_BORDER_STOKE'); ?>"><?php echo JText::_('SHOWCASE_GENERAL_TITLE_BORDER_STOKE'); ?></span></td>
								<td><input type="text" size="5" name="general_border_stroke" value="<?php echo $this->generalData['generalBorderStroke']; ?>" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" />&nbsp;<?php echo JText::_('px'); ?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWCASE_GENERAL_TITLE_OUTSITE_BACKGROUND_COLOR');?>::<?php echo JText::_('SHOWCASE_GENERAL_DES_OUTSITE_BACKGROUND_COLOR'); ?>"><?php echo JText::_('SHOWCASE_GENERAL_TITLE_OUTSITE_BACKGROUND_COLOR');?></span></td>
								<td class="showcase-input-field"><input type="text" size="10" readonly="readonly" name="background_color" id="background_color" value="<?php echo $this->generalData['generalBgColor']; ?>" />
									<a href="" id="general_background_color"><span id="span_background_color" class="jsnis-icon-view-color" style="background:<?php echo $this->generalData['generalBgColor']; ?>"></span><span class="color-selection"><?php echo JText::_('SHOWCASE_GENERAL_SELECT_COLOR')?></span></a></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWCASE_GENERAL_TITLE_BORDER_COLOR');?>::<?php echo JText::_('SHOWCASE_GENERAL_DES_BORDER_COLOR'); ?>"><?php echo JText::_('SHOWCASE_GENERAL_TITLE_BORDER_COLOR'); ?></span></td>
								<td class="showcase-input-field"><input type="text" size="10" id="general_border_color" readonly="readonly" name="general_border_color" value="<?php echo $this->generalData['generalBorderColor']; ?>" />
									<a href="" id="link_general_border_color"><span id="span_general_border_color" class="jsnis-icon-view-color" style="background:<?php echo $this->generalData['generalBorderColor']; ?>"></span><span class="color-selection"><?php echo JText::_('SHOWCASE_GENERAL_SELECT_COLOR')?></span></a></td>
							</tr>
						</tbody>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
	<div id="jsn-showcase-theme-wrapper">
		<?php
		$objShowcaseTheme 	= JSNISFactory::getObj('classes.jsn_is_showcasetheme');
		$objShowcase		= JSNISFactory::getObj('classes.jsn_is_showcase');
		$themes 			= $objShowcaseTheme->listThemes(false);
		$countTheme 		= count($themes);
		$theme				= JRequest::getVar('theme');

		if ($this->items->theme_id && $objShowcaseTheme->checkThemeExist($this->items->theme_name))
		{
			$objShowcaseTheme->loadThemeByName($this->items->theme_name);
		}
		else
		{
			if ($countTheme == 1)
			{
				$objShowcaseTheme->loadThemeByName(@$themes[0]['element']);
			}
			elseif ($countTheme && $theme != '')
			{
				$objShowcaseTheme->loadThemeByName($theme);
			}
			else
			{
				echo $this->loadTemplate('themes');
			}
		}
	?>
	</div>
	<input type="hidden" name="redirectLinkTheme" value="" />
	<input type="hidden" name="option" value="com_imageshow" />
	<input type="hidden" name="controller" value="showcase" />
	<input type="hidden" name="cid[]" value="<?php echo (int) $this->items->showcase_id; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>
<?php include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'footer.php'); ?>
