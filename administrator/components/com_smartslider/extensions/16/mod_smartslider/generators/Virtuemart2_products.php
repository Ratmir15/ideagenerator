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

class Virtuemart2_productsParser {
  
  function Virtuemart2_productsParser($p) {
   $this->params = $p;
  }
  function makeSlides() {
    $content = TemplateParser::getFile("contents", $this->params->get('generatorcontents'));
    $caption = TemplateParser::getFile("captions", $this->params->get('generatorcaptions'));
    $slides = array();
    $this->result = TemplateParser::getVm2ProductIds("virtuemart_product_categories", $this->params->get("generatorcategory"), "virtuemart_category_id");
    $this->params->set("generatorcategory", 0); //clear the content, because if more than one category selected this param will be an array --> Templateparser::getDatas
    $xml = &JFactory::getXMLParser('Simple');
    $xml->loadFile(JPATH_ADMINISTRATOR .DS. "components" .DS. "com_smartslider" .DS. "params" .DS. "slidegenerator" .DS. $this->params->get('generator') .".xml");
    $this->xml = $xml->document->settings[0]->contentvalues[0]->children();
    $count = count($this->result);
    if ($this->params->get("generatorslidenumber") != "") {
      if($count > $this->params->get("generatorslidenumber"))
        $count = $this->params->get("generatorslidenumber");
    }
    for($i=0;$i<$count;$i++) {
      $d = TemplateParser::getDatas($i, $this->xml, $this->result, $this->params);
      $slides[$i]->content = TemplateParser::parse($content, $d, "content");
      $slides[$i]->caption = TemplateParser::parse($caption, $d, "caption");
      $slides[$i]->groupprev = 0;      
      $slides[$i]->title = $d["generatorslidetitle"];
    }

  return $slides;
  }
  
  function makeParams($i) {
    $arr = TemplateParser::getDatas($i, $this->xml, $this->result, $this->params);
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