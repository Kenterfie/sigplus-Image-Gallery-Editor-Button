<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pane' );

require_once( JPATH_COMPONENT.DS.'helper.php' );

/**
 * @package		Joomla
 * @subpackage	Config
 */
class parameterEditView extends JView
{
	/**
	 * Display the view
	 */
	function display()
	{
		JRequest::setVar('tmpl', 'component'); //force the component template
		$helper = new sigplusEditorButtonHelper();
		
		$lang = JFactory::getLanguage();
		$lang->load('plg_content_sigplus', JPATH_ADMINISTRATOR);

		$plugin = &JPluginHelper::getPlugin('content', 'sigplus');
		//echo JPATH_ROOT.'/plugins/content/sigplus.xml';
		$pluginParams = new JParameter($plugin->params, JPATH_ROOT.'/plugins/content/sigplus.xml');
		
		echo $pluginParams->render();
	}
}