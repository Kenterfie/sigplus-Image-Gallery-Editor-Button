<?php
// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.language.language' );

class plgButtonsigplusEditorButton extends JPlugin
{
	public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }
	
	function onDisplay($name) {
		$lang =& JFactory::getLanguage();
		$lang->load('plg_editors-xtd_sigplus', JPATH_ADMINISTRATOR);
		
		$doc =& JFactory::getDocument();
		$doc->addStyleSheet(JURI::base(true).'/components/com_sigpluseditorbutton/assets/css/sigplusEditorButton.css');
		$js = "function insertEditorClickCallback(message) {
		           jInsertEditorText(message, '".$name."' );
		       }";
  		$doc->addScriptDeclaration($js);
	
		$button = new JObject();
		$button->set('modal', true);
		$button->set('text', JText::_('SEB_ADD_FOLDER_OR_IMAGE'));
		$button->set('name', 'sigplus_button'); // we dont need a spezial css style class
		//$button->set('name', 'blank'); // we dont need a spezial css style class
		$button->set('options', "{handler: 'iframe', size: {x: 600, y: 400}}");
		$link = "index.php?option=com_sigpluseditorbutton&amp;task=view&amp;tmpl=component";
		$button->set('link', $link);
		$button->set('class', 'modal');
		
		return $button;
	}
}

?>