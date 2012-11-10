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

class JElementOrdering extends JElement{

	var	$_name = 'Ordering';

	function fetchElement($name, $value, &$node, $control_name){
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );
		
    $document =& JFactory::getDocument();
    $document->addScript(JURI::root().'modules/mod_smartslider/js/dojo.js');
    $document->addScript(JURI::base().'components/com_smartslider/params/ordering.js');
    $document->addScriptDeclaration('
      dojo.addOnLoad(function(){
        new Ordering({});
      });
    ');
    
    $sliderid = $this->_parent->row->slider;
    
    if(!property_exists($this->_parent->row, 'id'))
      $this->_parent->row->id = '';
    $id = $this->_parent->row->id;
    
    $db =& JFactory::getDBO();
    $db->setQuery('SET @rank=-1;');
    $db->query();
    $db->setQuery('UPDATE #__offlajn_slide SET ordering = (@rank:=@rank+1) WHERE slider = "'.$sliderid.'" ORDER BY ordering ASC');
    $db->query();
    $query = 'SELECT id, title, ordering, groupprev'
		. ' FROM #__offlajn_slide '
		. ' WHERE slider = "'.$sliderid.'"'
		. ' ORDER BY ordering';
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();

    $selected = '';
		$options = array ();
		$main = null;
		foreach ($rows as $row){
			$text = '';
		  if($row->groupprev == 0 || $main == null){
        $main = $row;
      }else if($row->groupprev == 1 && $main != null){
        $text.='— ';
      }
			$val = $row->ordering;
      if($row->id == $id){
        $selected = $val;
			  $text.= 'Current slide';
      }else{
			  $text.= $row->title;
      }
			
			$options[] = JHTML::_('select.option', $val, JText::_($text));
		}
		
		if($id == ''){
		  $c = count($rows);
		  $selected = $c;
      $options[] = JHTML::_('select.option', $selected, JText::_('Current slide'));
    }

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', $class.' size="'.count($options).'"', 'value', 'text', $selected, $control_name.$name);
	}
}
