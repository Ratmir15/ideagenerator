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

class SliderModelSlide extends JModel{

  var $_table = '#__offlajn_slide';

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
	
	function &getDataWithSlider(){
    if(JRequest::getVar( 'task') == 'add'){
  		$query = 'SELECT name AS slidername, type, theme, params AS sliderparams '. 
               'FROM #__offlajn_slider '.
               'WHERE id = '.JRequest::getInt('sliderid');
  		
    }else{
  		$query = 'SELECT a.*, b.name AS slidername, b.type, b.theme, b.params AS sliderparams FROM '.$this->_table.' AS a '.
      				 'LEFT JOIN #__offlajn_slider AS b ON b.id = a.slider '.
               'WHERE a.id = '.$this->_id;
		}
		$this->_db->setQuery( $query );
		$rows = $this->_db->loadObject();
		return $rows;
	}
	
	function store(){	
    $data = JRequest::get( 'post' );
    $this->_id = $data['id']; 
		$row =& $this->getData();
		$this->row = &$row;
    $prev = $row->ordering;
    
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		$params = new JRegistry();
		$params->loadArray($data['param']);
		$row->params = $params->toString();
		
		if (!$row->bind($data['params'])) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		$row->content = $data['param']['paramscontenthtml'];
		$row->caption = $data['param']['paramscaptionhtml'];
		
		if($row->title == ''){
      $this->setError('Please fill out the title field!');
      $_SESSION['slideparams'] = get_object_vars($row);
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
		
		$db =& JFactory::getDBO();
		
		$cur = $row->ordering;
		$query='';
    if($prev > $cur){
      $query = 'UPDATE #__offlajn_slide '
  		. 'SET ordering = ordering + 1 '
      . 'WHERE id != "'.$row->id.'" AND ordering >= "'.($cur).'" AND ordering < "'.($prev).'"';
    }elseif($cur > $prev){
      $query = 'UPDATE #__offlajn_slide '
  		. 'SET ordering = ordering - 1 '
      . 'WHERE id != "'.$row->id.'" AND ordering <= "'.($cur).'" AND ordering > "'.($prev).'"';
    }
    if($query != ''){
  		$db->setQuery($query);
  		$db->query();
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