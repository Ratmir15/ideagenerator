<?php 
/*------------------------------------------------------------------------
# mod_vm_accordion - Accordion Menu for Virtuemart 
# ------------------------------------------------------------------------
# author    Roland Soos 
# copyright Copyright (C) 2011 Offlajn.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.offlajn.com
-------------------------------------------------------------------------*/
?>
<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart')) {
  $virtuemart_xml = &JFactory::getXMLParser('Simple');
  $virtuemart_xml->loadFile(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS."virtuemart.xml");
  if((int)$virtuemart_xml->document->version[0]->data() == 2) {

if (!class_exists( 'VmConfig' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'config.php');
$config= VmConfig::loadConfig();
if(!class_exists('TableCategories')) require(JPATH_VM_ADMINISTRATOR.DS.'tables'.DS.'categories.php');
if (!class_exists( 'VirtueMartModelCategory' )) require(JPATH_VM_ADMINISTRATOR.DS.'models'.DS.'category.php');
require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_smartslider' . DS . 'params' . DS . 'library' . DS . 'fakeElementBase.php');
require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_smartslider' . DS . 'params' . DS . 'vm2Category.class.php');
class JElementVM2Categories extends JOfflajnFakeElementBase{

  var $_name = 'VM2categories';
  
  function universalFetchElement($name, $value, &$node){
    if($value == NULL){
      $value = array();
    }elseif(!is_array($value)){
      $value = array($value);
    }
    $values = array_flip($value);
    array_walk($values, 'ofSetOne');

    $categoryModel = new fixedVirtueMartModelCategory();
    $cats = $categoryModel->GetTreeCat();

    $multiple = 1 ? "multiple=\"multiple\"" : "";
    ob_start();
		echo "<select class=\"inputbox\" size=\"10\" $multiple name=\"".$name."[]\" id=\"".$this->generateId($name)."\">\n";
		if( 1 ) {
			$selected = (@$values[0] == "1") ? "selected=\"selected\"" : "";
			echo "<option ".$selected." value=\"0\">Top Level</option>\n";
		}
		foreach($cats AS $cat){
  		$selected = '';
  		if( $selected == "" && @$values[$cat->id] == "1") {
  		  $selected = "selected=\"selected\"";
  		}
      echo "<option $selected value=\"$cat->id\">\n";
  		for ($i=0;$i<$cat->level;$i++) {
  			echo "&#151;";
  		}
  		echo "|$cat->level|";
  		echo "&nbsp;" . $cat->name . "</option>";
		}
		echo "</select>\n";
    return ob_get_clean();
  }
}

if(!function_exists('ofSetOne')){
  function ofSetOne(&$item, $key){
      $item = 1;
  }
}

if(version_compare(JVERSION,'1.6.0','ge')) {
  class JFormFieldVM2Categories extends JElementVM2Categories {}
}

}

}