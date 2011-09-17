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
class ImageShowViewShowlist extends JView
{
		function display($tpl = null)
		{
			global $mainframe, $option;
			$objJSNImages 		= JSNISFactory::getObj('classes.jsn_is_images');
			JHTML::_('behavior.modal', 'a.modal');
			JHTML::stylesheet('imageshow.css','administrator/components/com_imageshow/assets/css/');
			JHTML::script('jsn_is_imageshow.js','administrator/components/com_imageshow/assets/js/');
			JHTML::script('jsn_is_utils.js','administrator/components/com_imageshow/assets/js/');

			$model 	= $this->getModel();
			$lists 	= array();
					$items 	= $this->get('data');
					if($items->showlist_id != 0 && $items->showlist_id != '')
					{
						if($objJSNImages->checkImageLimition($items->showlist_id))
						{
							$msg = JText::_('SHOWLIST_YOU_HAVE_REACHED_THE_LIMITATION_OF_10_IMAGES_IN_FREE_EDITION');
							JError::raiseNotice(100, $msg);
						}
					}
					$alternativeContentCombo = array(
						'0' => array('value' => '4',
						'text' => JText::_('SHOWLIST_HTML_JS_GALLERY')),
						'1' => array('value' => '0',
						'text' => JText::_('SHOWLIST_FLASH_PLAYER_REQUIREMENT_MESSAGE')),
						'2' => array('value' => '3',
						'text' => JText::_('SHOWLIST_STATIC_IMAGE')),
						'3' => array('value' => '2',
						'text' => JText::_('SHOWLIST_JOOMLA_ARTICLE')),
						'4' => array('value' => '1',
						'text' => JText::_('SHOWLIST_JOOMLA_MODULE'))
					);
					$seoContent = array(
						'0' => array('value' => '0',
						'text' => JText::_('SHOWLIST_SHOWLIST_AND_IMAGES_DETAILS')),
						'1' => array('value' => '1',
						'text' => JText::_('SHOWLIST_JOOMLA_ARTICLE')),
						'2' => array('value' => '2',
						'text' => JText::_('SHOWLIST_JOOMLA_MODULE'))
					);
					$authorizationCombo = array(
						'0' => array('value' => '0',
						'text' => JText::_('SHOWLIST_NO_MESSAGE')),
						'1' => array('value' => '1',
						'text' => JText::_('SHOWLIST_JOOMLA_ARTICLE'))
					);
					$lists['alternativeContentCombo'] = JHTML::_('select.genericList', $alternativeContentCombo, 'alternative_status', 'class="inputbox" onchange="JSNISImageShow.ShowListCheckAlternativeContent();"'. '', 'value', 'text', ($items->alternative_status != '')?$items->alternative_status:4);
					$lists['seoContent'] = JHTML::_('select.genericList', $seoContent, 'seo_status', 'class="inputbox" onchange="JSNISImageShow.ShowListCheckSeoContent();"'. '', 'value', 'text', $items->seo_status );
					$lists['authorizationCombo'] = JHTML::_('select.genericList', $authorizationCombo, 'authorization_status', 'class="inputbox" onchange="JSNISImageShow.ShowListCheckAuthorizationContent();"'. '', 'value', 'text', $items->authorization_status );

					$lists['published'] 	= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', ($items->published !='')?$items->published:1 );
					$lists['overrideTitle'] = JHTML::_('select.booleanlist',  'override_title', 'class="inputbox"', $items->override_title);
					$lists['overrideDesc'] 	= JHTML::_('select.booleanlist',  'override_description', 'class="inputbox"', $items->override_description);
					$lists['overrideLink'] 	= JHTML::_('select.booleanlist',  'override_link', 'class="inputbox"', $items->override_link);

					$isNew				= ($model->_id < 1);
					$query 				= 'SELECT ordering AS value, showlist_title AS text'
											. ' FROM #__imageshow_showlist'
											. ' ORDER BY ordering';
					$lists['ordering'] 			= JHTML::_('list.specificordering',  $items, $items->showlist_id, $query );
					$this->assignRef('lists', $lists);
					$this->assignRef('items', $items);
			parent::display($tpl);
		}
}
?>