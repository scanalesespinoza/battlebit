<?php
/**
 * @version		$Id: edit_options.php 21097 2011-04-07 15:38:03Z dextercowley $
 * @package		Joomla.Administrator
 * @subpackage	com_templates
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

	$fieldSets = $this->form->getFieldsets('params');

	foreach ($fieldSets as $name => $fieldSet) :
		$label = !empty($fieldSet->label) ? $fieldSet->label : 'COM_TEMPLATES_'.$name.'_FIELDSET_LABEL';
		echo JHtml::_('sliders.panel',JText::_($label), $name.'-options');
			if (isset($fieldSet->description) && trim($fieldSet->description)) :
				echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
			endif;
			?>
		<fieldset class="panelform">
			<ul class="adminformlist">
			<?php foreach ($this->form->getFieldset($name) as $field) : ?>
				<li>
				<?php if (!$field->hidden) : ?>
					<?php echo $field->label; ?>
				<?php endif; ?>
					<?php echo $field->input; ?>
				</li>
			<?php endforeach; ?>
			</ul>
		</fieldset>
	<?php endforeach;  ?>
