<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JPATH_COMPONENT.DS.'helper.php' );
require_once (JPATH_COMPONENT.DS.'views'.DS.'view.php');

$helper = new sigplusEditorButtonHelper();
$task = JRequest::getVar('task');

if ($task == 'view') {
	$view = new sigplusEditorButtonViewComponent();
	$view->display();
} else if($task == 'browse') {
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
		<td align="center"><img src="<?php echo $helper->getBaseFolder(false).$path.DS.'_thumbs'.DS.$file; ?>" /></td>
		<td><a href="javascript:setFile('<?php echo $file; ?>');pasteTag();" /><img src="<?php echo JURI::base(true).'/components/com_sigpluseditorbutton/assets/images/picture_add.png'; ?>" title="<?php echo JText::_("Paste Image"); ?>" /></a>&nbsp;<a href="javascript:setFile('<?php echo $file; ?>');openConfig();" /><img src="<?php echo JURI::base(false).'/components/com_sigpluseditorbutton/assets/images/picture_edit.png'; ?>" title="<?php echo JText::_("Set Parameter"); ?>" /></a></td>
	</tr>
	<?php
	}
	?>
	</tbody>
	</table>
	<?php
} else if($task == 'config') {
	$lang = JFactory::getLanguage();
	$lang->load('plg_content_sigplus', JPATH_ADMINISTRATOR);

	$plugin = &JPluginHelper::getPlugin('content', 'sigplus');
	//echo JPATH_ROOT.'/plugins/content/sigplus.xml';
	$pluginParams = new JParameter($plugin->params, JPATH_ROOT.'/plugins/content/sigplus.xml');
	
	echo $pluginParams->render();
	echo '<br /><button type="button" onclick="pasteTag();" style="float: right;">'.JText::_("Paste").'</button>';
} else {

}
?>