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

jimport( 'joomla.html.parameter' );

if(!function_exists('getSmartSlider')) {
  function getSmartSlider($params, $this, $module, $slider, $sliderparams) {
  
    ob_start();
    require_once(dirname(__FILE__).DS.'classes'.DS.'js.cache.class.php');
    $GLOBALS['jscache'] = new OfflajnSliderJsCache($module);
    
    JPluginHelper::importPlugin( 'smartslider' );
    $dispatcher =& JDispatcher::getInstance();
    
    
    if(!function_exists('parseParams')){
      function parseParams(&$p, $vals){
        if(version_compare(JVERSION,'1.6.0','>=')) {
          $p->loadJSON($vals);
        }else{
          $p->loadIni($vals);
        }
      }
    }
    
    $params->def('plugin', 0);
    /*
    $sliderid = $params->get('slider', 0);
    if(!$sliderid){
      echo JText::_('Please select a slider on the backend!');
      return;
    }
    */
    $tthis = new stdClass();
    if(isset($this)){
      $tthis = &$this;
    }
    
    $tthis->slider = $slider;
    $sliderid = $tthis->slider->id;
    
    $db =& JFactory::getDBO();
    /*$query = 'SELECT *'
    . ' FROM #__offlajn_slider'
    . ' WHERE published = 1 AND id = '.((int)$sliderid);
    $db->setQuery($query);
    $tthis->slider = $db->loadObject();
    if($tthis->slider == NULL){
      echo JText::_('Please select a slider on the backend!');
      return;
    }
   */ 
    $type = dirname(__FILE__) . DS . 'types' . DS . $tthis->slider->type . DS;
    if(!is_dir($type)){
      echo JText::_('Please select the type for the slider!');
      return;
    }
    
    $theme = dirname(__FILE__) . DS . 'types' . DS . $tthis->slider->type . DS . $tthis->slider->theme . DS;
    if(!is_dir($theme)){
      echo JText::_('Please select the theme for the slider!');
      return;
    }
    
    $tthis->typeParams = new JParameter('');
    parseParams($tthis->typeParams, $tthis->slider->params);
   // $tthis->slider->params = &$tthis->typeParams;
    $tthis->slider->params = $sliderparams;
    
    if($params->get('plugin') == 0){
      $jnow =& JFactory::getDate();
      $now = $jnow->toMySQL();
      $nullDate	= $db->getNullDate();
      
      $query = 'SELECT id, title, content, caption, groupprev, icon'
      . ' FROM #__offlajn_slide'
      . ' WHERE published = 1 AND slider = '.((int)$sliderid)
      . ' AND ( publish_up = '.$db->Quote($nullDate).' OR publish_up <= '.$db->Quote($now).' )'
      . ' AND ( publish_down = '.$db->Quote($nullDate).' OR publish_down >= '.$db->Quote($now).' )';
      if($tthis->typeParams->get('random', 0) == 0 ){
        $query.= ' ORDER BY ordering';
      }else{
        $query.= ' ORDER BY RAND()';
      }
      $db->setQuery($query);
      $slides = $db->loadObjectList();
    }else{
      $slides = $GLOBALS['slides'];
    }
    $tthis->slides = array();
    $count = count($slides);
    
    if($tthis->slider->params->get('generator')!= "Choose" && $count == 0) {
      $gen = $tthis->slider->params->get('generator');
      require_once(dirname(__FILE__).DS.'generators'.DS.$gen.'.php');
      $gen.='Parser';
      $tp = new $gen($tthis->slider->params);
      $slidearray = $tp->makeSlides();
      for($i=0;$i<count($slidearray);$i++) {
        $slides[$i]->title = $slidearray[$i]->title;
        $slides[$i]->content = $slidearray[$i]->content;
        $slides[$i]->caption = $slidearray[$i]->caption;
        $slides[$i]->groupprev = $slidearray[$i]->groupprev;
      }
      $count = $i;
    }
    
    if(($count != 0)){
      
    
      for($i = 0, $j = 0; $i < $count; ++$i, ++$j){
        $slides[$i]->title = str_replace('$', '&#36;', $slides[$i]->title);
        $slides[$i]->content = str_replace('$', '&#36;', $slides[$i]->content);
        $slides[$i]->caption = str_replace('$', '&#36;', $slides[$i]->caption);
        $p = new JParameter('');
        //parseParams($p, $slides[$i]->params);
        //$slides[$i]->params = $p;
        $slides[$i]->childs = array();
        if($slides[$i]->groupprev == 1 && isset($tthis->slides[$j-1])){
          --$j;
        }else{
          $tthis->slides[$j] = &$slides[$i];
        }
        $tthis->slides[$j]->childs[] = &$slides[$i];
      }
    }else{
      echo JText::_('Please add some slide to the slider!');
      return;
    }
    
    $document =& JFactory::getDocument();
    $GLOBALS['jscache']->add('js/dojo.js');
    $GLOBALS['jscache']->add('ie6/ie6.js');
    $GLOBALS['jscache']->add('captions/captions.js');
    $GLOBALS['jscache']->add('types/'.$tthis->slider->type.'/script.js');
    
    $id = $module->module.'_'.$module->id;
    
    require_once(dirname(__FILE__).DS.'classes'.DS.'cache.class.php');
    require_once(dirname(__FILE__).DS.'classes'.DS.'helper.class.php');
	$smartslidersmainslide = SmartSlidersChecks();
    $themecache = new OfflajnSliderThemeCache($tthis, $module, $params, $theme.'style.css.php');
    $context = array();
    $context['helper'] = new OfflajnSliderHelper($themecache->themeCacheDir, $themecache->themeCacheUrl);
    $context['fonturl'] = JURI::root().'modules/'.$module->module.'/fonts/';
    $context['url'] = JURI::root().'modules/'.$module->module.'/types/'.$tthis->slider->type.'/'.$tthis->slider->theme.'/';
    $context['clearcss'] = dirname(__FILE__). DS .'clear.css.php';
    $context['captioncss'] = dirname(__FILE__). DS .'captions'. DS .'style.css.php';
    $context['contentcss'] = dirname(__FILE__). DS .'contents'. DS .'style.css.php';
    $context['id'] = '#'.$id;
    if($params->get('plugin') == 0){
      $document->addStyleSheet($themecache->generateCss($context).(defined('DEMO') ? '?'.time() : ''));
    }else{
      $GLOBALS['sliderhead'].= '<link rel="stylesheet" href="'.($themecache->generateCss($context).(defined('DEMO') ? '?'.time() : '')).'" type="text/css" />';
    }
    include($theme.'theme.php');
    
    $jsurl = $GLOBALS['jscache']->generate();
    if($params->get('plugin') == 0){
      $document->addScript($jsurl);
    }else{
      $GLOBALS['sliderhead'].= '<script type="text/javascript" src="'.$jsurl.'"></script>';
    }
    unset($GLOBALS['jscache']);
    $slider = ob_get_clean();
    $result = ($dispatcher->trigger( 'onSliderLoad', array( &$slider ) ));
    
    
    /*
    if (count($result)) {
      echo $result[0];
    } else {
      echo $slider;
    }
    */
    
    if (count($result)) {
      $result[0] = JHTML::_('content.prepare', $result[0] );
      return array( $result[0], $jsurl, ($themecache->generateCss($context).(defined('DEMO') ? '?'.time() : '')) );
    } else {
      $slider = JHTML::_('content.prepare', $slider );
      return array( $slider, $jsurl, ($themecache->generateCss($context).(defined('DEMO') ? '?'.time() : '')) );
    }
  
  }
}
//Get the sliderid for the sql query
$tthis = new stdClass();
$sliderid = $params->get('slider', 0);
  if(!$sliderid){
    echo JText::_('Please select a slider on the backend!');
    return;
  }

//get the datas of the current slider
$db =& JFactory::getDBO();
$query = 'SELECT *'
  . ' FROM #__offlajn_slider'
  . ' WHERE published = 1 AND id = '.((int)$sliderid);
$db->setQuery($query);
$tthis->slider = $db->loadObject();
if($tthis->slider == NULL){
  echo JText::_('Please select a slider on the backend!');
  return;
}

//Parse slider params

  if(!function_exists('parseParams')){
    function parseParams(&$p, $vals){
      if(version_compare(JVERSION,'1.6.0','>=')) {
        $p->loadJSON($vals);
      }else{
        $p->loadIni($vals);
      }
    }
  }

$tthis->typeParams = new JParameter('');
  parseParams($tthis->typeParams, $tthis->slider->params);
  $tthis->slider->params = &$tthis->typeParams;

//get the slider cache settings
$rows = array();
$iscache = $tthis->slider->params->get('iscache');
$cacheinterval = $tthis->slider->params->get('cacheinterval');

if(isset($iscache) && $iscache == 1 ) {
  
  jimport( 'joomla.cache.cache' );
  
  $cache = & JFactory::getCache();
  $cache->setCaching( 1 ); 
  //set the cache expiration
  if(isset($cacheinterval) && $cacheinterval > 0 ) 
    $cache->setLifeTime((int)$cacheinterval);

  if(isset($this)){ 
    $rows = $cache->call( 'getSmartSlider', $params, $this, $module, $db->loadObject(), $tthis->typeParams );
  } else {
    $rows = $cache->call( 'getSmartSlider', $params, '', $module, $db->loadObject(), $tthis->typeParams );
  }
  
} else {
  if(isset($this)){
    $rows =  call_user_func( 'getSmartSlider', $params, $this, $module, $db->loadObject(), $tthis->typeParams );
  } else {
    $rows = call_user_func( 'getSmartSlider', $params, '', $module, $db->loadObject(), $tthis->typeParams );
  }
}
//show the slider
$document =& JFactory::getDocument();
  $document->addScript($rows[1]);
  $document->addStyleSheet($rows[2]);

echo $rows[0];

?>