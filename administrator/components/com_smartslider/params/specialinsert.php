<?php
/*------------------------------------------------------------------------
# smartslider - Smart Slider
# ------------------------------------------------------------------------
# author    Jeno Kovacs 
# copyright Copyright (C) 2011 Offlajn.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.offlajn.com
-------------------------------------------------------------------------*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

class JElementSpecialInsert extends JElement {

	var	$_name = 'specialinsert';

	function fetchElement($name, $value, &$node, $control_name) {
        $document =& JFactory::getDocument();
        $document->addScript(JURI::root().'modules/mod_smartslider/js/dojo.js');
        $document->addScript(JURI::base().'components/com_smartslider/params/specialinsert.js');
        
        $h = '';
        $html = '';
        include(dirname(__FILE__).'/insert/specialqueries.php');
        
    $h .= '<fieldset style="border-radius: 8px; float: left; width: 410px;">
            <legend><h3 style="color: #0B55C4;">Special Insert</h3></legend>
            <table border="0" style="font-weight: bold;"><tr>';
    $h .= '<td>'.JTEXT::_('Choose an option').'</td><td><select id="specialselect"><option>'.JText::_('-Choose-').'</option>';
    
    $forms = array();
    
    foreach($specialQueries as $k => $queries) {
      if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.$queries[0])) {
        if($queries[0] == "com_virtuemart") {
          $virtuemart_xml = &JFactory::getXMLParser('Simple');
          $virtuemart_xml->loadFile(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS."virtuemart.xml");
            if((int)$virtuemart_xml->document->version[0]->data() == (int)$queries[1]) {
              $h .= '<option value="'.$k.'">'. ucfirst(str_replace("_", " ", $k)) .'</option>';
              $params = new JParameter('', dirname(__FILE__).DS.'insert' .DS. $k . '.xml');  
              $forms[$k] = $params->render('param');
            }
        } else {
          $h .= '<option value="'.$k.'">'. ucfirst(str_replace("_", " ", $k)) .'</option>';
          $params = new JParameter('', dirname(__FILE__).DS.'insert' .DS. $k . '.xml');  
          $forms[$k] = $params->render('param');
        }
      }
    }
    
    $h .= '</select></td>
          </tr></table>  
        <div class="specialinsertform"></div> 
        
      </fieldset>';

   $document->addScriptDeclaration('dojo.addOnLoad(function(){ new SpecialInsertConfigurator({ specialinsert: dojo.byId("specialinsert"), url: '.json_encode(JURI::base()).', h: '.json_encode($h).', specialqueries: '.json_encode($specialQueries).', forms: ' .json_encode($forms). ' }); }); ');
  
  // $html .= '<a href="#" id="specialinsert">Special Insert</a>';
	 return $html;
	}
}