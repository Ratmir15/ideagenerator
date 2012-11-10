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

JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_smartslider'.DS.'tables');

if(version_compare(JVERSION,'1.6.0','<')){
  class JFormField extends JElement{};
}

class JFormFieldSliders extends JFormField
{

	var	$_name = 'Sliders';

	function fetchElement($name, $value, &$node, $control_name){
    $value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES), ENT_QUOTES);
    $options = array ();
    $db =& JFactory::getDBO();
   /* $query = 'SELECT a.id, a.name, a.type , IF(ISNULL(c.slider),0,SUM(1)) AS count'
		. ' FROM #__offlajn_slider AS a'
		. ' LEFT JOIN #__offlajn_slide AS c ON a.id = c.slider'
		. ' WHERE a.published = 1 AND c.published = 1'
		. ' GROUP BY a.id'
		. ' ORDER BY a.name'
		;*/
		$query = 'SELECT a.id, a.name, a.type '
		. ' FROM #__offlajn_slider AS a'
		. ' WHERE a.published = 1 '
		. ' GROUP BY a.id'
		. ' ORDER BY a.name'
		;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		foreach($rows AS $row){
      $options[] = JHTML::_('select.option', $row->id, $row->name.' ['.$row->type.'] ');
    }
    if(isset($_GET['slider']) && $_GET['slider'] > 0){
      $value = $_GET['slider'];
    }
		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value, $control_name.$name);
	}
	
  function getInput(){
    $this->value = htmlspecialchars(html_entity_decode($this->value, ENT_QUOTES), ENT_QUOTES);

    $options = array ();
    $db =& JFactory::getDBO();
    $query = 'SELECT a.id, a.name, a.type , IF(ISNULL(c.slider),0,SUM(1)) AS count'
		. ' FROM #__offlajn_slider AS a'
		. ' LEFT JOIN #__offlajn_slide AS c ON a.id = c.slider'
		. ' WHERE a.published = 1 AND c.published = 1'
		. ' GROUP BY a.id'
		. ' ORDER BY a.name'
		;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		foreach($rows AS $row){
      $options[] = JHTML::_('select.option', $row->id, $row->name.' ['.$row->type.'] - '. $row->count .' slides');
    }
    if(isset($_GET['slider']) && $_GET['slider'] > 0){
      $this->value = $_GET['slider'];
    }
		return JHTML::_('select.genericlist',  $options, $this->name, 'class="inputbox"', 'value', 'text', $this->value);
	}
}

if(version_compare(JVERSION,'1.6.0','<')){
  class JElementSliders extends JFormFieldSliders{};
}