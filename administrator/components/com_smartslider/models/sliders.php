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

class SliderModelSliders extends JModel{

  var $_table = '#__offlajn_slider';

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
		$query = 'SELECT a.*, b.name AS uname, IF(ISNULL(c.slider),0,SUM(1)) AS count'
		. ' FROM '.$this->_table.' AS a'
		. ' LEFT JOIN #__users AS b ON a.created_by = b.id'
		. ' LEFT JOIN #__offlajn_slide AS c ON a.id = c.slider'
		. $where
		. ' GROUP BY a.id'
		. $orderby
		;
		$this->_db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		return array($pageNav, $this->_db->loadObjectList());
  }

}