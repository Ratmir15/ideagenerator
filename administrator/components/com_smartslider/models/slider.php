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

jimport('joomla.application.component.model');

class SliderModelSlider extends JModel{

  var $_table = '#__offlajn_slider';

	function __construct(){
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData(){
		$row =& $this->getTable();
		$row->load($this->_id);
		return $row;
	}
	
  function store(){	
    $data = JRequest::get( 'post' );
	  $this->_id = $data['id']; 
		$row =& $this->getData();
		$this->row = &$row;

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		$params = new JRegistry();
		$params->loadArray($data['params']);

		$row->params = $params->toString();
		
		if (!$row->bind($data['params'])) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if($row->name == ''){
      $this->setError('Please fill out the name field!');
      $_SESSION['sliderparams'] = get_object_vars($row);
			return false;
    }

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}
		$id = $row->id;
		
		
		
		
    $db =& JFactory::getDBO();
    if($data['params']['addslides'] > 0 && $data['params']['generator']== "") {
      for($x=0; $x < $data['params']['addslides']; $x++){
        $srow =& JTable::getInstance('slide', 'Table');
        $srow->title = 'Slide '.($x+1);
        $srow->ordering = $x;
        $srow->slider = $id;
        $srow->store();
      }
    }
   
    //SlideGenerator
    $params = new JParameter('');
		$params->loadArray($data['params']);
    if($data['params']['generator'] != "" && $data['params']['generatorslidegenerate']== 1) {
      $gen =$data['params']['generator'];
      require_once( JPATH_SITE.DS.'modules/mod_smartslider/generators'.DS.$gen.'.php');
      $gen.='Parser';
      $tp = new $gen($params);
      $slidearray = $tp->makeSlides();
      //delete the the previously generated slides
      $db =& JFactory::getDBO();
      $db->setQuery('DELETE FROM #__offlajn_slide WHERE slider = '.(int) $id);
      $db->query();
      //make the slides
      for($i=0;$i<count($slidearray);$i++) {
        $ssrow =& JTable::getInstance('slide', 'Table');
        $ssrow->title = $slidearray[$i]->title;
        $ssrow->content = $slidearray[$i]->content;
        $ssrow->caption = $slidearray[$i]->caption;
        $ssrow->groupprev = $slidearray[$i]->groupprev;
        $ssrow->ordering = $i;
        $ssrow->slider = $id;
        $ssrow->params = $tp->makeParams($i);
        $ssrow->store();
      }
    }
    
		return true;
	}
	
	function delete(){
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}else{
          $db =& JFactory::getDBO();
          $db->setQuery('DELETE FROM #__offlajn_slide WHERE slider = "'.$cid.'"');
          $db->query();
        }
			}
		}
		return true;
	}
	
	function checkin(){
    $row =& $this->getTable();
		return $row->checkin(JRequest::getInt('id'));
  }
  
  function publish($publish = 1){
  	$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();
		$user =& JFactory::getUser();
		$row->publish($cids, $publish, $user->get('id'));
		return true;
  }
	

}