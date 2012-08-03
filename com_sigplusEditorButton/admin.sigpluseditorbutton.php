<?php
// no direct access
defined('_JEXEC') or die;

require_once( JPATH_COMPONENT.DS.'helper.php' );
require_once (JPATH_COMPONENT.DS.'views'.DS.'uploadView.php');
require_once (JPATH_COMPONENT.DS.'views'.DS.'mainView.php');
require_once (JPATH_COMPONENT.DS.'views'.DS.'fileBrowserView.php');
require_once (JPATH_COMPONENT.DS.'views'.DS.'parameterEditView.php');
require_once (JPATH_COMPONENT.DS.'views'.DS.'captionEditView.php');

$task = JRequest::getVar('task');

$lang =& JFactory::getLanguage();
$lang->load('plg_editors-xtd_sigplus', JPATH_ADMINISTRATOR);

if ($task == 'view') {
	$view = new mainView();
	$view->display();
} else if($task == 'browse') {
	$view = new fileBrowserView();
	$view->display();
} else if($task == 'config') {
	$view = new parameterEditView();
	$view->display();
} else if($task == 'caption') {
	$view = new captionEditView();
	$view->display();
} else if($task == 'setcaption') {
	$helper = new sigplusEditorButtonHelper();
	$path = JRequest::getVar('path');
	$file = JRequest::getVar('file');
	$label = JRequest::getVar('label');
	$desc = JRequest::getVar('desc');
	
	$imageData = $helper->loadImageData($path);
	if(!is_array($imageData))
		$imageData = array();
	if(get_class($imageData[$file]) != 'ImageData')
		$imageData[$file] = new imageData();
	$imageData[$file]->label = $label;
	$imageData[$file]->desc = $desc;
	$helper->saveImageData($path, $imageData);
} else if($task == 'upload') {
    $view = new uploadView();
	$view->display();
} else {

}
?>