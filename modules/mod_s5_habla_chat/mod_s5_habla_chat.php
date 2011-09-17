<?php

/**
* @package		Joomla
* @copyright	Copyright (C) 2009 - current - Shape 5 LLC.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

$hablaid		= $params->get( 'hablaid', '' );
$pretext_s5_hc		= $params->get( 'pretext', "" );
$posttext_s5_hc		= $params->get( 'posttext', "" );

$LiveSite 	= JURI::base();

?>

<?php if ($pretext_s5_hc != "") { ?>
<?php echo $pretext_s5_hc ?>
<br /><br />
<?php } ?>

<a onclick="s5_chatopen('habla_topbar_div');" style="cursor:pointer;">
	<?php echo $posttext_s5_hc ?>
</a>

<?php if ($posttext_s5_hc != "") { ?>
<br />

<?php } ?>

	<!-- Begin Olark Chat -->
	<script type="text/javascript">
	(function(){document.write(unescape('%3Cscript src=%27' + (document.location.protocol == 'https:' ? "https:" : "http:") + '//static.olark.com/js/wc.js%27 type=%27text/javascript%27%3E%3C/script%3E'));})();
	</script>
	<div id="olark-data">
		<a class="olark-key" id="<?php echo $hablaid ?>" title="Powered by Olark" href="http://olark.com/about" rel="nofollow">
		Powered by Olark
		</a>
	</div>
	<script type="text/javascript"> wc_init();</script>
	<!-- /End Olark Chat -->
	
	
	


<script type="text/javascript">
function s5_chatopen(objID) {
	var target=document.getElementById(objID);
	if(document.dispatchEvent) { // W3C
		var oEvent = document.createEvent( "MouseEvents" );
		oEvent.initMouseEvent("click", true, true,window, 1, 1, 1, 1, 1, false, false, false, false, 0, target);
		target.dispatchEvent( oEvent );
		}
	else if(document.fireEvent) { // IE
		target.fireEvent("onclick");
		}    
	}



</script>

	

	
	
	
	
	
	
	
	
	
	
	
	