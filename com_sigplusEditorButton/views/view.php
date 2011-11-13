<?php
/**
 * @version		$Id: view.php 14401 2010-01-26 14:10:00Z louis $
 * @package		Joomla
 * @subpackage	Config
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pane' );

require_once( JPATH_COMPONENT.DS.'helper.php' );

/**
 * @package		Joomla
 * @subpackage	Config
 */
class sigplusEditorButtonViewComponent extends JView
{
	/**
	 * Display the view
	 */
	function display()
	{
		JRequest::setVar('tmpl', 'component'); //force the component template
		//$model		= &$this->getModel();
		//$params		= &$model->getParams();
		$component	= JComponentHelper::getComponent(JRequest::getCmd( 'component' ));
		
		$helper = new sigplusEditorButtonHelper();

		$doc = & JFactory::getDocument();
		$doc->setTitle( JText::_('Edit Preferences') );
		JHTML::_('behavior.mootools');
		JHTML::_('behavior.tooltip');
		
		$plugin = &JPluginHelper::getPlugin('content', 'sigplus');
		$pluginParams = new JParameter($plugin->params, JPATH_ROOT.'/plugins/content/sigplus.xml');
		
		$paramsArray = $pluginParams->renderToArray();
		$jsonArray = array();
		foreach($paramsArray as &$param) {
			$jsonArray[$param[5]] = $param[4];
		}
		$doc->addScriptDeclaration("var sigplus_parameter = ".json_encode($jsonArray).";");
		
		$doc->addScript(JURI::base(true).'/components/com_sigpluseditorbutton/assets/js/sigplusEditorButton.js');
		
		$doc->addScriptDeclaration("
var joomla_base = '".JURI::root(false)."';
		
window.addEvent('domready', function() {
	loadFiles(path[0]);
});
		");
		
		if(!$helper->isSIGPLUSEnabled()) {
			JError::raiseWarning(100, JText::_("sigplus plugin is not enabled"));
		} else {
		?>
<fieldset>
<div style="float: right">
	<button type="button" onclick="closeWindow();">
		<?php echo JText::_( 'Cancel' );?>
	</button>
</div>

<div class="configuration" >
	<?php echo JText::_('Select Folder / Image') ?>
</div>
</fieldset>
<div id="formHolder">
</div>
<br />
<?php
		}
	}
}