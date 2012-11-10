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

require_once('helper.php'); //to parse templates and load template files

class IgnitegalleryParser {
  
  function IgnitegalleryParser($p) {
   $this->params = $p;
  }

  function makeSlides() {
    $content = TemplateParser::getFile("contents", $this->params->get('generatorcontents'));
    $caption = TemplateParser::getFile("captions", $this->params->get('generatorcaptions'));

    $slides = array();
    $this->ids = array();
    $this->result = TemplateParser::getImages("igallery_img", $this->params->get("generatorcategory"), "gallery_id");
    $xml = &JFactory::getXMLParser('Simple');
    $xml->loadFile(JPATH_ADMINISTRATOR .DS. "components" .DS. "com_smartslider" .DS. "params" .DS. "slidegenerator" .DS. $this->params->get('generator') .".xml");
    $this->xml = $xml->document->settings[0]->contentvalues[0]->children(); 

    $count = count($this->result);
    if ($this->params->get("generatorslidenumber") != "") {
      if($count > $this->params->get("generatorslidenumber"))
        $count = $this->params->get("generatorslidenumber");
    }
    
    $this->ids = $this->makeArrayForIds();
    
    for($i=0;$i<$count;$i++) {
      $d = TemplateParser::getDatas($i, $this->xml, $this->ids, $this->params);
      $d['generatorcontentbackgroundimageurl'] = JURI::base()."images/igallery/original/".$this->getFolders($this->result[$i][0])."/".$this->result[$i][1];
      $slides[$i]->content = TemplateParser::parse($content, $d, "content");
      $slides[$i]->caption = TemplateParser::parse($caption, $d, "caption");
      $slides[$i]->groupprev = 0;
      $slides[$i]->title = (isset($d["generatorslidetitle"])) ? $d["generatorslidetitle"] : "";
    }
    
  return $slides;
  }
  
  //to store phocagallery image ids in another array to call TemplateParser::getDatas() function (it is prepare for ids only)   
  function makeArrayForIds() { 
    $arr = array();
    for($i=0;$i < count($this->result);$i++) {
      $arr[$i] = $this->result[$i][0];
    }
    return $arr;
  }
  
  //Ignite gallery store images by 100. The first directory is '1-100', the second is '101-200' and so on. The position of an image depends on its id.
  function getFolders($id) {
    $folder = "";
    $num = 1;
    if($id < 100) {
       $num = intval($id / 100);
    }
    if ($id > $num*100) {
      $num++;
    }
    $folder = $num*100-99;
    $folder .= "-";
    $folder .= $num*100;
    return $folder;
  }
  
  
  function makeParams($i) {
    $arr = TemplateParser::getDatas($i, $this->xml, $this->ids, $this->params);
    $params = new JParameter('');
		$params->loadArray($arr);
    $paramstr = $params->toString();
    $paramstr = str_replace("generator", "", $paramstr);
    $paramstr = preg_replace('/contents=/', "content=", $paramstr);
    $paramstr = preg_replace('/captions=/', "caption=", $paramstr);
   return $paramstr;  
  }
}