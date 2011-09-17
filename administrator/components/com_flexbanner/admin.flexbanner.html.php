<?php
/**
* @copyright Copyright (C) 2009 - 2011 inch communications ltd. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class HTML_FlexBanner {

	public static function showBanners( &$rows, &$pageNav, $option ) {
		?>
		<form action="index.php" method="post" name="adminForm">

		<table class="adminlist">
		<tr>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th align="left"><?php echo JText::_( 'ADMIN_FLEXBANNER_BANNERALT' ); ?></th>
			<th align="left" width="110" nowrap><?php echo JText::_('ADMIN_FLEXBANNER_BANNERIMAGE'); ?></th>
			<th width="70" nowrap><?php echo JText::_('ADMIN_FLEXBANNER_BANNERPUB'); ?></th>
			<th width="60" nowrap><?php echo JText::_('ADMIN_FLEXBANNER_BANNERFIN'); ?></th>
			<th width="100" nowrap><?php echo JText::_('ADMIN_FLEXBANNER_BANNERDAILYIMP'); ?></th>
			<th width="110" nowrap><?php echo JText::_('ADMIN_FLEXBANNER_BANNERIMPMADE'); ?></th>
			<th width="40"><?php echo JText::_('ADMIN_FLEXBANNER_BANNERCLICKS'); ?></th>
			<th width="50" nowrap><?php echo JText::_('ADMIN_FLEXBANNER_BANNERPERCENTCLICKS'); ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];

			$row->id 	= $row->bannerid;
			$link 		= 'index.php?option=com_flexbanner&task=editBanner&hidemainmenu=1&bannerid='. $row->id;

			$impleft 	= $row->maximpressions - $row->impressions;
			if( $impleft < 0 ) {
				$impleft 	= "unlimited";
			}
			
			$clickleft 	= $row->maxclicks - $row->clicks;
			if( $clickleft < 0 ) {
				$clickleft 	= "unlimited";
			}

			if ( $row->impressions != 0 ) {
				$percentClicks = substr(100 * $row->clicks/$row->impressions, 0, 5);
			} else {
				$percentClicks = 0;
			}

			$task 	= $row->published ? 'unpublish' : 'publish';
			$img 	= $row->published ? 'publish_g.png' : 'publish_x.png';
			$alt 	= $row->published ? 'Published' : 'Unpublished';

       		$ftask 	= $row->finished ? 'start' : 'finish';
			$fimg 	= $row->finished ? 'publish_g.png' : 'publish_x.png';
			$falt 	= $row->finished ? 'Finished' : 'Unfinished';

			$checked 	= JHTML::_('grid.checkedOut',$row, $i );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center">
				<?php echo $checked; ?>
				</td>
				<td align="left">
				<?php
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					echo $row->imagealt;
				} else {
					?>
					<a href="<?php echo $link; ?>" title="Edit Banner">
					<?php echo $row->imagealt; ?>
					</a>
					<?php
				}
				?>
				</td>
				<td><?php if (strpos($row->imageurl, '.swf')==0){ ?><img src="<?php echo JURI::base(). "../images/banners/" . $row->imageurl; ?>" alt="<?php echo $row->imagealt; ?>" width="110"/> <?php } ?></td>
				<td align="center">
				<img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="<?php echo $alt; ?>" />
				</td>
				<td align="center">
				<img src="images/<?php echo $fimg;?>" width="12" height="12" border="0" alt="<?php echo $falt; ?>" />
				</td>
				<td align="center">
				<?php echo $row->dailyimpressions;?>
				</td>
				<td align="center">
				<?php echo $row->impressions;?>
				</td>
				<td align="center">
				<?php echo $row->clicks;?>
				</td>
				<td align="center">
				<?php echo $percentClicks;?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
	<div style="width:70%;float:right">
			<div style="float:right">
				<br /><b><?php echo JText::_('ADMIN_FLEXBANNER_DONATE'); ?></b>
             </div>
			 <div style="float:right">
			 	<br />
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="W43XUP6SDZW9N">
				<input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
				<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
				</form>
			</div>
	</div>
		<?php
	}
	
	public static function bannerForm( &$_row, &$lists, $_option ) {
    	$fb_language = 0;
		if (file_exists(JPATH_SITE . '/administrator/components/com_joomfish/joomfish.php')) {
		$fb_language = 1; }
            
		JFilterOutput::objectHTMLSafe( $_row, ENT_QUOTES, 'custombannercode' );
		JHTML::_('behavior.calendar');
		?>
		<link rel="stylesheet" href="<?php echo JURI::base()?>components/com_flexbanner/flexbanner.css" type="text/css" />
		<script language="javascript" type="text/javascript">
		<!--
		function changeDisplayImage() {
			if (document.adminForm.imageurl.value !='') {
				document.adminForm.imagelib.src='../images/banners/' + document.adminForm.imageurl.value;
			} else {
				document.adminForm.imagelib.src='images/blank.png';
			}
		}
		
		public static function toggleClientFields(){
                  box = document.adminForm.clientid;
                  clientid = box.options[box.selectedIndex].value;
                  if (clientid != 0){
                    document.adminForm.clientname.disabled=true;
                    document.adminForm.contactname.disabled=true;
                    document.adminForm.clientemail.disabled=true;
                  }else{
                    document.adminForm.clientname.disabled=false;
                    document.adminForm.contactname.disabled=false;
                    document.adminForm.clientemail.disabled=false;
                  }
                }

		public static function toggleLinkFields(){
                  box = document.adminForm.linkid;
                  linkid = box.options[box.selectedIndex].value;
                  if (linkid != 0){
                    document.adminForm.linkurl.disabled=true;
                  }else{
                    document.adminForm.linkurl.disabled=false;
                  }
                }

		public static function toggleRestrictFields(){
                  box = document.adminForm.restrictbyid;
                  restrictbyid = box.options[box.selectedIndex].value;
                  box = document.adminForm.frontpage;
                  frontpage = box.options[box.selectedIndex].value;
                  if (restrictbyid == 0){
                    document.adminForm.elements['categoryid[]'].disabled=true;
                    document.adminForm.elements['contentid[]'].disabled=true;
                  }else{
                    document.adminForm.elements['categoryid[]'].disabled=false;
                    document.adminForm.elements['contentid[]'].disabled=false;
                  }
                }

		public static function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancelBanner') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if ( form.imagealt.value == "" ) {
				alert( "<?php echo JText::_( ADMIN_FLEXBANNER_ALTVAL, true ); ?>" );
							} else {
				submitform( pressbutton );
			}
		}
		
		public static function toggleAll(){
                  toggleClientFields();
                  toggleLinkFields();
                  toggleRestrictFields();
                }

		//-->
		</script>
		<form action="index.php" method="post" name="adminForm" onmouseover="toggleAll()">
		<table class="adminheading">
		<tr>
			<th>
			<?php echo JText::_('ADMIN_FLEXBANNER_BANNERBANNER'); ?>
			<small>
			<?php echo $_row->bannerid ? 'Edit' : 'New';?>
			</small>
			</th>
			<th>
			<?php echo JText::_('ADMIN_FLEXBANNER_BANNERPUBLISHED') ." ". $lists['published']; ?>
			</th>
		</tr>
		</table>

		<fieldset>
		<legend><?php echo JText::_('ADMIN_FLEXBANNER_THECLIENT') ?></legend>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_SELECTCLIENT') ." ". $lists['clientid']; ?></label>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_CLIENTNAME') ?> <input  class="inputbox" type="text" name="clientname" /></label>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_CONTACTNAME') ?> <input class="inputbox" type="text" name="contactname" /></label>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_EMAIL') ?> <input  class="inputbox" type="text" name="clientemail" /></label>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_LINKEDTO') ." ". $lists['linkedto']; ?></label>
       </fieldset>

        <fieldset>
		<legend><?php echo JText::_('ADMIN_FLEXBANNER_LINK') ?></legend>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_SELECTLINK') ." ". $lists['linkid']; ?></label>
    	<label><?php echo JText::_('ADMIN_FLEXBANNER_NEWLINKURL') ?> <input class="inputbox" type="text" name="linkurl" /></label>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_BANNERCLICKS') . ":&nbsp;" . $_row->clicks . "&nbsp;" ?><input name="reset_hits" class="button" value=<?php echo JText::_('ADMIN_FLEXBANNER_RESET')?> onclick="submitbutton('resethits');" type="button"></label>
		</fieldset>
		
		<fieldset>
		<legend><?php echo JText::_('ADMIN_FLEXBANNER_IMAGE') ?></legend>
		<label style="text-align:left;"><b><?php echo JText::_('ADMIN_FLEXBANNER_SELECTIMAGE_NOTE'); ?></b></label>
    	<br />
    	<label><?php echo JText::_('ADMIN_FLEXBANNER_SELECTIMAGE') ." ". $lists['imageurl']; ?></label>
            <label><?php echo JText::_('ADMIN_FLEXBANNER_BANNERIMAGE');  
			if (preg_match("/swf/", $_row->imageurl)) {
				?>
				<img src="images/blank.png" name="imagelib">
				<?php
			} elseif (preg_match("/gif|jpg|png/", $_row->imageurl)) {
				?>
				<img src="../images/banners/<?php echo $_row->imageurl; ?>" name="imagelib" />
				<?php
			} else {
				?>
				<img src="images/blank.png" name="imagelib" />
				<?php
			}
			?></label>
                <label><?php echo JText::_('ADMIN_FLEXBANNER_ALTIMAGETEXT') ?><input class="inputbox" type="text" name="imagealt" value="<?php echo $_row->imagealt; ?>" /></label>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_SELECTIMAGESIZE') ." ". $lists['sizeid']; ?></label>
                </fieldset>

                <fieldset>
		<legend><?php echo JText::_('ADMIN_FLEXBANNER_LOCATION') ?></legend>
		<label style="text-align:left;"><b><?php echo JText::_('ADMIN_FLEXBANNER_BANNERLOCATION_NOTE'); ?></b></label>
		<br />
		<label><?php echo JText::_('ADMIN_FLEXBANNER_BANNERLOCATION') ." ". $lists['locationid']; ?></label>
         </fieldset>

		<fieldset>
		<legend><?php echo JText::_('ADMIN_FLEXBANNER_PARAMETERS') ?></legend>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_STARTDATE') ?> <input class="inputbox" type="text" name="startdate" id="startdate" size="25" maxlength="10" value="<?php echo $_row->startdate;?>" />
			<input name="fsd" type="reset" class="button" onClick="return showCalendar('startdate', '%y-%m-%d');" value="...">
                </label>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_ENDDATE') ?> <input class="inputbox" type="text" name="enddate" id="enddate" size="25" maxlength="10" value="<?php echo $_row->enddate;?>" />
			<input name="fsd" type="reset" class="button" onClick="return showCalendar('enddate', '%y-%m-%d');" value="...">
                </label>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_MAXIMPRESSIONS') ?> <input type="text"  class="inputbox" name="maximpressions" value="<?php echo $_row->maximpressions; ?>" /></label>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_MAXCLICKS') ?> <input type="text"  class="inputbox" name="maxclicks" value="<?php echo $_row->maxclicks; ?>" /></label>
                </fieldset>

	<?php if ($fb_language == 1) { ?>
               <fieldset>
		<legend><?php echo JText::_('ADMIN_FLEXBANNER_LANGUAGERESTRICTIONS') ?></legend>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_LANGUAGE') ." ". $lists['languageid']; ?></label>
                </fieldset>
<?php } ?>
                <fieldset>
		<legend><?php echo JText::_('ADMIN_FLEXBANNER_CONTENTRESTRICTIONS') ?></legend>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_RESTRICTID') ." ". $lists['restrictbyid']; ?></label>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_FRONTPAGE') ." ". $lists['frontpage']; ?></label>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_CATEGORIES') ." ". $lists['categoryid']; ?></label>
		<label><?php echo JText::_('ADMIN_FLEXBANNER_ARTICLES') ." ". $lists['contentid']; ?></label>
                </fieldset>


                <fieldset>
		<legend><?php echo JText::_('ADMIN_FLEXBANNER_CUSTOMCODE') ?></legend>
		<label><textarea name="customcode" cols="30" rows="6"><?php echo stripslashes($_row->customcode); ?></textarea></label>
		</fieldset>

		<input type="hidden" name="option" value="<?php echo $_option; ?>" />
		<input type="hidden" name="bannerid" value="<?php echo $_row->bannerid; ?>" />
		<input type="hidden" name="clicks" value="<?php echo $_row->clicks; ?>" />
		<input type="hidden" name="task" value="listBanners" />
		<input type="hidden" name="impressions" value="<?php echo $_row->impressions; ?>" />
		</form>

<?php
	}
	
	public static function showSizes( &$rows, &$pageNav, $option ) {
		global $my;

		?>
		<form action="index.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th><?php echo JText::_('ADMIN_FLEXBANNER_SIZEMANAGER') ?></th>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">
			#
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th align="left" nowrap><?php echo JText::_('ADMIN_FLEXBANNER_SIZENAME') ?></th>
			<th align="left" nowrap><?php echo JText::_('ADMIN_FLEXBANNER_SIZEWIDTH') ?></th>
			<th align="left" nowrap><?php echo JText::_('ADMIN_FLEXBANNER_SIZEHEIGHT') ?></th>
			<th align="left" nowrap><?php echo JText::_('ADMIN_FLEXBANNER_MAXFILESIZE') ?></th>
			<th align="left" nowrap><?php echo JText::_('ADMIN_FLEXBANNER_SIZEID') ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];

			$row->id 	= $row->sizeid;
			$link 		= 'index.php?option=com_flexbanner&task=editSize&hidemainmenu=1&id='. $row->id;

			$checked 	= JHTML::_('grid.checkedOut',$row, $i );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td width="20" align="center">
				<?php //echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td width="20">
				<?php echo $checked; ?>
				</td>
				<td>
				<?php
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					echo $row->sizename;
				} else {
					?>
					<a href="<?php echo $link; ?>" title="Edit AdTrack Size">
					<?php echo $row->sizename; ?>
					</a>
					<?php
				}
				?>
				</td>
				<td>
				<?php echo $row->width;?>
				</td>
				<td>
				<?php echo $row->height;?>
				</td>
				<td align="center">
				<?php echo $row->maxfilesize;?>
				</td>
				<td align="center">
				<?php echo $row->sizeid;?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="listSizes" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}

	public static function sizeForm( &$_row, &$lists, $_option ) {
                global $mosConfig_live_site;
          ?>
          <link rel="stylesheet" href="<?php echo JURI::base();?>/administrator/components/com_flexbanner/flexbanner.css" type="text/css" />
          <script language="javascript" type="text/javascript">
	    <!--
              function submitbutton(pressbutton) {
	        var form = document.adminForm;
	        if (pressbutton == 'cancelbanner') {
	          submitform( pressbutton );
	          return;
	        }
                submitform( pressbutton );
              }
            //-->
          </script>
          <form action="index.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			Size:
			<small>
			<?php echo $_row->sizeid ? 'Edit' : 'New';?>
			</small>
			</th>
		</tr>
		</table>

                <fieldset>
                <legend><?php echo JText::_('ADMIN_FLEXBANNER_SIZEDETAILS') ?></legend>
                <label><?php echo JText::_('ADMIN_FLEXBANNER_SIZENAME') ?><input type="text"  class="inputbox" name="sizename" value="<?php echo $_row->sizename; ?>"/></label>
                <label><?php echo JText::_('ADMIN_FLEXBANNER_WIDTH') ?><input type="text"  class="inputbox" name="width" value="<?php echo $_row->width; ?>"/></label>
                <label><?php echo JText::_('ADMIN_FLEXBANNER_HEIGHT') ?><input type="text" class="inputbox" name="height" value="<?php echo $_row->height; ?>"/></label>
                <label><?php echo JText::_('ADMIN_FLEXBANNER_MAXFILE') ?><input type="text" class="inputbox" name="maxfilesize" value="<?php echo $_row->maxfilesize; ?>"/></label>

                </fieldset>

		<input type="hidden" name="option" value="<?php echo $_option; ?>" />
		<input type="hidden" name="task" value="listSizes" />
		<input type="hidden" name="sizeid" value="<?php echo $_row->sizeid; ?>" />
		</form>
        <?php
        }

	public static function showLocations( &$rows, &$pageNav, $option ) {
		global $my;

		?>
		<form action="index.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th><?php echo JText::_('ADMIN_FLEXBANNER_LOCATIONMANAGER') ?></th>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">
			#
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th align="left" nowrap width="400"><?php echo JText::_('ADMIN_FLEXBANNER_LOCATIONNAME') ?></th>
			<th width="70" align="left" nowrap>
			<b><?php echo JText::_('ADMIN_FLEXBANNER_LOCATIONID') ?></b></th>
			<th align="left" nowrap>&nbsp;
			
			</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];

			$row->id 	= $row->locationid;
			$link 		= 'index.php?option=com_flexbanner&task=editLocation&hidemainmenu=1&id='. $row->id;

			$checked 	= JHTML::_('grid.checkedOut',$row, $i );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td width="20" align="center">
				<?php //echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td width="20">
				<?php echo $checked; ?>
				</td>
				<td>
				<?php
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					echo $row->locationname;
				} else {
					?>
					<a href="<?php echo $link; ?>" title="Edit AdTrack Size">
					<?php echo $row->locationname; ?>
					</a>
					<?php
				}
				?>
				</td>
				<td align="center">
				<b><?php echo $row->locationid;?></b>
				</td>
				<td align="center">&nbsp;
				
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>
		
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="listlocations" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}
	
	public static function locationForm( &$_row, &$lists, $option ) {

          ?>
          <link rel="stylesheet" href="<?php echo JURI::base();?>/administrator/components/com_flexbanner/flexbanner.css" type="text/css" />
          <script language="javascript" type="text/javascript">
	    <!--
              function submitbutton(pressbutton) {
	        var form = document.adminForm;
	        if (pressbutton == 'cancelbanner') {
	          submitform( pressbutton );
	          return;
	        }
                submitform( pressbutton );
              }
            //-->
          </script>
          <form action="index.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th><?php echo JText::_('ADMIN_FLEXBANNER_LOCATION') ?><small>
			<?php echo $_row->locationid ? 'Edit' : 'New';?>
			</small>
			</th>
		</tr>
		</table>

                <fieldset>
                <legend><?php echo JText::_('ADMIN_FLEXBANNER_LOCATIONDETAILS') ?></legend>
                <label><?php echo JText::_('ADMIN_FLEXBANNER_LOCATIONNAME') ?>: <input type="text" class="inputbox" name="locationname" value="<?php echo $_row->locationname; ?>"/></label>
                </fieldset>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="listLocations" />
		<input type="hidden" name="locationid" value="<?php echo $_row->locationid; ?>" />
		</form>
        <?php
        }

	public static function showClients( &$rows, &$pageNav, $option ) {
		global $my;

		?>
		<form action="index.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th><?php echo JText::_('ADMIN_FLEXBANNER_CLIENTMANAGER') ?></th>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">
			#
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th align="left" nowrap><?php echo JText::_('ADMIN_FLEXBANNER_CLIENTNAME') ?></th>
			<th align="left" nowrap><?php echo JText::_('ADMIN_FLEXBANNER_CONTACTNAME') ?></th>
			<th align="left" nowrap><?php echo JText::_('ADMIN_FLEXBANNER_CONTACTEMAIL') ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];

			$row->id 	= $row->clientid;
			$link 		= 'index.php?option=com_flexbanner&task=editClient&hidemainmenu=1&id='. $row->id;

			$checked 	= JHTML::_('grid.checkedOut',$row, $i );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td width="20" align="center">
				<?php //echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td width="20">
				<?php echo $checked; ?>
				</td>
				<td>
				<?php
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					echo $row->clientname;
				} else {
					?>
					<a href="<?php echo $link; ?>" title="Edit Client">
					<?php echo $row->clientname; ?>
					</a>
					<?php
				}
				?>
				</td>
				<td>
				<?php echo $row->contactname;?>
				</td>
				<td>
				<?php echo $row->contactemail;?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>
		
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="listClients" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}

	public static function clientForm( &$_row, &$lists, $_option ) {
               
          ?>
          <link rel="stylesheet" href="<?php  echo JURI::base();?>/administrator/components/com_flexbanner/flexbanner.css" type="text/css" />
          <script language="javascript" type="text/javascript">
	    <!--
              function submitbutton(pressbutton) {
	        var form = document.adminForm;
	        if (pressbutton == 'cancelbanner') {
	          submitform( pressbutton );
	          return;
	        }
                submitform( pressbutton );
              }
            //-->
          </script>
          <form action="index.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			<?php echo JText::_('ADMIN_FLEXBANNER_CLIENT') ?>
			<small>
			<?php echo $_row->clientid ? 'Edit' : 'New';?>
			</small>
			</th>
		</tr>
		</table>

                <fieldset>
                <legend><?php echo JText::_('ADMIN_FLEXBANNER_CLIENTDETAILS') ?></legend>
                <label><?php echo JText::_('ADMIN_FLEXBANNER_CLIENTNAME') ?>: <input type="text" class="inputbox" name="clientname" value="<?php echo $_row->clientname; ?>"/></label>
                <label><?php echo JText::_('ADMIN_FLEXBANNER_CONTACTNAME') ?>: <input type="text" class="inputbox" name="contactname" value="<?php echo $_row->contactname; ?>"/></label>
                <label><?php echo JText::_('ADMIN_FLEXBANNER_CONTACTEMAIL') ?>: <input type="text" class="inputbox" name="contactemail" value="<?php echo $_row->contactemail; ?>"/></label>
                </fieldset>

		<input type="hidden" name="option" value="<?php echo $_option; ?>" />
		<input type="hidden" name="task" value="listClients" />
		<input type="hidden" name="clientid" value="<?php echo $_row->clientid; ?>" />
		</form>
        <?php
        }

	public static function showLinks( &$rows, &$pageNav, $option ) {
		global $my;

		?>
		<form action="index.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th><?php echo JText::_('ADMIN_FLEXBANNER_LINKMANAGER') ?></th>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">
			#
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th align="left" nowrap><?php echo JText::_('ADMIN_FLEXBANNER_LINKURL') ?></th>
			<th align="left" nowrap><?php echo JText::_('ADMIN_FLEXBANNER_THECLIENT') ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];

			$row->id 	= $row->linkid;
			$link 		= 'index.php?option=com_flexbanner&task=editLink&hidemainmenu=1&id='. $row->id;

			$checked 	= JHTML::_('grid.checkedOut',$row, $i );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td width="20" align="center">
				<?php //echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td width="20">
				<?php echo $checked; ?>
				</td>
				<td>
				<?php
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					echo $row->linkurl;
				} else {
					?>
					<a href="<?php echo $link; ?>" title="Edit Link">
					<?php echo $row->linkurl; ?>
					</a>
					<?php
				}
				?>
				</td>
				<td>
				<?php echo $row->clientname;?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>
		
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="listLinks" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}

	public static function linkForm( &$_row, &$lists, $_option ) {
                global $mosConfig_live_site;
          ?>
          <link rel="stylesheet" href="<?php echo $mosConfig_live_site;?>/administrator/components/com_flexbanner/flexbanner.css" type="text/css" />
          <script language="javascript" type="text/javascript">
	    <!--
              function submitbutton(pressbutton) {
	        var form = document.adminForm;
	        if (pressbutton == 'cancelbanner') {
	          submitform( pressbutton );
	          return;
	        }
                submitform( pressbutton );
              }
            //-->
          </script>
          <form action="index.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			<?php echo JText::_('ADMIN_FLEXBANNER_LINK') ?>
			<small>
			<?php echo $_row->linkid ? 'Edit' : 'New';?>
			</small>
			</th>
		</tr>
		</table>

                <fieldset>
                <legend><?php echo JText::_('ADMIN_FLEXBANNER_LINKDETAILS') ?></legend>
                <label><?php echo JText::_('ADMIN_FLEXBANNER_URL') ?><input type="text" class="inputbox" name="linkurl" cols="60" value="<?php echo $_row->linkurl; ?>" /></label>
                <label><?php echo JText::_('ADMIN_FLEXBANNER_CLIENT') ?><?php echo $lists['clientid']; ?></label>
                </fieldset>

		<input type="hidden" name="option" value="<?php echo $_option; ?>" />
		<input type="hidden" name="task" value="listLinks" />		<input type="hidden" name="linkid" value="<?php echo $_row->linkid; ?>" />
		</form>

        <?php
       }
}
?>