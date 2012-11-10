<?php 
/*------------------------------------------------------------------------
# smartslider - Smart Slider
# ------------------------------------------------------------------------
# author    Jeno Kovacs
# copyright Copyright (C) 2011 Offlajn.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.offlajn.com
-------------------------------------------------------------------------*/
?>
<?php
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );
require_once('helper.php'); //to parse templates

class ImgsParser {
  
  var $templatetype;
  
  function ImgsParser($p) {
   $this->params = $p;
  }

  function makeSlides() {
    $content = TemplateParser::getFile("contents", $this->params->get('generatorcontents'));
    $caption = TemplateParser::getFile("captions", $this->params->get('generatorcaptions'));
    $this->data = array();
    $slides = array(); 
    
    foreach($this->params->toArray() AS $k => $param) {
        if(false !== strpos($k, "generator")) {
          $this->data[$k] = $param;
        }
    }
    
    //$this->linktoimg = JURI::base(). "images". "/" . $this->params->get("generatorpath");
    $this->linktoimg = JURI::root(). "images". "/" . $this->params->get("generatorpath");
    $this->imgfiles = JFolder::files(JPATH_SITE . DS . "images" . DS . $this->params->get("generatorpath"),'.jpg|.jpeg|.png|.gif|.bmp');
    $xmlpath = JPATH_SITE . DS . "images" . DS . $this->params->get("generatorpath");
        
    $slidescontent = array();
   
    $this->data["generatorlinkforimage"] = $this->params->get("generatorcontentlinkforimage");
    $count = count($this->imgfiles);
    if ($this->params->get("generatorslidenumber") != "") {
      if($count > $this->params->get("generatorslidenumber"))
        $count = $this->params->get("generatorslidenumber");
    }
    
    for($i=0;$i<$count;$i++) {
      $xml = &JFactory::getXMLParser('Simple');
      $xmlfile = $xmlpath.DS.JFILE::stripExt($this->imgfiles[$i]).".xml";
      $this->data['generatorbackgroundimageurl'] = $this->linktoimg. "/" .$this->imgfiles[$i];
      if(file_exists($xmlfile)) {
        
        $xml->loadFile($xmlfile);
        
        if(isset($xml->document->title)) {
          $slides[$i]->title = $xml->document->title[0]->data();          
        } else {  
          $slides[$i]->title = JFile::stripExt($this->imgfiles[$i]);
        }
        $slides[$i]->content = TemplateParser::parse($content, $this->data);
        
        if(isset($xml->document->caption)) {
          $slides[$i]->caption = $xml->document->caption[0]->data();   
        } else {
          $slides[$i]->caption = "";
        }
        if($xml->document->attributes("groupprev")) {
          $slides[$i]->groupprev = $xml->document->attributes("groupprev");
        } else {
          $slides[$i]->groupprev = 0;
        }
      } else {
        $slides[$i]->title = JFile::stripExt($this->imgfiles[$i]);
        $slides[$i]->content = TemplateParser::parse($content, $this->data);
        $slides[$i]->caption = "";
        $slides[$i]->groupprev = 0;
      }
    }
    return $slides;
  }
  
  function makeParams($i) {
    $this->data['generatorcontentbackgroundimageurl'] = str_replace("administrator/", "", $this->linktoimg. "/" .$this->imgfiles[$i]);
    $params = new JParameter('');
		$params->loadArray($this->data);
		if(version_compare(JVERSION,'1.6.0','>=')) {
      $paramstr = stripslashes(json_encode($this->data));
      $paramstr = preg_replace('/contents\"/', "content\"", $paramstr);
      $paramstr = preg_replace('/captions\"/', "caption\"", $paramstr);
    } else {
      $paramstr = $params->toString();
      $paramstr = preg_replace('/contents=/', "content=", $paramstr);
      $paramstr = preg_replace('/captions=/', "caption=", $paramstr);
    }
    $paramstr = str_replace("generator", "", $paramstr);
     
   return $paramstr;
  }
}
?>