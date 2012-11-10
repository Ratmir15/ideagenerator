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

class SliderModelSlides extends JModel{

  var $_table = '#__offlajn_slide';

	function __construct(){
		parent::__construct();
	}
	
	function getPaginator($where, $orderby, $limitstart, $limit){
		$query = 'SELECT COUNT(*)'
		. ' FROM '.$this->_table.' AS a'
		. $where
		;
		$this->_db->setQuery( $query );
		$total = $this->_db->loadResult();
		
    jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );
		$query = 'SELECT a.*, b.name AS uname, c.name AS slidername'
		. ' FROM '.$this->_table.' AS a'
		. ' LEFT JOIN #__users AS b ON a.created_by = b.id'
		. ' LEFT JOIN #__offlajn_slider AS c ON a.slider = c.id'
		. $where
		. $orderby
		;
		$this->_db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		return array($pageNav, $this->_db->loadObjectList());
  }
  
  function reorder( $where='' ){
    return true;
  }
  
  function saveorder($pks = null, $order = null){}
  
	function order($direction){
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Initialize variables
		$db		= & JFactory::getDBO();
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );

		if (isset($cid[0])){
			$row = & $this->getTable('slide');
			$row->load( (int) $cid[0] );
			$row->move($direction, 'slider = ' . (int) $row->slider );
		}
	}
  

}