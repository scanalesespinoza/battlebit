<?php
defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Sample data field type
 *
 * @package		
 * @subpackage	
 * @since		1.6
 */
class JFormFieldJSNSampleData extends JFormField
{
	public $type = 'JSNSampleData';
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected function getInput() {
		
		$templateName 		= explode( DS, str_replace( array( '\elements', '/elements' ), '', dirname(__FILE__) ) );
		$templateName 		= $templateName [ count( $templateName ) - 1 ];
		$pathBase 			= str_replace( DS."templates".DS.$templateName.DS.'elements', "", dirname(__FILE__) );
		$templatePathOfBase = $pathBase . DS . 'templates' .  DS . $templateName;
		require_once JPATH_ROOT.DS.'templates'.DS.$templateName.DS.'includes'.DS.'lib'.DS.'jsn_utils.php';
		$jsnUtils 	  		= JSNUtils::getInstance();
		$doc 				= JFactory::getDocument();
		$tellMore	    	= '';
		$html 				= '';
		$result 			= $jsnUtils->getTemplateDetails( $templatePathOfBase, $templateName);
		$templateVersion 	= $result->version;	
		$templateName	  	= $result->name;
		$templateEdition 	= $result->edition;
		$templateName    	= str_replace('_', ' ', $templateName);				   		
		$templateEdition 	= strtolower($templateEdition);
		
		$html   = '<div class="jsn-sampledata">';
		$html	.= JText::_('INSTALL_SAMPLE_DATA_AS_SEEN_ON_DEMO_WEBSITE');
		$html	.= '<a class="link-button" href="../index.php?template='.strtolower($result->name).'&tmpl=jsn_installsampledata&template_style_id='.JRequest::getInt('id').'">'.JText::_('Install sample data').'</a>';
		$html  .= '</div>';
		
		return $html;
	}
} 