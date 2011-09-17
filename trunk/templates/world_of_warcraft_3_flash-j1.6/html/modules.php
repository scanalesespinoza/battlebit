<?php
defined('_JEXEC') or die('Restricted access');

/**
 * This is a file to add template specific chrome to module rendering.  To use it you would
 * set the style attribute for the given module(s) include in your template to use the style
 * for each given modChrome function.
 *
 * eg.  To render a module mod_test in the sliders style, you would use the following include:
 * <jdoc:include type="module" name="test" style="slider" />
 *
 * This gives template designers ultimate control over how modules are rendered.
 *
 * NOTICE: All chrome wrapping methods should be named: modChrome_{STYLE} and take the same
 * three arguments.
 */


/**
 * Custom module chrome, echos the whole module in a <div> and the header in <h{x}>. The level of
 * the header can be configured through a 'headerLevel' attribute of the <jdoc:include /> tag.
 * Defaults to <h3> if none given
 */


function modChrome_wowround_left($module, &$params, &$attribs)
{ ?>
		<div class="module<?php echo $params->get('moduleclass_sfx'); ?>">
			<?php if ($module->showtitle != 0) : ?>
				<h3 class="module"><strong><?php echo $module->title; ?></strong></h3>
			<?php endif; ?>
			<div>
				<div>
					<div>
					<?php echo $module->content; ?>
					</div>
				</div>
			</div>
		</div>
	<?php
}

function modChrome_wowround_right($module, &$params, &$attribs)
{ ?>
		<div class="module<?php echo $params->get('moduleclass_sfx'); ?>">
			<?php if ($module->showtitle != 0) : ?>
				<h3 class="module"><strong><?php echo $module->title; ?></strong></h3>
			<?php endif; ?>
			<div>
				<div>
					<div>
					<?php echo $module->content; ?>
					</div>
				</div>
			</div>
		</div>
	<?php
}

function modChrome_wowround($module, &$params, &$attribs)
{ ?>
		<div class="module<?php echo $params->get('moduleclass_sfx'); ?>">
			<?php if ($module->showtitle != 0) : ?>
				<h3 class="module"><strong><?php echo $module->title; ?></strong></h3>
			<?php endif; ?>
			<div>
				<div>
					<div>
					<?php echo $module->content; ?>
					</div>
				</div>
			</div>
		</div>
	<?php
}

function modChrome_wowxhtml($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
		<div class="moduletable<?php echo $params->get('moduleclass_sfx'); ?>">
		<?php if ($module->showtitle != 0) : ?>
			<h3 class="moduletable"><?php echo $module->title; ?></h3>
		<?php endif; ?>
			<?php echo $module->content; ?>
		</div>
	<?php endif;
}