<?php
/**
 * @version		$Id: cpanel.php 21721 2011-07-01 08:48:47Z chdemko $
 * @package		Joomla.Administrator
 * @subpackage	Templates.bluestork
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

$app = JFactory::getApplication();
$doc = JFactory::getDocument();

$doc->addStyleSheet('templates/system/css/system.css');
$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');

if ($this->direction == 'rtl') {
	$doc->addStyleSheet('templates/'.$this->template.'/css/template_rtl.css');
}

/** Load specific language related css */
$lang = JFactory::getLanguage();
$file = 'language/'.$lang->getTag().'/'.$lang->getTag().'.css';
if (JFile::exists($file)) {
	$doc->addStyleSheet($file);
}

if ($this->params->get('textBig')) {
	$doc->addStyleSheet('templates/'.$this->template.'/css/textbig.css');
}

if ($this->params->get('highContrast')) {
	$doc->addStyleSheet('templates/'.$this->template.'/css/highcontrast.css');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo  $this->language; ?>" lang="<?php echo  $this->language; ?>" dir="<?php echo  $this->direction; ?>" >
	<head>
		<jdoc:include type="head" />

		<!--[if IE 7]>
			<link href="templates/<?php echo  $this->template ?>/css/ie7.css" rel="stylesheet" type="text/css" />
		<![endif]-->
	</head>
<body id="minwidth-body">
	<div id="border-top" class="h_blue">
		<span class="logo"><a href="http://www.joomlaspanish.org" target="_blank"><img src="templates/<?php echo  $this->template ?>/images/logo.png" alt="Joomla!" /></a></span>
		<span class="title"><a href="index.php"><?php echo $this->params->get('showSiteName') ? $app->getCfg('sitename') : JText::_('TPL_BLUESTORK_HEADER'); ?></a></span>
	</div>
	<div id="header-box">
		<div id="module-status">
			<jdoc:include type="modules" name="status"/>
			<?php
				//Display an harcoded logout
				$task = JRequest::getCmd('task');
				if ($task == 'edit' || $task == 'editA' || JRequest::getInt('hidemainmenu')) {
					$logoutLink = '';
				} else {
					$logoutLink = JRoute::_('index.php?option=com_login&task=logout&'. JUtility::getToken() .'=1');
				}
				$hideLinks	= JRequest::getBool('hidemainmenu');
				$output = array();
				// Print the Preview link to Main site.
				$output[] = '<span class="viewsite"><a href="'.JURI::root().'" target="_blank">'.JText::_('JGLOBAL_VIEW_SITE').'</a></span>';
				// Print the logout link.
				$output[] = '<span class="logout">' .($hideLinks ? '' : '<a href="'.$logoutLink.'">').JText::_('JLOGOUT').($hideLinks ? '' : '</a>').'</span>';
				// Reverse rendering order for rtl display.
				if ($this->direction == "rtl") :
					$output = array_reverse($output);
				endif;
				// Output the items.
				foreach ($output as $item) :
				echo $item;
				endforeach;
			?>
		</div>
		<div id="module-menu">
			<jdoc:include type="modules" name="menu"/>
		</div>
		<div class="clr"></div>
	</div>
	<div id="content-box">
		<div id="element-box">
			<jdoc:include type="message" />
			<div class="m" >
				<div class="adminform">
					<div class="cpanel-left">
						<?php if ($this->countModules('icon')>1):?>
							<?php echo JHtml::_('sliders.start', 'position-icon', array('useCookie' => 1));?>
							<jdoc:include type="modules" name="icon" style="sliders" />
							<?php echo JHtml::_('sliders.end');?>
						<?php else:?>
							<jdoc:include type="modules" name="icon" />
						<?php endif;?>
					</div>
					<div class="cpanel-right">
						<jdoc:include type="component" />
					</div>
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<noscript>
			<?php echo  JText::_('JGLOBAL_WARNJAVASCRIPT') ?>
		</noscript>
	</div>
	<jdoc:include type="modules" name="footer" style="none"  />
	<div id="footer">
		<p class="copyright">
			<?php $joomla= '<a href="http://www.joomla.org">Joomla!&#174;</a>';
				echo JText::sprintf('JGLOBAL_ISFREESOFTWARE', $joomla) ?>
			<span class="version"><?php echo  JText::_('JVERSION') ?> <?php echo  JVERSION; ?></span>
		</p>
	</div>
</body>
</html>
