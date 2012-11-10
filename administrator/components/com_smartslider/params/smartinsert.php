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

class JElementSmartInsert extends JElement {

	var	$_name = 'smartinsert';

	function fetchElement($name, $value, &$node, $control_name) {
        $document =& JFactory::getDocument();
        $document->addScript(JURI::root().'modules/mod_smartslider/js/dojo.js');
        $document->addScript(JURI::base().'components/com_smartslider/params/smartinsert.js');
        $h = '';
        $html = '';
        include(dirname(__FILE__).'/insert/sqltables.php');

    $h .= '<fieldset style="border-radius: 8px; display: block; float: left; width: 410px;">
          <legend><h3 style="color: #0B55C4;">Smart Insert</h3></legend>';
    
    $h.= '<table style="font-weight: bold;">
        <tr style="display: block;">
  
          <td>'.JText::_('Choose an option').'</td><td>';
      
    $h .= '<select id="dbselect"><option>'.JText::_('-Choose-').'</option>';
  
    foreach($sqlTables as $k => $tables) {
      if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.$tables[0])) {
        if($tables[1] == "Virtuemart Product details" && version_compare(JVERSION,'1.6.0','<=')) {
          $h .= '<option value="'.$k.'">'.$tables[1].'</option>';
        } else if ($tables[1] == "Virtuemart2 Product details") {
          $virtuemart_xml = &JFactory::getXMLParser('Simple');
          $virtuemart_xml->loadFile(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS."virtuemart.xml");
        
            if((int)$virtuemart_xml->document->version[0]->data() == 2) {
              $h .= '<option value="'.$k.'">'.$tables[1].'</option>';
            }
          } else if(false === strpos($tables[1], "Virtuemart")) {
              $h .= '<option value="'.$k.'">'.$tables[1].'</option>';
          }
      }     
    }

    $h .= '</select></td></tr><tr class="noshowfields">';
    $h .='<td>'. JText::_('Choose the field') .'</td><td><select name="fieldselect" id="fieldselect"></select></td></tr><tr class="noshowvalues"><td>'
        . JText::_('Choose the value') .'</td><td><select name="values" id="values"></select></td>
        </tr>
        <tr class="noshowlength">
          <td>'.JTEXT::_('Length: ').'</td><td><input type="text" name="length" id="length" /></td>
        </tr>
      </table>';
    $h .='</fieldset>';
  
    $document->addScriptDeclaration('dojo.addOnLoad(function(){ new SmartInsertConfigurator({ defaultinsert: dojo.byId("defaultinsert"), h: '.json_encode($h).', sqltables: ' .json_encode($sqlTables).', url: '.json_encode(JURI::base()).'      });   });    '); 
    return $html;
	}
}