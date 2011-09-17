<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 6637 2011-06-08 08:21:13Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class ImageShowViewShowcase extends JView
{
		function display($tpl = null)
		{
			global $mainframe, $option;

			$objISUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
			JHTML::script('mooRainbow.1.2.js','administrator/components/com_imageshow/assets/js/');
			JHTML::stylesheet('mooRainbow.css','administrator/components/com_imageshow/assets/css/');
			JHTML::stylesheet('imageshow.css','administrator/components/com_imageshow/assets/css/');
			JHTML::script('jsn_is_imageshow.js','administrator/components/com_imageshow/assets/js/');
			JHTML::script('jsn_is_utils.js','administrator/components/com_imageshow/assets/js/');

			JHTML::_('behavior.modal', 'a.modal');
			$lists 				= array();
			$format 			= JRequest::getVar('view_format', 'temporary');
			$showlist_id 		= JRequest::getInt('showlist_id');
			$showcaseTheme 		= JRequest::getVar('theme', 'showcasethemeclassic');
			$model	 			= $this->getModel();
			$items 				= $this->get('data');
			$session 			= JFactory::getSession();
			$overallWidthDimensionValue     = '%';
			$showcaseThemeSession 	= $session->get('showcaseThemeSession');
			$session->clear('showcaseThemeSession');

			// GENERAL TAB BEGIN
			if($showcaseThemeSession){
				$publishShowcase = $showcaseThemeSession['published'];
			}else if($items->published != ''){
				$publishShowcase = $items->published;
			}else{
				$publishShowcase = 1;
			}
			$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $publishShowcase);

			$query 		= 'SELECT ordering AS value, showcase_title AS text'
			. ' FROM #__imageshow_showcase'
			. ' ORDER BY ordering';
			$lists['ordering'] 			= JHTML::_('list.specificordering',  $items, $items->showcase_id, $query );

			$generalImagesOrder= array(
				'0' => array('value' => 'forward',
				'text' => JText::_('SHOWCASE_GENERAL_FORWARD')),
				'1' => array('value' => 'backward',
				'text' => JText::_('SHOWCASE_GENERAL_BACKWARD')),
				'2' => array('value' => 'random',
				'text' => JText::_('SHOWCASE_GENERAL_RANDOM'))
			);
			$lists['generalImagesOrder'] = JHTML::_('select.genericList', $generalImagesOrder, 'general_images_order', 'class="inputbox" '. '', 'value', 'text', (!empty($showcaseThemeSession['general_images_order'])) ? $showcaseThemeSession['general_images_order'] : $items->general_images_order );

			$dimension  = array(
				'0' => array('value' => 'px',
				'text' => JText::_('px')),
				'1' => array('value' => '%',
				'text' => JText::_('%'))
			);

			// GENERAL TAB END

			$generalData = array();

			if(!empty($showcaseThemeSession))
      		{
      			$generalData['generalTitle'] 			= $showcaseThemeSession['showcase_title'];
      			$generalData['generalWidth'] 			= $showcaseThemeSession['general_overall_width'];
      			$generalData['generalHeight'] 			= $showcaseThemeSession['general_overall_height'];
      			$generalData['generalCornerRadius'] 	= $showcaseThemeSession['general_round_corner_radius'];
      			$generalData['generalBorderStroke'] 	= $showcaseThemeSession['general_border_stroke'];
      			$generalData['generalBgColor']	 		= $showcaseThemeSession['background_color'];
      			$generalData['generalBorderColor']	 	= $showcaseThemeSession['general_border_color'];
      			$generalData['generalImageLoad'] 		= $showcaseThemeSession['general_number_images_preload'];
      		}
      		else if($items->general_overall_width)
      		{
      			$generalData['generalTitle'] 			= htmlspecialchars($items->showcase_title);
      			$generalData['generalWidth'] 			= $items->general_overall_width;
      			$generalData['generalHeight'] 			= $items->general_overall_height;
      			$generalData['generalCornerRadius'] 	= $items->general_round_corner_radius;
      			$generalData['generalBorderStroke'] 	= $items->general_border_stroke;
      			$generalData['generalBgColor']	 		= $items->background_color;
      			$generalData['generalBorderColor']	 	= $items->general_border_color;
      			$generalData['generalImageLoad'] 		= $items->general_number_images_preload;
      		}
      		else
      		{
      			$generalData['generalTitle'] 			= '';
      			$generalData['generalWidth'] 			= '100%';
      			$generalData['generalHeight'] 			= '450';
      			$generalData['generalCornerRadius']  	= 0;
      			$generalData['generalBorderStroke']  	= 2;
      			$generalData['generalBgColor']			= '#ffffff';
      			$generalData['generalBorderColor']		= '#000000';
      			$generalData['generalImageLoad']  		= 3;
      		}

			$overallWith = $generalData['generalWidth'];
			$posPercentageOverallWidth = strpos($overallWith, '%');

			if ($posPercentageOverallWidth)
			{
				$overallWith 	= substr($overallWith, 0, $posPercentageOverallWidth + 1);
				$overallWidthDimensionValue = "%";
			}
			else
			{
				$overallWith = (int) $overallWith;
				$overallWidthDimensionValue = "px";
			}

			$lists['overallWidthDimension'] = JHTML::_('select.genericList', $dimension, 'overall_width_dimension', 'class="inputbox" '. '', 'value', 'text', $overallWidthDimensionValue );
			$this->assignRef('generalData', $generalData);
			$this->assignRef('lists', $lists);
			$this->assignRef('items', $items);

			parent::display($tpl);
		}
}
?>