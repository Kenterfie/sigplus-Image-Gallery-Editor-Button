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
class captionEditView extends JView
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
		
		$path = JRequest::getVar('path');
		$file = JRequest::getVar('file');
		
		$imageData = $helper->loadImageData($path);
		if(!is_array($imageData))
			$imageData = array();
		if(get_class($imageData[$file]) != 'ImageData')
			$imageData[$file] = new imageData();
		$iD = $imageData[$file];
?>
<table class="adminlist">
	<thead>

	</thead>
	<tbody>
		<tr>
			<td><?php echo JText::_("SEB_IMAGE_LABEL"); ?>:</td>
			<td><input type="text" id="imageLabel" value="<?php echo $iD->label; ?>" /></td>
		</tr>
		<tr>
			<td><?php echo JText::_("SEB_IMAGE_DESCRIPTION") ?>:</td>
			<td>
				<textarea id="imageDesc" cols="30" rows="10"><?php echo $iD->desc; ?></textarea>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<button type="button" id="saveButton"  onclick="saveCaption();">
					<?php echo JText::_("SEB_BUTTON_SAVE") ?>
				</button>
				<button type="button" id="backButton"  onclick="back();">
					<?php echo JText::_("SEB_BUTTON_BACK") ?>
				</button>
			</td>
		</tr>
	</tbody>
</table>
<?php
	}
}