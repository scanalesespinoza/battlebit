<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form_showlist.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
$objJSNUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
JHTML::_('behavior.tooltip');
JToolBarHelper::title( JText::_('JSN_IMAGESHOW').': '.JText::_('SHOWLIST_SHOWLIST_SETTINGS'), 'showlist-settings' );
JToolBarHelper::apply('apply');
JToolBarHelper::save('save');
JToolBarHelper::cancel('cancel','close');
JToolBarHelper::divider();
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$showListID = (int) $this->items->showlist_id;
$task = JRequest::getVar('task');
$user = JFactory::getUser();
if ($task == 'edit')
{
	echo "<div id=\"jsn-showlist-toolbar-css\"><style>#toolbar-save,#toolbar-apply{display:none;}</style></div>";
}

?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function(pressbutton)
	{
		var form 		= document.adminForm;
		var link  		= form.showlist_link.value;
		var flexElement = document.getElementById('flash');
		var task 		= '<?php echo $task; ?>';

		if (pressbutton == 'cancel')
		{
			submitform( pressbutton );
			return;
		}

		if (form.showlist_title.value == "")
		{
			alert( "<?php echo JText::_('SHOWLIST_SHOWLIST_MUST_HAVE_A_TITLE', true); ?>");
			return;
		}
		else
		{
			if(task != 'add')
			{
				try
				{
					flexElement.saveFlex(pressbutton);
				}
				catch(e){}
			}
			else
			{
				submitform( pressbutton );
			}
		}
	}

	function selectArticle_auth_article_id(id, title, catid)
	{
		document.id("aid_name").value = title;
		document.id("aid_id").value = id;
		SqueezeBox.close();
	}

	function selectArticle_seo_article_id(id, title, catid, object)
	{
		document.id("seo_article_name").value = title;
		document.id("seo_article_id").value = id;
		SqueezeBox.close();
	}

	function selectArticle_alter_article_id(id, title, catid, object)
	{
		document.id("id_name").value = title;
		document.id("id_id").value = id;
		SqueezeBox.close();
	}

	function selectModule_id(id, title, seo)
	{
		if(seo == true)
		{
			document.id("seo_module_id").value = id;
			document.id("seo_module_title").value = title;
		}
		else
		{
			document.id("alter_module_title").value = title;
			document.id("alter_module_id").value = id;
		}
		SqueezeBox.close();
	}

	function jInsertFieldValue(value,id)
	{
		var old_id = document.getElementById(id).value;
		if (old_id != id)
		{
			document.getElementById(id).value = value;
		}
	}

	document.addEvent('domready', function()
	{
		JSNISImageShow.simpleSlide('jsnis-showlist-detail-heading',
				'jsnis-showlist-detail-slide',
				'jsnis-showlist-detail-arrow',
				'jsnis-showlist-detail-title',
				'jsnis-element-heading-arrow-collapse',
				'jsnis-element-heading-title');

		$('jsnis-showlist-detail-heading').addEvent('click', function()
		{
			JSNISImageShow.setCookieHeadingTitleStatus('jsnis-heading-title-showlist-<?php echo $user->id . (int) $this->items->showlist_id; ?>');
		});

		var showlistHeadingTitleStatus = JSNISUtils.getCookie('jsnis-heading-title-showlist-<?php echo $user->id . (int) $this->items->showlist_id; ?>');

		if (showlistHeadingTitleStatus == 'close')
		{
			$('jsnis-showlist-detail-heading').fireEvent('click');
			JSNISUtils.setCookie('jsnis-heading-title-showlist-<?php echo $user->id . (int) $this->items->showlist_id; ?>', 'close', 15);
		}
	});
</script>
<?php
$objJSNMsg = JSNISFactory::getObj('classes.jsn_is_displaymessage');
echo $objJSNMsg->displayMessage('SHOWLISTS');
?>
<form name='adminForm' id='adminForm' action="index.php?option=com_imageshow&controller=showlist" method="post">
	<div id="jsnis-showlist-detail-heading" class="jsnis-dgrey-heading jsnis-dgrey-heading-style">
		<h3 class="jsnis-element-heading">
			<?php echo JText::_('SHOWLIST_TITLE_SHOWLIST_DETAILS');?>
			<span id="jsnis-showlist-detail-title" class="jsnis-element-heading-title"><?php echo ($this->items->showlist_title != '') ? ': '.htmlspecialchars($this->items->showlist_title) : ''; ?></span>
			<span id="jsnis-showlist-detail-arrow" class="jsnis-element-heading-arrow"></span>
		</h3>
	</div>
	<table id="jsnis-showlist-detail-slide" class="jsnis-showlist-settings" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td valign="top" style="width: 50%;"><fieldset>
					<legend> <?php echo JText::_('SHOWLIST_GENERAL');?> </legend>
					<table class="admintable" border="0" style="width:100%;">
						<tbody>
							<?php
					if($showListID != 0){
				?>
							<tr>
								<td class="key"><?php echo JText::_('ID');?></td>
								<td><?php echo $showListID; ?></td>
							</tr>
							<?php
					}
				?>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_TITLE_SHOWLIST');?>::<?php echo JText::_('SHOWLIST_DES_SHOWLIST'); ?>"><?php echo JText::_('SHOWLIST_TITLE_SHOWLIST');?></span></td>
								<td><input style="width:96%;" type="text" value="<?php echo htmlspecialchars($this->items->showlist_title);?>" name="showlist_title"/>
									<font color="Red"> *</font></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_TITLE_PUBLISHED');?>::<?php echo JText::_('SHOWLIST_DES_PUBLISHED'); ?>"><?php echo JText::_('SHOWLIST_TITLE_PUBLISHED');?></span></td>
								<td><?php echo $this->lists['published']; ?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_TITLE_ORDER');?>::<?php echo JText::_('SHOWLIST_DES_ORDER'); ?>"><?php echo JText::_('SHOWLIST_TITLE_ORDER');?></span></td>
								<td><?php echo $this->lists['ordering']; ?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_TITLE_HITS');?>::<?php echo JText::_('SHOWLIST_DES_HITS'); ?>"><?php echo JText::_('SHOWLIST_HITS');?></span></td>
								<td><input size="15" type="text" name="hits" value="<?php echo ($this->items->hits!='')?$this->items->hits:0;?>" /></td>
							</tr>
							<tr>
								<td valign="top" class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_TITLE_DESCRIPTION');?>::<?php echo JText::_('SHOWLIST_DES_DESCRIPTION'); ?>"><?php echo JText::_('SHOWLIST_TITLE_DESCRIPTION');?></span></td>
								<td><textarea style="width:100%; height:100px;" name="description"><?php echo $this->items->description; ?></textarea></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_TITLE_LINK');?>::<?php echo JText::_('SHOWLIST_DES_LINK'); ?>"><?php echo JText::_('SHOWLIST_LINK');?></span></td>
								<td><input style="width: 100%;" type="text" name="showlist_link" value="<?php echo htmlspecialchars($objJSNUtils->decodeUrl($this->items->showlist_link)); ?>" /></td>
							</tr>
						</tbody>
					</table>
				</fieldset></td>
			<td valign="top">
				<fieldset>
					<legend><?php echo JText::_('SHOWLIST_IMAGES_DETAILS_OVERRIDE'); ?></legend>
					<table class="admintable" border="0" style="width: 100%;">
						<tbody>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_OVERRIDE_TITLE');?>::<?php echo JText::_('SHOWLIST_OVERRIDE_TITLE_DESC'); ?>"><?php echo JText::_('SHOWLIST_OVERRIDE_TITLE');?></span></td>
								<td><?php echo $this->lists['overrideTitle']; ?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_OVERRIDE_DESCRIPTION');?>::<?php echo JText::_('SHOWLIST_OVERRIDE_DESCRIPTION_DESC'); ?>"><?php echo JText::_('SHOWLIST_OVERRIDE_DESCRIPTION');?></span></td>
								<td><?php echo $this->lists['overrideDesc']; ?></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_OVERRIDE_LINK');?>::<?php echo JText::_('SHOWLIST_OVERRIDE_LINK_DESC'); ?>"><?php echo JText::_('SHOWLIST_OVERRIDE_LINK');?></span></td>
								<td><?php echo $this->lists['overrideLink']; ?></td>
							</tr>
						</tbody>
					</table>
				</fieldset>
				<fieldset>
					<legend><?php echo JText::_('SHOWLIST_ACCESS_PERMISSION'); ?></legend>
					<table class="admintable" border="0" style="width: 100%;">
						<tbody>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_TITLE_ACCESS_LEVEL');?>::<?php echo JText::_('SHOWLIST_DES_ACCESS_LEVEL'); ?>"><?php echo JText::_('SHOWLIST_TITLE_ACCESS_LEVEL');?></span></td>
								<td><select name="access" class="inputbox">
										<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->items->access);?>
									</select></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_TITLE_AUTHORIZATION_MESSAGE');?>::<?php echo JText::_('SHOWLIST_DES_AUTHORIZATION_MESSAGE'); ?>"><?php echo JText::_('SHOWLIST_TITLE_AUTHORIZATION_MESSAGE');?></span></td>
								<td class="paramlist_value"><?php echo $this->lists['authorizationCombo']; ?>
									<div style="<?php echo ($this->items->authorization_status == 1)?'display:"";':'display:none;'; ?>" id="wrap-aut-article">
										<input class="showlist-input jsnis-readonly" type="text" id="aid_name" value="<?php echo @$this->items->aut_article_title;?>" readonly="readonly" />
										<div class="button2-left">
											<div class="blank"> <a class="modal" rel="{handler: 'iframe', size: {x: 651, y: 375}}" href="index.php?option=com_content&view=articles&layout=modal&tmpl=component&function=selectArticle_auth_article_id" title="Select Content"><?php echo JText::_('SHOWLIST_SELECT');?></a>
												<input type="hidden" id="aid_id" name="alter_autid" value="<?php echo $this->items->alter_autid;?>" />
											</div>
										</div>
									</div></td>
							</tr>
						</tbody>
					</table>
				</fieldset>
				<fieldset>
					<legend><?php echo JText::_('SHOWLIST_ALTERNATIVE_AND_SEO_CONTENT'); ?></legend>
					<table class="admintable" border="0" style="width:100%;">
						<tbody>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_TITLE_ALTERNATIVE_CONTENT');?>::<?php echo JText::_('SHOWLIST_DES_ALTERNATIVE_CONTENT'); ?>"><?php echo JText::_('SHOWLIST_TITLE_ALTERNATIVE_CONTENT');?></span></td>
								<td class="paramlist_value"><?php echo $this->lists['alternativeContentCombo']; ?>
									<div style="<?php echo ($this->items->alternative_status == 2)?'':'display: none;'; ?>" id="wrap-btt-article">
										<input class="showlist-input jsnis-readonly" type="text" id="id_name" value="<?php echo @$this->items->article_title;?>" readonly="readonly"/>
										<div class="button2-left">
											<div class="blank"> <a class="modal" rel="{handler: 'iframe', size: {x: 651, y: 375}}" href="index.php?option=com_content&view=articles&layout=modal&tmpl=component&function=selectArticle_alter_article_id" title="Select Content"><?php echo JText::_('SHOWLIST_SELECT');?></a>
												<input type="hidden" id="id_id" name="alter_id" value="<?php echo $this->items->alter_id;?>" />
											</div>
										</div>
									</div>
									<div style="<?php echo ($this->items->alternative_status == 1)?'':'display:none;'; ?>" id="wrap-btt-module">
										<input class="showlist-input jsnis-readonly" type="text" id="alter_module_title" value="<?php echo @$this->items->alter_module_title;?>" readonly="readonly"/>
										<div class="button2-left">
											<div class="blank"> <a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 600}}" href="index.php?option=com_imageshow&controller=modules&seo=false&tmpl=component" title="Select Modules"><?php echo JText::_('SHOWLIST_SELECT');?></a>
												<input type="hidden" id="alter_module_id" name="alter_module_id" value="<?php echo $this->items->alter_module_id;?>" />
											</div>
										</div>
									</div>
									<div style="<?php echo ($this->items->alternative_status == 3)?'':'display:none;'; ?>" id="wrap-btt-image">
										<input class="showlist-input jsnis-readonly" type="text" id="alter_image_path" name="alter_image_path" value="<?php echo @$this->items->alter_image_path;?>" readonly="readonly"/>
										<div class="button2-left">
											<div class="blank"> <a class="modal" rel="{handler: 'iframe', size: {x: 590, y: 420}}" href="index.php?option=com_imageshow&controller=media&act=showlist&tmpl=component&e_name=text" title="Select Modules"><?php echo JText::_('SHOWLIST_SELECT');?></a> </div>
										</div>
									</div></td>
							</tr>
							<tr>
								<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_TITLE_SEO_CONTENT');?>::<?php echo JText::_('SHOWLIST_DES_SEO_CONTENT'); ?>"><?php echo JText::_('SHOWLIST_TITLE_SEO_CONTENT');?></span></td>
								<td class="paramlist_value"><?php echo $this->lists['seoContent']; ?>
									<div style="<?php echo ($this->items->seo_status == 1)?'':'display: none;'; ?>" id="wrap-seo-article">
										<input class="showlist-input jsnis-readonly" type="text" id="seo_article_name" value="<?php echo @$this->items->seo_article_title;?>" readonly="readonly"/>
										<div class="button2-left">
											<div class="blank"> <a class="modal" rel="{handler: 'iframe', size: {x: 651, y: 375}}" href="index.php?option=com_content&view=articles&layout=modal&tmpl=component&function=selectArticle_seo_article_id" title="Select Content"><?php echo JText::_('SHOWLIST_SELECT');?></a>
												<input type="hidden" id="seo_article_id" name="seo_article_id" value="<?php echo $this->items->seo_article_id;?>" />
											</div>
										</div>
									</div>
									<div style="<?php echo ($this->items->seo_status == 2)?'':'display:none;'; ?>" id="wrap-seo-module">
										<input class="showlist-input jsnis-readonly" type="text" id="seo_module_title" value="<?php echo @$this->items->seo_module_title;?>" readonly="readonly"/>
										<div class="button2-left" >
											<div class="blank"> <a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 600}}" href="index.php?option=com_imageshow&controller=modules&seo=true&tmpl=component" title="Select Modules"><?php echo JText::_('SHOWLIST_SELECT');?></a>
												<input type="hidden" id="seo_module_id" name="seo_module_id" value="<?php echo $this->items->seo_module_id;?>" />
											</div>
										</div>
									</div></td>
							</tr>
						</tbody>
					</table>
				</fieldset></td>
	</table>
	<input type="hidden" name="cid[]" value="<?php echo (int) $this->items->showlist_id;?>" />
	<input type="hidden" name="option" value="com_imageshow" />
	<input type="hidden" name="controller" value="showlist" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
