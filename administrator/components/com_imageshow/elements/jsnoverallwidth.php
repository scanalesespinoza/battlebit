<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsnoverallwidth.php 6637 2011-06-08 08:21:13Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');
class JFormFieldJSNOverAllWidth extends JFormField
{
	public $type = 'JSNOverAllWidth';
	protected function getInput()
	{
		$doc		= JFactory::getDocument();
		$msg        = JText::_('JSN_ALLOW_ONLY_DIGITS');
		$doc->addScriptDeclaration("
			var original_value = '';

			function getInputValue(object)
			{
				original_value = object.value;
			}

			function checkNumberValue(object)
			{
				var patt;
				var msg;
				patt=/^[0-9]+$/;
				msg = '".$msg."';
				if(object.value != '' && !patt.test(object.value))
				{
					alert (msg);
					object.value = original_value;
					return;
				}
			}
			function changeoverallWithValue()
			{
				var patt=/^[0-9]+$/;
				var value_tmp_width = $('tmp_width').value;
				var dimension   	= $('tmp_width_dimension').value;
				if(value_tmp_width != '' && !patt.test(value_tmp_width))
				{
					alert ('".$msg."');
					$('tmp_width').value = original_value;
				}
				else
				{
					if(value_tmp_width != '')
					{
						$('".$this->id."').value = value_tmp_width+dimension;
					}
					else
					{
						$('".$this->id."').value = '';
					}
				}
			}");
		$html       = '';
		$size		= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$maxLength	= $this->element['maxlength'] ? ' maxlength="'.(int) $this->element['maxlength'].'"' : '';
		$class		= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : ' class="jsn-text"';
		$readonly	= ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$dimension  = array(
			'0' => array('value' => 'px',
			'text' => JText::_('px')),
			'1' => array('value' => '%',
			'text' => JText::_('%'))
		);

		$overallWidthDimensionValue = "%";
		$overallWith = htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');
		$posPercentageOverallWidth = strpos($overallWith, '%');

		if ($posPercentageOverallWidth)
		{
			$overallWith 	= substr($overallWith, 0, $posPercentageOverallWidth + 1);
			$overallWidthDimensionValue = "%";
		}
		else
		{
			$overallWith = $overallWith;
			$overallWidthDimensionValue = "px";
		}
		$list = JHTML::_('select.genericList', $dimension, 'tmp_width_dimension', 'class="inputbox" onchange="changeoverallWithValue();"'. '', 'value', 'text', $overallWidthDimensionValue );
		// Initialize JavaScript field attributes.
		$onchange	= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		$html 		 = '<input type="text" name="tmp_width" id="tmp_width"'.' value="'.($overallWith != ''? (int) $overallWith:'').'"'.$class.$size.$disabled.$readonly.$maxLength.' onfocus="getInputValue(this);" onchange="changeoverallWithValue();"/>';
		$html 		.= '<input type="hidden" name="'.$this->name.'" id="'.$this->id.'"' .
					' value="'.$overallWith.'"'.$class.$size.$disabled.$readonly.$onchange.$maxLength.'/> '.$list;
		return $html;
	}
}
