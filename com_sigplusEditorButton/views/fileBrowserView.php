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
class fileBrowserView extends JView
{
	/**
	 * Display the view
	 */
	function display()
	{
		JRequest::setVar('tmpl', 'component'); //force the component template
		$helper = new sigplusEditorButtonHelper();
		
		$path = JRequest::getVar('path');
		$folders = $helper->getFolders($path);
		$files = $helper->getFiles($path);
		?>
		<table class="adminlist" id="">
		<thead>
			<tr>
				<th><?php echo JText::_("Type"); ?></th>
				<th><?php echo JText::_("Name"); ?></th>
				<th><?php echo JText::_("Preview"); ?></th>
				<th><?php echo JText::_("Action"); ?></th>
			</tr>
		</thead>
		<tbody id="fileTable">
		<?php
		
		if(strripos($path, '/') !== false) {
		?>
		<tr class="back">
			<td align="center"><img src="<?php echo JURI::base(true).'/components/com_sigpluseditorbutton/assets/images/back.png'; ?>" /></td>
			<td><a href="javascript:browseFolder(null);" /><?php echo JText::_("Folder Up"); ?></a></td>
			<td></td>
			<td></td>
		</tr>
		<?php
		}
		foreach($folders as $folder) {
		?>
		<tr class="folder" style="height: 90px;">
			<td align="center"><img src="<?php echo JURI::base(true).'/components/com_sigpluseditorbutton/assets/images/folder_picture.png'; ?>" /></td>
			<td><a href="javascript:browseFolder('<?php echo $folder; ?>');" /><?php echo $folder; ?></a></td>
			<td></td>
			<td><a href="javascript:setFile('<?php echo $folder; ?>');pasteTag();" /><img src="<?php echo JURI::base(false).'/components/com_sigpluseditorbutton/assets/images/picture_add.png'; ?>" title="<?php echo JText::_("Paste Gallery"); ?>" /></a>&nbsp;<a href="javascript:setFile('<?php echo $folder; ?>');openConfig();" /><img src="<?php echo JURI::base(false).'/components/com_sigpluseditorbutton/assets/images/picture_edit.png'; ?>" title="<?php echo JText::_("Set Parameter"); ?>" /></a></td>
		</tr>
		<?php
		}
		?>
		<?php
		foreach($files as $file) {
		?>
		<tr class="image" style="height: 90px;">
			<td align="center"><img src="<?php echo JURI::base(true).'/components/com_sigpluseditorbutton/assets/images/picture.png'; ?>" /></td>
			<td><?php echo $file; ?></td>
			<td align="center"><img src="<?php echo $helper->getBaseFolder(false).$path.DS.'thumbs'.DS.$file; ?>" /></td>
			<td><a href="javascript:setFile('<?php echo $file; ?>');pasteTag();" /><img src="<?php echo JURI::base(true).'/components/com_sigpluseditorbutton/assets/images/picture_add.png'; ?>" title="<?php echo JText::_("Paste Image"); ?>" /></a>&nbsp;<a href="javascript:setFile('<?php echo $file; ?>');openCaption();" /><img src="<?php echo JURI::base(true).'/components/com_sigpluseditorbutton/assets/images/picture_caption.png'; ?>" title="<?php echo JText::_("Change Label / Description"); ?>" /></a>&nbsp;<a href="javascript:setFile('<?php echo $file; ?>');openConfig();" /><img src="<?php echo JURI::base(false).'/components/com_sigpluseditorbutton/assets/images/picture_edit.png'; ?>" title="<?php echo JText::_("Set Parameter"); ?>" /></a></td>
		</tr>
		<?php
		}
		?>
		</tbody>
		</table>
		<?php
	}
}