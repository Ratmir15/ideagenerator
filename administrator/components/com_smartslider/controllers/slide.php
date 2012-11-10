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
jimport('joomla.filesystem.file');

class SliderControllerSlide extends SliderController
{
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'apply', 'save' );
		$this->registerTask( 'save2new', 'save' );
	}
	
	/*
	 List the Sliders
	*/
	function display(){
	  JRequest::setVar( 'view', 'slides' );
    parent::display();
  }

	function add(){
		$sliderid = JRequest::getInt('sliderid');
		if(!$sliderid || $sliderid < 1){
		  $msg = JText::_( 'Error: You must specify the slider first. Use the "Add new Slide" button.' );
      $this->setRedirect( 'index.php?option=com_smartslider&controller=slider', $msg );
      return;
    }
		JRequest::setVar('sliderid', $sliderid);
		$this->edit();
	}
	
	function edit(){
		JRequest::setVar( 'view', 'slide' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}
	
	function save(){
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_smartslider&controller=slide' );
		
		$model = $this->getModel('slide');
		if(!$model->store()){
		  $link = '';
		  if(JRequest::getCmd( 'prevtask' ) == 'edit'){
        $link = 'index.php?option=com_smartslider&controller=slide&task=edit&id='. $model->row->id ;
      }else{
        $link = 'index.php?option=com_smartslider&controller=slide&task=add&sliderid='. $model->row->slider;
      }
      $this->setRedirect( $link, JText::_( $model->getError() ) );
      return;
    }

    $task = JRequest::getCmd( 'task' );
    
		switch ($task)
		{
			case 'apply':
				$link = 'index.php?option=com_smartslider&controller=slide&task=edit&id='. $model->row->id ;
				break;
				
			case 'save2new':
				$link = 'index.php?option=com_smartslider&controller=slide&task=add&sliderid='. $model->row->slider ;
				break;

			case 'save':
			default:
				$link = 'index.php?option=com_smartslider&controller=slide';
		    $this->getModel('slider')->checkin();
				break;
		}

		$this->setRedirect( $link, JText::_( 'Slide Saved' ) );
	}

	function remove(){
		$model = $this->getModel('slide');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Slide(s) Could not be Deleted' );
		} else {
			$msg = JText::_( 'Slide(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_smartslider&controller=slide', $msg );
	}

	function cancel(){
		$msg = JText::_( 'Operation Cancelled' );
		$this->getModel('slide')->checkin();
		$this->setRedirect( 'index.php?option=com_smartslider&controller=slide', $msg );
	}
	
	function publish(){
		$msg = JText::_( 'Slide(s) published' );
		$this->getModel('slide')->publish();
		$this->setRedirect( 'index.php?option=com_smartslider&controller=slide', $msg );
	}
	
	function unpublish(){
		$msg = JText::_( 'Slide(s) unpublished' );
		$this->getModel('slide')->publish(0);
		$this->setRedirect( 'index.php?option=com_smartslider&controller=slide', $msg );
	}
	
	function saveOrder(){
    JRequest::checkToken() or jexit( 'Invalid Token' );
    
    $cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$order		= JRequest::getVar( 'order', array (0), 'post', 'array' );

    JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));
		
		$this->getModel('slides')->saveorder($cid, $order);
		$msg = JText::_( 'Slides reordered' );
		$this->setRedirect( 'index.php?option=com_smartslider&controller=slide', $msg );
	}
	
  function orderdown(){
    $this->getModel('slides')->order(1);
		$msg = JText::_( 'Slides reordered' );
		$this->setRedirect( 'index.php?option=com_smartslider&controller=slide', $msg );
  }
  
  function orderup(){
    $this->getModel('slides')->order(-1);
		$msg = JText::_( 'Slides reordered' );
		$this->setRedirect( 'index.php?option=com_smartslider&controller=slide', $msg );
  }
  
  function upload(){
    $dir = JPATH_SITE. DS . 'images' . DS . 'slider' . DS;
    if(!is_dir($dir)){
      mkdir($dir);
    }
    $img = $_FILES['filename'];
    $img['name'] = strtolower($img['name']);
		$img['name'] = preg_replace('/\s/', '_', $img['name']);
    $s = getimagesize($img['tmp_name']);
    if($s[0] > 0 && in_array(JFile::getExt($img['name']), array('jpg', 'jpeg', 'png', 'gif')) ){

      $newName = JFile::stripExt($img['name']).time().'.'.JFile::getExt($img['name']);
      if(!JFile::upload($img['tmp_name'], $dir.$newName) ){
        echo JText::_( 'Something wrong with the image upload!' );
      }else{
        echo JText::_( 'Upload was successful!' );
        echo "{".JURI::root().'images/slider/'.$newName."}";
      }
    }else{
      echo JText::_( 'Please upload a supported image!' );
    }
  }
	
	function duplicate(){
    $db =& JFactory::getDBO();
    $cid = $_REQUEST['cid'];
    foreach($cid AS $id){
      if(($id = (int)$id) && is_numeric($id) && $id > 0){
        $row =& JTable::getInstance('slide', 'Table');
		    $row->load($id);
		    $row->id = '';
		    $row->title = 'Copy - '.$row->title;
		    $row->published = 0;
		    $row->store();
      }
    }
		$this->setRedirect( 'index.php?option=com_smartslider&controller=slide', $msg );
  }
  
  function ajax() {
    $db = & JFactory::getDBO();    
    //include the php file which contains the allowed tables and fields
    include( JPATH_COMPONENT. DS . 'params' . DS . 'insert' . DS . 'sqltables.php' );
    //filter the $_POST variables
    $table = JRequest::getCmd( 'table' );
    $field = JRequest::getCmd( 'field' );
    //check that the parameters are correct
    if( isset($sqlTables[$table][$field]) ) {
      $query = 
        ' SELECT ' .$db->nameQuote( $sqlTables[$table][2] ) . ','
                    .$db->nameQuote( $sqlTables[$table][$field] ) . ' 
              FROM ' . $db->nameQuote( '#__' . $table );
      $db->setQuery($query);
       $result = $db->loadRowList();
    }
    echo json_encode( $result );
  }
  
  function replaceSlideContent() {
    JPluginHelper::importPlugin( 'smartslider' );
    $dispatcher =& JDispatcher::getInstance();
    $slider = stripslashes($_POST['slider']);
    $GLOBALS['forceadmin'] = 1;
    $result = ($dispatcher->trigger( 'onSliderLoad', array( &$slider ) ));
    if ( count($result) && isset($result[0]) && !empty($result[0])) {
      echo json_encode ( $result[0] );
    } else {
      echo json_encode ( $slider );
    }
  }
  
}


if (!function_exists('json_encode'))
{
  function json_encode($a=false)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }

      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}