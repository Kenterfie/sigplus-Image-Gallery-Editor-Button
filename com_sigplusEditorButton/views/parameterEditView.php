<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pane' );
jimport( 'joomla.form.form' );
jimport( 'joomla.html.parameter' );
jimport( 'joomla.registry.registry' );

require_once( JPATH_COMPONENT.DS.'helper.php' );

/**
 * @package		Joomla
 * @subpackage	Config
 */
class parameterEditView extends JView
{
	/**
	 * Display the view
	 */
	function display()
	{
		$lang =& JFactory::getLanguage();
		$lang->load('plg_editors-xtd_sigplus', JPATH_ADMINISTRATOR);
        $lang->load('plg_content_sigplus', JPATH_ADMINISTRATOR);
        $lang->load('com_plugins', JPATH_ADMINISTRATOR);
	
		JRequest::setVar('tmpl', 'component'); //force the component template
		$helper = new sigplusEditorButtonHelper();
		
		//$lang = JFactory::getLanguage();
		

		$plugin = &JPluginHelper::getPlugin('content', 'sigplus');
		//echo JPATH_ROOT.'/plugins/content/sigplus.xml';
        //$plugin = & JPluginHelper::getPlugin('content', 'emailcloak');
        //$pluginParams = new JParameter($plugin->params);
        //$mode = $pluginParams->def('mode', 1);
		//$pluginParams = new JRegistry();
        //$pluginParams->loadString($plugin->params);
        //echo $pluginParams;
        //var_dump($pluginParams->toArray());
        //$form = new JForm('myform', array('control'=>'jform'));
        //$form->bind($pluginParams);
        //$form->bind($pluginParams);
		
        //$form = &JForm::getInstance('myform', $plugin->params);
        //var_dump($form);
        
        $xmlfile = JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'sigplus'.DS.'sigplus.xml';
        $htmlfile = JPATH_ROOT.DS.'media'.DS.'sigplus'.DS.'editor'.DS.'button.'.$lang->getTag().'.html';
        
        if (file_exists($xmlfile)) {
            // load configuration XML file
            $form = new JForm('sigplus');
            $params = new JRegistry($plugin->params);
            //var_dump($params->toArray());
            $data = new StdClass();
	     $data->params = $params->toArray();
	
            $form->loadFile($xmlfile, true, '/extension/config/fields');
	     if(!$form->bind($data))
	         echo("error");
	     
            $fieldSets = $form->getFieldsets('params');
            
	     //echo '<br /><br />';
            //var_dump($fieldSets);

            // get permissible gallery parameters
            $vars = get_class_vars('SIGPlusGalleryParameters');
	     //echo '<br /><br />';
            //var_dump($vars);

            //ob_start();
            //print '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
            //print '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . $lang->getTag() . '" lang="' . $lang->getTag() . '">';
            //print '<head>';
            //print '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
            //print '<link rel="stylesheet" href="button.css" type="text/css" />';
            //print '<script type="text/javascript" src="../../system/js/mootools-core.js"></script>';
            //print '<script type="text/javascript" src="button.js"></script>';
            //print '</head>';
            //print '<body>';
            print '<form id="sigplus-settings-form">';
            //print '<button id="sigplus-settings-submit" type="button">' . JText::_('JSUBMIT') . '</button>';
            foreach ($fieldSets as $name => $fieldSet) {
                $fields = $form->getFieldset($name);
                //var_dump($fields);

                /*$hasfields = false;
                foreach ($fields as $field) {
                    if (isset($vars[$field->fieldname])) {
                        $hasfields = true;
                        break;
                    }
                }
                if (!$hasfields) {
                    continue;
                }*/
                
                if($name != "basic")
                    continue;

                // field group title
                $label = !empty($fieldSet->label) ? $fieldSet->label : 'COM_PLUGINS_' . strtoupper($name) . '_FIELDSET_LABEL';
                print '<h3>' . JText::_($label) . '</h3>';
                if (isset($fieldSet->description) && trim($fieldSet->description)) {
                    print '<p class="tip">' . $this->escape(JText::_($fieldSet->description)) . '</p>';
                }

                // field group elements
                print '<fieldset class="panelform">';
                $hidden_fields = '';
                print '<ul>';
                foreach ($fields as $field) {
                    /*if (!isset($vars[$field->fieldname])) {
                        continue;
                    }*/
                    if (!$field->hidden) {
                        print '<li class="formelm">';
                        print $field->label;
                        print $field->input;
                        print '</li>';
                    } else {
                        $hidden_fields.= $field->input;
                    }
                }
                print '</ul>';
                print $hidden_fields;
                print '</fieldset>';
            }
            print '</form>';
            //print '</body>';
            //print '</html>';
            //$html = ob_get_clean();
            //echo $html;
            /*if (file_put_contents($htmlfile, $html) === false) {
                throw new SIGPlusAccessException($htmlfile);
            }*/
        }
    }

}
?>
