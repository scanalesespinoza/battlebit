<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_profile_flickr.php 7329 2011-07-15 10:35:35Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JHTML::_('behavior.tooltip');
$configurationID = JRequest::getInt('configuration_id');
?>
<script language="javascript">
	JSNISImageShow.submitForm = function()
	{
		var form = document.adminForm;
		$('submit-form').disabled=true;
		$('cancel').disabled=true;
		form.submit();
		window.top.setTimeout('SqueezeBox.close(); window.top.location.reload(true)', 1000);
	}

	function onSubmit()
	{
		var form 			= document.adminForm;
		var params       	= {};
		params.configTitle 	= form.configuration_title.value;
		params.apiKey 	  	= form.flickr_api_key.value;
		params.secretKey   	= form.flickr_secret_key.value;
		params.screenName  	= form.flickr_username.value;

		if(params.configTitle == '' || params.apiKey  == '' || params.secretKey == '' || params.screenName == '' )
		{
			alert( "<?php echo JText::_('MAINTENANCE_SOURCE_REQUIRED_FIELD_PROFILE_CANNOT_BE_LEFT_BLANK', true); ?>");
			return;
		}
		else
		{
			var url  			= 'index.php?option=com_imageshow&controller=maintenance&task=checkEditProfileExist&configuration_title='+params.configTitle+'&configuration_id='+<?php echo $configurationID; ?>;
			params.validateURL 	= 'index.php?option=com_imageshow&controller=maintenance&task=validateProfile&flickr_api_key='+ params.apiKey +'&flickr_secret_key=' + params.secretKey + '&flickr_screen_name=' + params.screenName;
			JSNISImageShow.checkEditProfile(url, params);
		}
	}
</script>
<!--[if IE 7]>
	<link href="<?php echo JURI::base();?>components/com_imageshow/assets/css/fixie7.css" rel="stylesheet" type="text/css" />
<![endif]-->
<div id="jsnis-image-source-profile-details">
<form name='adminForm' id='adminForm' action="index.php" method="post" onsubmit="return false;">
<table cellspacing="0" width="100%" border="0">
	<tbody>
		<tr>
			<td><h3 class="jsnis-element-heading"><?php echo JText::_('MAINTENANCE_SOURCE_IMAGE_SOURCE_PROFILE_SETTINGS'); ?></h3></td>
		</tr>
		<tr>
			<td>
				<table class="admintable">
					<tr>
						<td width="25%" class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('MAINTENANCE_SOURCE_TITLE_PROFILE_TITLE');?>::<?php echo JText::_('MAINTENANCE_SOURCE_DES_PROFILE_TITLE'); ?>"><?php echo JText::_('MAINTENANCE_SOURCE_TITLE_PROFILE_TITLE');?></span></td>
						<td><input type="text" name ="configuration_title" id ="configuration_title" value = "<?php echo @$this->profileinfo->configuration_title;?>" /></td>
					</tr>
					<tr>
						<td width="25%" class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('MAINTENANCE_SOURCE_TITLE_FLICKR_API_KEY');?>::<?php echo JText::_('MAINTENANCE_SOURCE_DES_FLICKR_API_KEY'); ?>"><?php echo JText::_('MAINTENANCE_SOURCE_TITLE_FLICKR_API_KEY');?></span></td>
						<td><input type="text" name ="flickr_api_key" id = "" value = "<?php echo @$this->profileinfo->flickr_api_key;?>" <?php echo ($this->countShowlist) ? 'disabled="disabled" class="jsnis-readonly"' : ''; ?>/></td>
					</tr>
					<tr>
						<td width="25%" class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('MAINTENANCE_SOURCE_TITLE_FLICKR_API_SECRET_KEY');?>::<?php echo JText::_('MAINTENANCE_SOURCE_DES_FLICKR_API_SECRET_KEY'); ?>"><?php echo JText::_('MAINTENANCE_SOURCE_TITLE_FLICKR_API_SECRET_KEY');?></span></td>
						<td><input type="text" name ="flickr_secret_key" id = "" value = "<?php echo @$this->profileinfo->flickr_secret_key;?>" <?php echo ($this->countShowlist) ? 'disabled="disabled" class="jsnis-readonly"' : ''; ?>/></td>
					</tr>
					<tr>
						<td width="25%" class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('MAINTENANCE_SOURCE_TITLE_FLICKR_SCREEN_NAME');?>::<?php echo JText::_('MAINTENANCE_SOURCE_DES_FLICKR_SCREEN_NAME'); ?>"><?php echo JText::_('MAINTENANCE_SOURCE_TITLE_FLICKR_SCREEN_NAME');?></span></td>
						<td><input type="text" name ="flickr_username" id = "" value = "<?php echo @$this->profileinfo->flickr_username;?>" <?php echo ($this->countShowlist) ? 'disabled="disabled" class="jsnis-readonly"' : ''; ?>/></td>
					</tr>
					<tr>
						<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('MAINTENANCE_SOURCE_TITLE_FLICKR_IMAGE_SIZE');?>::<?php echo JText::_('MAINTENANCE_SOURCE_DES_FLICKR_IMAGE_SIZE'); ?>"><?php echo JText::_('MAINTENANCE_SOURCE_TITLE_FLICKR_IMAGE_SIZE');?></span></td>
						<td><?php echo $this->lists['fickrImageSize']; ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="center"><div class="button">
					<button type="button" onclick="return onSubmit();" id="submit-form" class="button jsnis-button"  title="<?php echo JText::_('OK');?>"><?php echo JText::_('OK');?></button>
					<button  type="button" class="button jsnis-button" id="cancel" title="<?php echo JText::_('CANCEL');?>" onclick="window.top.setTimeout('SqueezeBox.close()', 200);"><?php echo JText::_('CANCEL');?></button>
				</div></td>
		</tr>
	</tbody>
</table>

<input type="hidden" name="option" value="com_imageshow" />
<input type="hidden" name="controller" value="maintenance" />
<input type="hidden" name="task" value="saveprofile" id="task" />
<input type="hidden" name="configuration_id" value="<?php echo $configurationID; ?>" id="configuration_id" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
