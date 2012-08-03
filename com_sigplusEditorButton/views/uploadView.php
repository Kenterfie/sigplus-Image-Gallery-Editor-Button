<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pane' );

require_once( JPATH_COMPONENT.DS.'helper.php' );

/**
 * @package		Joomla
 * @subpackage	Config
 */
class uploadView extends JView {
    /**
	 * Display the view
	 */
	function display()
	{
        ?>
<div class="dropzone">
    <?php echo JText::_("Drag your image files here"); ?>
</div>
        <?php
    }
}