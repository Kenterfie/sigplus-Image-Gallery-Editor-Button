<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport( 'joomla.application.component.helper' );
jimport( 'joomla.html.parameter' );
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
        
        //var_dump(JRequest::getCmd( 'component' ));
		$component	= JComponentHelper::getComponent('com_sigpluseditorbutton');

		$doc = & JFactory::getDocument();
		$doc->setTitle( JText::_('Edit Preferences') );
        
		JHTML::_('behavior.mootools');
		JHTML::_('behavior.tooltip');
        $doc->addStyleSheet(JURI::base(true).'/components/com_sigpluseditorbutton/assets/css/sigplusEditorButton.css');
        $doc->addScript(JURI::base(true).'/components/com_sigpluseditorbutton/assets/js/jquery-1.6.4.min.js');
        $doc->addScript(JURI::base(true).'/components/com_sigpluseditorbutton/assets/js/jquery.filedrop.js');
		$doc->addScriptDeclaration('var $j = jQuery.noConflict();');
        
		$plugin = &JPluginHelper::getPlugin('content', 'sigplus');
		$pluginParams = new JParameter($plugin->params, JPATH_ROOT.'/plugins/content/sigplus.xml');
        
        //var_dump($pluginParams->toArray());
		
		$paramsArray = $pluginParams->toArray();
        //var_dump($paramsArray);
		$jsonArray = array();
		foreach($paramsArray as $key => $value) {
			$jsonArray[$key] = $value;
		}
        
		$doc->addScriptDeclaration("var sigplus_parameter = ".json_encode($jsonArray).";");
		$doc->addScript(JURI::base(true).'/components/com_sigpluseditorbutton/assets/js/sigplusEditorButton.js');
		$doc->addScriptDeclaration("
var joomla_base = '".JURI::root(true)."';
		
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
<a href="javascript:upload()">upload</a>
<br />
<?php
		}
	}
}