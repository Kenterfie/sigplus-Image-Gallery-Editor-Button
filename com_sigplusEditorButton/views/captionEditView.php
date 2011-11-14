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
			<td><?php echo JText::_("Label:"); ?></td>
			<td><input type="text" id="imageLabel" value="<?php echo $iD->label; ?>" /></td>
		</tr>
		<tr>
			<td><?php echo JText::_("Description:") ?></td>
			<td>
				<textarea id="imageDesc" cols="30" rows="10"><?php echo $iD->desc; ?></textarea>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<button type="button" id="saveButton"  onclick="saveCaption();">
					<?php echo JText::_("Save") ?>
				</button>
				<button type="button" id="backButton"  onclick="back();">
					<?php echo JText::_("Back") ?>
				</button>
			</td>
		</tr>
	</tbody>
</table>
<?php
	}
}