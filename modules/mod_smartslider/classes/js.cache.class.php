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

require_once('jsmin4.php');

if(!defined('OfflajnSliderJsCache')) {
  define("OfflajnSliderJsCache", null);

  class OfflajnSliderJsCache{
    
    var $module;
    
    var $js;
    
    var $themeCacheDir;
    
    var $themeCacheUrl;
    
    function OfflajnSliderJsCache(&$_module){
      $this->module = &$_module;
      $this->js = array();
      
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
    
    function add($src){
      $this->js[] = $src;
    }
    
    function generate(){
      $dir = dirname(__FILE__).DS.'..'.DS;
      $cachetext = '';
      foreach($this->js as $js){
        $cachetext.=$js.filemtime($dir.$js);
      }
      $hash = md5($cachetext);
      $script = $this->themeCacheDir.DS.$hash.'.js';
      if(is_file($script)) return $this->themeCacheUrl.$hash.'.js'; //already cached
      $jst= "(function(){";
      foreach($this->js as $js){
        $jst.= file_get_contents($dir.$js)."\n";
      }
      $jst.= "})();";
      file_put_contents($script, OJSMin::minify($jst));
      @chmod($script,0777);
      return $this->themeCacheUrl.$hash.'.js';
    }
    
  }
  
}
?>