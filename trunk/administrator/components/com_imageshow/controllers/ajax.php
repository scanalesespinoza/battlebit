<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: ajax.php 6777 2011-06-16 02:41:34Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.controller' );

class ImageShowControllerAjax extends JController
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display($cachable = false, $urlparams = false)
	{
		JRequest::setVar('layout', 'default');
		JRequest::setVar('view', 'ajax');
		parent::display();
	}

	function checkVersion()
	{
		$objJSNUtil     = JSNISFactory::getObj('classes.jsn_is_utils');
		$jsnProductInfo = $objJSNUtil->encodeUrl('http://www.joomlashine.com/joomla-extensions/jsn-imageshow-version-check.html');
		$objJSNHTTP   	= JSNISFactory::getObj('classes.jsn_is_httprequest', null, $jsnProductInfo);
		$objJSNJSON     = JSNISFactory::getObj('classes.jsn_is_json');

		$result    = $objJSNHTTP->DownloadToString();

		if ($result == false)
		{
			echo $objJSNJSON->encode(array('connection' => false, 'version' => ''));
			exit();
		}
		else
		{
			$stringExplode  = explode("\n", $result);
			$newVersion 	= trim(@$stringExplode[2]);
			echo $objJSNJSON->encode(array('connection' => true, 'version' => $newVersion));
			exit();
		}
	}

	function checkUpdate()
	{
		$name 		 	= JRequest::getVar('name');
		$edition 	 	= JRequest::getVar('edition');
		$objJSNUtil     = JSNISFactory::getObj('classes.jsn_is_utils');
		$link		 	= CHECKUPDATE_LINK.'&name='.urlencode($name).'&edition='.urlencode($edition);
		$objJSNHTTP   	= JSNISFactory::getObj('classes.jsn_is_httprequest', null, $link);
		$objJSNJSON     = JSNISFactory::getObj('classes.jsn_is_json');
		$result    		= $objJSNHTTP->DownloadToString();

		if (!$result)
		{
			echo $objJSNJSON->encode(array('connection' => false, 'version' => '', 'commercial'=>''));
			exit();
		}
		else
		{
			$parse = $objJSNJSON->decode($result);
			echo $objJSNJSON->encode(array('connection' => true, 'version' => @$parse->version, 'commercial' => @$parse->commercial));
			exit();
		}
	}
}
?>