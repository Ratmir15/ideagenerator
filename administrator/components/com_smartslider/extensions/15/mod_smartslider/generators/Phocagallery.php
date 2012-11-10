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

class PhocagalleryParser {
  
  function PhocagalleryParser($p) {
   $this->params = $p;
  }

  function makeSlides() {
    $content = TemplateParser::getFile("contents", $this->params->get('generatorcontents'));
    $caption = TemplateParser::getFile("captions", $this->params->get('generatorcaptions'));

    $slides = array();
    $this->ids = array();
    $this->result = TemplateParser::getImages("phocagallery", $this->params->get("generatorcategory"), "catid");
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
      $d['generatorcontentbackgroundimageurl'] = JURI::root()."images/phocagallery/".$this->result[$i][1];
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
  
  function makeParams($i) {
    $arr = TemplateParser::getDatas($i, $this->xml, $this->ids, $this->params);
    $params = new JParameter('');
		$params->loadArray($arr);
    if(version_compare(JVERSION,'1.6.0','>=')) {
      $paramstr = stripslashes(json_encode($arr));
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