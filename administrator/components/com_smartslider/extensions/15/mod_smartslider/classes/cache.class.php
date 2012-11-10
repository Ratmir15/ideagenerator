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

if(!defined('OfflajnSliderThemeCache')) {
  define("OfflajnSliderThemeCache", null);

  if (version_compare(PHP_VERSION, '5.2.0', '>=')) {
    require_once('cssmin5.php');
  }
  class OfflajnSliderThemeCache{
  
    var $env;
    
    var $module;
    
    var $params;
    
    var $css;
    
    var $themeCacheDir;
    
    var $themeCacheUrl;
    
    function OfflajnSliderThemeCache(&$_env, &$_module, &$_params, $_css){
      $this->env = &$_env;
      $this->module = &$_module;
      $this->params = &$_params;
      $this->css = $_css;
      
      $this->init();
    }
    
    function init(){
      $this->themeCacheDir = JPATH_CACHE.DS.$this->module->module.'_theme'.DS.$this->module->id;
      if(!JFolder::exists($this->themeCacheDir)){
        if(!JFolder::create($this->themeCacheDir , 0777)){
          echo JPATH_CACHE." is unwriteable, so the Slider won't work correctly. Please set the folder to 777!";
        }
      }
      $this->themeCacheUrl = JURI::base(true).'/cache/'.$this->module->module.'_theme/'.$this->module->id.'/';
    }
    
    function generateCss($c){
      $hash=md5(
        $this->env->slider->params->toString().
        filemtime($c['clearcss']).
        filemtime($c['captioncss']).
        filemtime($c['contentcss']).
        filemtime($this->css).
        count($this->env->slides));
        $doc =& JFactory::getDocument();
        foreach($this->env->slider->params->toArray() AS $k => $p){
            
          if(strpos($k, 'font')){
            $p = explode('|*', $p);
            $p[0] = str_replace('*','',$p[0]);
            $$k = $p;
            if($p[0] == '0') continue;
            $t = $p[2];
            if($p[4] == 1){
              $t.=':700';
            }else{
              $t.=':400';
            }
            if($p[5] == 1){
              $t.='italic';
            }
            $subset = $p[0];
            if($subset == 'LatinExtended'){
              $subset = 'latin,latin-ext';
            }else if($subset == 'CyrillicExtended'){
              $subset = 'cyrillic,cyrillic-ext';
            }else if($subset == 'GreekExtended'){
              $subset = 'greek,greek-ext';
            }
            $t.='&subset='.$subset;
            if($this->params->get('plugin') == 0){
              $doc->addStyleSheet( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http' ).'://fonts.googleapis.com/css?family='.$t);
            }else{
              $GLOBALS['sliderhead'].= '<link rel="stylesheet" href="'.((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http' ).'://fonts.googleapis.com/css?family='.$t).'" type="text/css" />';
            }
          }
        }
      if(!is_file($this->themeCacheDir.DS.$hash.'.css')){
        $this->calc = false;
        ob_start();
        include($this->css);
        $css = ob_get_contents();
        ob_end_clean();
        
        if(class_exists('CssMin')){
          //$css = CssMin::minify($css);
        }
        file_put_contents($this->themeCacheDir.DS.$hash.'.css', $css);
        @chmod($this->themeCacheDir.DS.$hash.'.css',0777);
      }
      return $this->themeCacheUrl.$hash.'.css';
    }
    
  }
  
}
?>