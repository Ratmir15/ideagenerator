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


class sliderViewslide extends JView{

	function display($tpl = null){
	  global $mainframe;
	  
    $document =& JFactory::getDocument();
    $document->addScript(JURI::root().'modules/mod_smartslider/js/dojo.js');
    $document->addScript(JURI::root().'modules/mod_smartslider/js/easing.js');
    $document->addScript(JURI::root().'modules/mod_smartslider/js/regexp.js');
    $document->addScript(JURI::root().'modules/mod_smartslider/js/cookie.js');
    $document->addScript(JURI::root().'modules/mod_smartslider/captions/captions.js');
    $document->addScript(JURI::base().'components/com_smartslider/js/live.js');
    
		$id	= JRequest::getInt('id', 0);
		
		($id > 0) ? $title = 'Edit ' : $title = 'Add ';
	  
		JToolBarHelper::title(   JText::_( 'Slide Manager' ) .' - '. JText::_( $title.'slide' ), 'generic.png' );
		JToolBarHelper::save();
		
    if(version_compare(JVERSION,'1.7.0','ge')) {
      JToolBarHelper::save2new();
    } else {
  		$bar = & JToolBar::getInstance('toolbar');
  		$bar->appendButton( 'Standard', 'savenew', 'Save & New', 'save2new', false, false );
		}
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
		
		$model = $this->getModel();
		
		$user =& JFactory::getUser();
		
		if($id > 0)
		  $model->getData()->checkout( $user->get('id') );

		$model->setId($id);
		$row = $model->getDataWithSlider();
		  
		$xmlfile = JPATH_COMPONENT . DS . 'forms' . DS . 'slide.xml';
		$params = new JParameter( '', $xmlfile);
		$row->sliderparams = new JParameter($row->sliderparams);
		if(!property_exists($row, 'params'))
		  $row->params = '';
		$p = $row->params;
		$row->params = new JParameter('');
		parseParams($row->params, $p);
		$params->row = &$row;
		
		if(isset($_SESSION['slideparams']) && count($_SESSION['slideparams']) > 0 ){
		  $params->bind($_SESSION['slideparams']);
		  parseParams($params, $_SESSION['slideparams']['params']);
		  parseParams($row->params, $_SESSION['slideparams']['params']);
		  unset($_SESSION['slideparams']);
		}else{
      $params->bind($row);
		  $params->bind($row->params);
    }
		
		if(JRequest::getVar('task') == 'add'){
      $row->slider = JRequest::getInt('sliderid');
    }

		$this->assignRef('defaultparams', $params->render('params'));
		
		$this->assignRef('contentparams', $params->render('params', 'content'));
		
		$this->assignRef('captionparams', $params->render('params', 'caption'));
		
		$this->assignRef('row', $row);
		
    
    
    
    ob_start();
    include(JPATH_SITE.DS.'modules'.DS.'mod_smartslider'.DS.'live_demo.php');
    $live = ob_get_clean();
    
    $document =& JFactory::getDocument();
    $document->addScriptDeclaration('
      document.live = '.json_encode($live).';
      dojo.addOnLoad(function(){
        var tips = dojo.query(".hasTip", dojo.byId("defaultparams"));
        dojo.forEach(tips, function(el, i){
          new ofTip({node: el});
        } );
        new SlideLive({
          width: '.$row->sliderparams->get('width').',
          height: '.$row->sliderparams->get('height').',
          lang_insert: "'.JTEXT::_('insert').'",
          lang_replace: "'.JTEXT::_('replace').'"
        });
      });
    ');
		parent::display($tpl);
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
