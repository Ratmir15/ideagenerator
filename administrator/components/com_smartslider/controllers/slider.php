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

class SliderControllerSlider extends SliderController
{
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'apply',		'save' );
	}
	
	/*
	 List the Sliders
	*/
	function display(){
	  JRequest::setVar( 'view', 'sliders' );
    parent::display();
  }

	function edit(){
		JRequest::setVar( 'view', 'slider' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}
	
	function save()
	{
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_smartslider&controller=slider' );
		
		$model = $this->getModel('slider');
		if(!$model->store()){
      $link = 'index.php?option=com_smartslider&controller=slider&task=edit&id='. $model->row->id ;
      $this->setRedirect( $link, JText::_( $model->getError() ) );
      return;
    }

    $task = JRequest::getCmd( 'task' );
    
		switch ($task)
		{
			case 'apply':
				$link = 'index.php?option=com_smartslider&controller=slider&task=edit&id='. $model->row->id ;
				break;

			case 'save':
			default:
				$link = 'index.php?option=com_smartslider&controller=slider';
		    $this->getModel('slider')->checkin();
				break;
		}

		$this->setRedirect( $link, JText::_( 'Slider Saved' ) );
	}

	function remove(){
		$model = $this->getModel('slider');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Sliders Could not be Deleted' );
		} else {
			$msg = JText::_( 'Slider(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_smartslider&controller=slider', $msg );
	}

	function cancel(){
		$msg = JText::_( 'Operation Cancelled' );
		$this->getModel('slider')->checkin();
		$this->setRedirect( 'index.php?option=com_smartslider&controller=slider', $msg );
	}
	
	function publish(){
		$msg = JText::_( 'Slider(s) published' );
		$this->getModel('slider')->publish();
		$this->setRedirect( 'index.php?option=com_smartslider&controller=slider', $msg );
	}
	
	function unpublish(){
		$msg = JText::_( 'Slider(s) unpublished' );
		$this->getModel('slider')->publish(0);
		$this->setRedirect( 'index.php?option=com_smartslider&controller=slider', $msg );
	}
	
	function duplicate(){
    $db =& JFactory::getDBO();
    $cid = $_REQUEST['cid'];
    foreach($cid AS $id){
      if(($id = (int)$id) && is_numeric($id) && $id > 0){
        $row =& JTable::getInstance('slider', 'Table');
		    $row->load($id);
		    $row->id = '';
		    $row->name = 'Copy - '.$row->name;
		    $row->published = 0;
		    $row->store();
		    $sliderid = $row->id;
        $db->setQuery('SELECT id FROM #__offlajn_slide WHERE slider = "'.$id.'"');
        $c = $db->loadResultArray(0);
        foreach($c AS $i){
          $row =& JTable::getInstance('slide', 'Table');
  		    $row->load($i);
  		    $row->id = '';
  		    $row->slider = $sliderid;
  		    $row->store();
        }
      }
    }
		$this->setRedirect( 'index.php?option=com_smartslider&controller=slider', $msg );
  }
}