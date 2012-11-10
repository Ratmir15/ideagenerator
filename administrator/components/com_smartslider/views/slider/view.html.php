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

jimport( 'joomla.application.component.view' );


class sliderViewslider extends JView{
	function display($tpl = null){
	  $mainframe = &JFactory::getApplication();
	  
		JToolBarHelper::title(   JText::_( 'Slider Manager' ) .' - '. JText::_( 'Edit slider' ), 'generic.png' );
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
		$model = $this->getModel();
		
		$id	= JRequest::getInt('id', 0);
		$model->setId($id);
		$row = $model->getData();
	  
		$user =& JFactory::getUser();
		if($row->id > 0)
		  $row->checkout( $user->get('id') );
		
		$p = $row->params;
		
		$row->params = new JParameter('','');
    parseParams($row->params, $p);
		$xmlfile = JPATH_COMPONENT . DS . 'forms' . DS . 'slider.xml';
		$xml = & JFactory::getXMLParser('Simple');
    $xml->loadFile($xmlfile);
    $sel = &$xml->document->params[0]->param[2];
    if($row->id > 0){
      $xml->document->params[0]->removeChild($sel);
    }
    
		$params = new JParameter( '');
    $params->setXML($xml->document->params[0]);
    $params->setXML($xml->document->params[1]);
    $params->row = &$row;
		
		if(isset($_SESSION['sliderparams']) && count($_SESSION['sliderparams']) > 0 ){
		  $row = (object)$_SESSION['sliderparams'];
		  $subparams = new JParameter('','');
		  $subparams->bind($row->params);
		  $row->params = $subparams;
      $params->bind($row->params->toArray());
      $params->row = $row;
		  unset($_SESSION['sliderparams']);
		}else{
		  $params->bind($row);
		  $params->bind($row->params->toArray());
		}
		$this->assignRef('defaultparams', $params->render('params'));
		
		$this->assignRef('generatorparams', $params->render('','generator'));
    
    $this->assignRef('row', $row);
		
		
		parent::display($tpl);
	}
}