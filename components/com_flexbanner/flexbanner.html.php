<?php
/**
* @copyright Copyright (C) 2009-2011 inch communications ltd. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class HTML_FlexBanner {

	public static function showClientbanners( &$rows, &$pageNav, $option ) {

if (count( $rows ) > 0) { 
	?>
        <link rel="stylesheet" href="<?php echo JURI::base();?>components/com_flexbanner/flexbanner.css" type="text/css" />
		<table class="fblist">
		<tr>
			<th align="left"><?php echo JText::_( 'FLEXBANNER_BANNERALT' ); ?></th>
			<th align="left" width="200" nowrap><?php echo JText::_('FLEXBANNER_BANNERIMAGE'); ?></th>
			<th width="70" nowrap><?php echo JText::_('FLEXBANNER_BANNERPUB'); ?></th>
			<th width="60" nowrap><?php echo JText::_('FLEXBANNER_BANNERFIN'); ?></th>
			<th width="100" nowrap><?php echo JText::_('FLEXBANNER_BANNERDAILYIMP'); ?></th>
			<th width="110" nowrap><?php echo JText::_('FLEXBANNER_BANNERIMPMADE'); ?></th>
			<th width="40"><?php echo JText::_('FLEXBANNER_BANNERCLICKS'); ?></th>
			<th width="50" nowrap><?php echo JText::_('FLEXBANNER_BANNERPERCENTCLICKS'); ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];

			$row->id 	= $row->bannerid;

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
				<td align="left">
				<?php echo $row->imagealt; ?>
				</td>
				<td><?php if (strpos($row->imageurl, '.swf')==0){ ?><img src="<?php echo JURI::base(). "images/banners/" . $row->imageurl; ?>" alt="<?php echo $row->imagealt; ?>" width="100"/> <?php } ?></td>
				<td align="center">
				<img src="components/com_flexbanner/images/<?php echo $img;?>" width="12" height="12" border="0" alt="<?php echo $alt; ?>" />
				</td>
				<td align="center">
				<img src="components/com_flexbanner/images/<?php echo $fimg;?>" width="12" height="12" border="0" alt="<?php echo $falt; ?>" />
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
		<br />		
		<?php echo $pageNav->getListFooter(); ?>
		<br />
		<?php
	} else {
	echo "<h2>".JText::_( 'FLEXBANNER_NOBANNER')."</h2>";
	}
	}
}
?>