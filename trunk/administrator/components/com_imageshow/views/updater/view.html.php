<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 7485 2011-07-26 10:08:14Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.view');
class ImageShowViewUpdater extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;
		JHTML::script('jsn_is_imageshow.js','administrator/components/com_imageshow/assets/js/');
		JHTML::script('jsn_is_updater.js','administrator/components/com_imageshow/assets/js/');
		JHTML::stylesheet('imageshow.css','administrator/components/com_imageshow/assets/css/');
		$objVersion 	  = new JVersion();
		$objJSNXML 		  = JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$objJSNUtil		  = JSNISFactory::getObj('classes.jsn_is_utils');
		$infoXmlDetail    = $objJSNXML->parserXMLDetails();
		$this->assignRef('infoXmlDetail',$infoXmlDetail);
		$this->assignRef('objVersion',$objVersion);
		$this->assignRef('objJSNUtil',$objJSNUtil);
		parent::display($tpl);
	}

	function getThemeItems()
	{
		$model 		= JModel::getInstance('plugins', 'imageshowmodel');
		return $model->getFullData();
	}

	function renderThemeItems($type, $id, $data)
	{
		$objJSNUtil		= JSNISFactory::getObj('classes.jsn_is_utils');
		$items 			= $this->getThemeItems();
		$html			= '';
		$check			= false;

		if (count($items))
		{
			for ($i = 0, $count = count($items); $i < $count; $i++)
			{
				$item 			= $items[$i];
				$state 			= '';
				$element		= trim($item->element);
				$name			= trim($item->name);
				$version		= trim($item->version);
				$compareData 	= $data[$element];

				if ($item->extension_id == $id && $type == 'theme') {
					$state = 'selected-item';
				}

				$runningVersionParam = $version;
				$latestVersionParam  = $compareData['version'];
				$check = $objJSNUtil->compareVersion($runningVersionParam, $latestVersionParam);

				if($compareData['commercial']) {
					$commercial = 'yes';
				} else {
					$commercial = 'no';
				}

				if ($check) {
					$html= '<li class="'.$state.'" id="'.$element.'"><a href="index.php?option=com_imageshow&controller=updater&type=theme&element_id='.$item->extension_id.'&commercial='.$commercial.'" id="'.$element.'-link"><span class="items icon-themes">'.$name.' - <span id="'.$element.'-version" class="element-version">'.$compareData['version'].'</span></span></a></li>';
				}
			}
		}
		return $html;
	}

	function renderCoreItem($type, $element, $data)
	{
		$state 			= '';
		$check			= false;
		$objJSNUtil		= JSNISFactory::getObj('classes.jsn_is_utils');
		$coreData       = $element['imageshow'];
		$element		= trim($coreData['id']);
		$name			= trim($coreData['name']);
		$version		= trim($coreData['version']);
		$compareData 	= $data['imageshow'];
		$html 			= '';

		if ($type == 'core') {
			$state = 'selected-item';
		}

		$runningVersionParam = $version;
		$latestVersionParam  = $compareData['version'];
		$check = $objJSNUtil->compareVersion($runningVersionParam, $latestVersionParam);

		if ($compareData['commercial']) {
			$commercial = 'yes';
		} else {
			$commercial = 'no';
		}

		if ($check) {
			$html= '<li class="'.$state.'" id="'.$element.'"><a href="index.php?option=com_imageshow&controller=updater&type=core&element_id=0&commercial='.$commercial.'" id="'.$element.'-link"><span class="items icon-configs">JSN '.$name.' Core - <span id="'.$element.'-version" class="element-version">'.$compareData['version'].'</span></span></a></li>';
		}

		return $html;
	}
}