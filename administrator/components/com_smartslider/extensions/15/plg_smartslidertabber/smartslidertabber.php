<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.html.parameter' );

class  plgSystemSmartslider extends JPlugin
{
	function plgSystemSmartslider(& $subject, $config){
		parent::__construct($subject, $config);
	}

	/**
	* Converting the site URL to fit to the HTTP request
	*
	*/
	function onAfterRender(){
    $app = &JFactory::getApplication();
    if(!$app->isSite()) return;
		$body = JResponse::getBody();
		if(false === strpos($body, "{smartslider")){
      return;
    }
    $sliders = array();
    //preg_match_all('/{(slider)\s+id="([0-9]+)"\s*}((\s*{slide\s*title="(.*?)"\s*}\s*{content}(.*?){\/content}\s*{caption}(.*?){\/caption}\s*{\/slide}\s*)){\/\1}/sm', $body, $sliders);
    preg_match_all('/{smartslider\s+id="([0-9]+)"\s*}\s*(.*?)\s*{\/smartslider}/sm', $body, $sliders, PREG_SET_ORDER);
    $j = 1;
    if(is_array($sliders)){
      foreach($sliders AS $slider){
        $slidertext = $slider[0];
        $sliderid = $slider[1];
        preg_match_all('/{slide\s+title="(.*?)"\s*}\s*{content}\s*(.*?)\s*{\/content}\s*{caption}\s*(.*?)\s*{\/caption}\s*{\/slide}\s*/sm', $slider[2], $slides, PREG_SET_ORDER);
        $params = new JParameter(JPATH_ROOT . DS . "modules".DS.'mod_smartslider'.DS.'mod_smartslider.xml');
        $params->set('slider', $sliderid);
        $params->set('plugin', 1);
        $GLOBALS['sliderhead'] = '';
        $module = new stdClass();
				$module->params = $params->toString();
				$module->module = 'mod_smartslider';
				$module->id = $sliderid+$j*100000;
				$module->user = 0;
				
				$pslides = array();
				$i = 1;
				foreach($slides AS $slide){
          $pslide = new stdClass();
          $pslide->id = $i;
          $pslide->title = $slide[1];
          $pslide->content = $slide[2];
          $pslide->caption = $slide[3];
          $pslide->groupprev = 0;
          $pslide->icon = '';
          $pslides[] = $pslide;
          $i++;
        }
        $GLOBALS['slides'] = &$pslides;
        $rendered = JDocumentRendererModule::render($module, $params->toArray());
        unset($GLOBALS['slides']);
        $body = str_replace($slidertext, $rendered, $body);
        $body = str_replace('</head>', $GLOBALS['sliderhead'].'</head>', $body);
        $j++;
      }
    }
    
    //exit;
		JResponse::setBody($body);
	}
}