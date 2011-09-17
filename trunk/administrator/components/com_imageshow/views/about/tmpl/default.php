<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 7498 2011-07-27 04:33:17Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JToolBarHelper::title( JText::_('JSN_IMAGESHOW').': '.JText::_( 'ABOUT_ABOUT' ), 'about' );
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$edition 	= @$this->infoXmlDetail['edition'];
$objJSNMsg 	= JSNISFactory::getObj('classes.jsn_is_displaymessage');
$explodeEdition =  explode(' ', $edition);
echo $objJSNMsg->displayMessage('ABOUT');
?>
<div id="jsn-imageshow-about">
	<div id="jsn-imageshow-intro">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="10" valign="top"><div class="jsn-product-thumbnail"><?php echo JHTML::_('image.administrator', 'components/com_imageshow/assets/images/product-thumbnail.png',''); ?></div></td>
				<td align="left">
					<h2><a href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow.html"><?php echo JText::_('JSN') .' '. @$this->infoXmlDetail['realName'].'&nbsp;'.strtoupper($edition); ?></a></h2>
					<?php if(strtolower($edition) == 'pro standard')
					{
					?>
					<p class="jsn-message-upgrade"><?php echo JText::_('ABOUT_UPGRADE_TO_UNLIMITED'); ?></p>
					<?php
					}
					?>
					<hr />

					<dl>
						<dt><?php echo JText::_('ABOUT_VERSION'); ?>:</dt><dd><strong class="jsn-current-version"><?php echo @$this->componentData->version; ?></strong>&nbsp;-&nbsp;<a href="javascript:void(0);" id="jsn-check-version" class="link-action"><strong><?php echo JText::_('ABOUT_CHECK_FOR_UPDATE'); ?></strong></a><span id="jsn-check-version-result"></span></dd>
						<dt><?php echo JText::_('ABOUT_AUTHOR'); ?>:</dt><dd><a href="http://<?php echo $this->infoXmlDetail['website']; ?>"><?php echo $this->infoXmlDetail['author']; ?></a></dd>
						<dt><?php echo JText::_('ABOUT_COPYRIGHT'); ?>:</dt><dd><?php echo $this->infoXmlDetail['copyright']; ?></dd>
					</dl>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<div class="content-center">
						<ul class="list-horizontal">
							<li>
								<?php if (strtolower($explodeEdition[0]) == 'free') { ?>
								<a href="http://www.joomlashine.com/joomla-extensions/buy-jsn-imageshow.html" target="_blank" class="link-button">
									<span class="icon-upgrade-to-pro"><?php echo JText::_('ABOUT_UPGRADE_TO_PRO'); ?></span>
								</a>
								<?php } else {?>
								<a href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow-on-jed.html" target="_blank" class="link-button">
									<span class="icon-vote-jed"><?php echo JText::_('ABOUT_VOTE_FOR_THIS_PRODUCT_ON_JED'); ?></span>
								</a>
								<?php } ?>
							</li>
							<li>
								<a href="http://twitter.com/joomlashine" target="_blank" class="link-button">
									<span class="icon-twitter"><?php echo JText::_('ABOUT_FOLLOW_US_ON_TWITTER'); ?></span>
								</a>
							</li>
						</ul>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<?php
		echo JText::_('ABOUT_COMPONENT_DESCRIPTION_'.$explodeEdition[0]);
	?>
</div>
