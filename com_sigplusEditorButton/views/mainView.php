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
class mainView extends JView
{
	/**
	 * Display the view
	 */
	function display()
	{
		$lang =& JFactory::getLanguage();
		$lang->load('plg_editors-xtd_sigplus', JPATH_ADMINISTRATOR);
	
		JRequest::setVar('tmpl', 'component'); //force the component template
		$helper = new sigplusEditorButtonHelper();
		$component	= JComponentHelper::getComponent(JRequest::getCmd( 'component' ));

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
		<?php echo JText::_( 'SEB_BUTTON_CANCEL' );?>
	</button>
</div>
<div style="float: right">
	<button type="button" id="pasteButton" style="display: none;"  onclick="pasteTag();">
			<?php echo JText::_("SEB_BUTTON_PASTE") ?>
	</button>
</div>

<div class="configuration" >
	<?php echo JText::_('sigplus Image Gallery Editor Button') ?>
</div>
</fieldset>
<div id="messageHolder">
</div>
<div id="formHolder">
</div>
<br />
<?php
		}
	}
}