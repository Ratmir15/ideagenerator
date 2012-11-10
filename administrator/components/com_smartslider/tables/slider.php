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

class TableSlider extends JTable{

	var $id = null;
	
	var $name = '';
	
	var $type = '';
	
	var $theme = '';
	
	var $published = 1;
	
	var $created = null;
	
  var $created_by = 0;
	
  var $created_by_alias = '';
	
  var $modified = null;
	
  var $modified_by = 0;
	
  var $checked_out = 0;
	
  var $checked_out_time = null;
		
  var $publish_up = null;
	
  var $publish_down = null;
	
  var $params = '';
	
  var $access = 0;
	
	function TableSlider(& $db){
		parent::__construct('#__offlajn_slider', 'id', $db);
	  $user =& JFactory::getUser();
    $this->created_by = $user->get('id');
    $this->created_by_alias = $user->get('name') ;
	}
	
	function check(){
	  $user =& JFactory::getUser();
    if($this->id <= 0){
      $this->created = date("Y-m-d H:i:s");
      $this->created_by = $user->get('id');
      $this->created_by_alias = $user->get('name') ;
    }else{
      $this->modified = date("Y-m-d H:i:s");
      $this->modified_by = $user->get('id');
    }
    return parent::check();
  }
}

class sliderTableSlider extends TableSlider{

}