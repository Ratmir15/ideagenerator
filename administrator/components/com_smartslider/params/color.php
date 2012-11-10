<?php 
/*------------------------------------------------------------------------
# smartslider - Smart Slider
# ------------------------------------------------------------------------
# author    Roland Soos 
# copyright Copyright (C) 2011 Offlajn.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.offlajn.com
-------------------------------------------------------------------------*/
?>
<?php
defined('_JEXEC') or die('Restricted access');

class JElementColor extends JElement
{
  var $_moduleName = '';
  
	var	$_name = 'Color';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$size = 'size="12"';
    $value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES), ENT_QUOTES);
    
    $alpha = $node->attributes('alpha') == 1 ? true : false;
    $document =& JFactory::getDocument();
    $document->addScript(JURI::base().'components/com_smartslider/params/jpicker/jquery-1.4.4.min.js');
    $document->addScript(JURI::base().'components/com_smartslider/params/jpicker/jpicker-1.1.6.min.js');
    $document->addStyleSheet(JURI::base().'components/com_smartslider/params/jpicker/css/jPicker-1.1.6.min.css');
    if(version_compare(JVERSION,'1.6.0','>=')) {
      $document->addStyleDeclaration('.jPicker{float:left;}');
    }
    $GLOBALS['scripts'][] = 'jQuery(document).ready(function(){jQuery.fn.jPicker.defaults.images.clientPath="'.JURI::base().'components/com_smartslider/params/jpicker/images/";dojo.byId("'.$control_name.$name.'").alphaSupport='.($alpha ? 'true' : 'false').'; jQuery("#'.$control_name.$name.'").jPicker({window:{expandable: true,alphaSupport: '.($alpha ? 'true' : 'false').'}});});';
		return '<input style="padding: 2px 0;" type="text" name="'.$control_name.'['.$name.']" id="'.$control_name.$name.'" value="'.$value.'" class="color" '.$size.' />';
	}
}