<?php
defined('_JEXEC') or die( 'Restricted access' );
	$task 		= JRequest::getWord('task','','post');
	$showlistID = JRequest::getVar('cid');
	$showlistID = $showlistID[0];
	$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
	$baseURL 	= $objJSNUtils->overrideURL();
	$url 		= $baseURL.'components/com_imageshow/assets/swf';
	$objJSNFlex = JSNISFactory::getObj('classes.jsn_is_flex');
	$token 		= $objJSNFlex->getToken();
	$user 		= JFactory::getUser();
?>
<script type="text/javascript" src="<?php echo dirname($baseURL); ?>/components/com_imageshow/assets/js/swfobject.js"></script>
<script type="text/javascript">
	var userID 				= '<?php echo $user->id; ?>';
	var expandCookie 		= JSNISUtils.getCookie('jsn-flex-cookie-expand-' + userID);
	var divideWidthCookie  	= JSNISUtils.getCookie('jsn-flex-cookie-devidewidth-' + userID);
	var albumSize		  	= JSNISUtils.getCookie('jsn-flex-cookie-albumviewmode-' + userID);
	var viewMode		  	= JSNISUtils.getCookie('jsn-flex-cookie-showlistviewmode-' + userID);
	var url = '<?php echo $url;?>';
	var flashvars = {
						baseurl:'<?php echo $baseURL."index.php"; ?>',
						siteurl:'<?php echo dirname($baseURL); ?>',
						showlistid:'<?php echo (int)$showlistID;?>',
						option:'com_imageshow',
						controller:'flex',
						token:'<?php echo $token; ?>',
						expand :  (expandCookie != undefined ) ? expandCookie : true,
						dividewidth: (divideWidthCookie != undefined) ? divideWidthCookie : '',
						albumviewmode: (albumSize != undefined) ? albumSize : 0,
						showlistviewmode: (viewMode != undefined) ? viewMode : 0
					};
	var params = {bgcolor:'#F4F4F4', allowFullScreen:'true', allowScriptAccess:'sameDomain', quality:'high', wmode:'window'};

	swfobject.embedSWF(
			url+"/ImageSelector.swf",
			"flash",
			"100%", "100%", "9.0.0",
			url+"/playerProductInstall.swf",
			 flashvars, params);

	window.addEvent('domready', function()
	{
		JSNISImageShow.loadCookieSettingFlex();
	});
</script>
<div id="jsnis-showlist-flex" style="height:<?php echo JRequest::getVar('jsnis-height-flex-'.$user->id, 550, 'COOKIE'); ?>px;">
	<div id="flash">
		<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
	</div>
</div>
<div id="jsnis-flex-control-button">
	<a class="jsnis-panel-increase" onclick="JSNISImageShow.resizeFlex('increase', <?php echo $user->id; ?>); return false;"><?php echo JText::_('SHOWLIST_FLEX_INCREASE_SIZE');?></a>&nbsp;&nbsp;&iota;&nbsp;
	<a class="jsnis-panel-decrease" onclick="JSNISImageShow.resizeFlex('reduce', <?php echo $user->id; ?>); return false;"><?php echo JText::_('SHOWLIST_FLEX_DECREASE_SIZE');?></a>
</div>