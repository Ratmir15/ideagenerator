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

jimport('joomla.filesystem.file');

class TemplateParser {

 function parse($content, $data, $type = "") {
    preg_match_all('/\{param.*?"(.*?)"(\s"(.*?)")?.*?\}((([^\{\},]*)[^\{\}]*?)\{\/param\})?/',$content, $out, PREG_SET_ORDER);  
    foreach($out AS $o){
      $name = 'generator'.$type.strtolower(str_replace(' ', '', $o[1]));
      $value = isset($data[$name]) ? $data[$name] : (isset($o[6]) ? $o[6] : (isset($o[3]) ? $o[3] : ''));
      $content = str_replace($o[0], $value, $content);
    }
    $content = ' ?> '.preg_replace(array('/<%/', '/%>/', '/param([a-z]*)/'), array('<?php', '?>', '\$data["generator$1"]'), $content ).' <?php ';
  ob_start();
      @eval($content);
  return ob_get_clean();
 }
  
 function getFile($type, $param) {
  $dir = JPATH_SITE . DS . 'modules' . DS . 'mod_smartslider' . DS . $type;  
  $files = JFolder::files($dir, '.phtml');
  foreach($files as $file) {
    if($param == strtolower(JFile::stripExt( $file ))) {
      break;
    }
  }
  return file_get_contents($dir.DS.$file);  
 }
  
 function getIds($table, $catid = 0, $field = "", $order = "" ) {
  $db = & JFactory::getDBO();
  $query = "SELECT ".$db->nameQuote('id')." 
              FROM ".$db->nameQuote('#__'.$table);
  if($field != "" && $catid != 0)
    $query .=   " WHERE ". $db->nameQuote($field)." = ".$db->quote($catid);
  if($order != "") {
    $ord = explode(",", $order);
    $query .= " ORDER BY ".$db->nameQuote($ord[0])." ".$ord[1];
  }
  $db->setQuery($query);
  return $db->loadResultArray();
 }
  
 function getProductIds($table, $catid = 0, $field = "" ) {
  $db = & JFactory::getDBO();
  $query = "SELECT ".$db->nameQuote('product_id')." 
              FROM ".$db->nameQuote('#__'.$table);
     for($i=0;$i<count($catid);$i++){
      if($i == 0) {
        $query .=   " WHERE ". $db->nameQuote($field)." = ".$db->quote($catid[$i]);
      } else {
        $query .= " or ". $db->nameQuote($field)." = ".$db->quote($catid[$i]);
      }
    }
  $db->setQuery($query);
  return $db->loadResultArray();
 }


 function getVm2ProductIds($table, $catid = 0, $field = "" ) {
  $db = & JFactory::getDBO();
  $query = "SELECT ".$db->nameQuote('virtuemart_product_id')." 
              FROM ".$db->nameQuote('#__'.$table);
     for($i=0;$i<count($catid);$i++){
      if($i == 0) {
        $query .=   " WHERE ". $db->nameQuote($field)." = ".$db->quote($catid[$i]);
      } else {
        $query .= " or ". $db->nameQuote($field)." = ".$db->quote($catid[$i]);
      }
    }
  $db->setQuery($query);
  return $db->loadResultArray();
 }
  
  
  function getImages($table, $catid = 0, $field = 0) {
    $db = & JFactory::getDBO();
    $query = "SELECT ".$db->nameQuote('id').", ".$db->nameQuote('filename')." 
                     FROM ".$db->nameQuote('#__'.$table);
    if($field != 0 && $catid != 0)
      $query .=   " WHERE ". $db->nameQuote($field)." = ".$db->quote($catid);
    
    $db->setQuery($query);
    return $db->loadRowList();
  }
  
  
 function getDatas($i, $xml, $result, $params) {
  require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_smartslider' . DS . 'params' . DS . 'insert' . DS . 'sqltables.php' ); 
  require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_smartslider' . DS . 'params' . DS . 'insert' . DS . 'specialqueries.php' );//to make replace codes for plugin
  $data = array();
  $limiter = 0;
  $field = "";
  foreach($params->toArray() AS $k => $param) {
    if(false !== strpos($k, "generator")) {
      preg_match_all( '/\{([a-z\_0-9]*)(,\s?([0-9]*))?\}/',$param , $preg_param, PREG_SET_ORDER  );          
        if(isset($preg_param[0][1])) {          
          for($j=0;$j<count($xml);$j++) { 
            if($preg_param[0][1] == $xml[$j]->name()) {
              if (isset($preg_param[0][3]) && $preg_param[0][3] > 0) {
                $limiter = $preg_param[0][3];
              } else {
                $limiter = "";
              }
              if($xml[$j]->attributes('insert') == 0) { //for smartinsert
                if($param == $preg_param[0][0]) {
                  $type = "";
                  if($xml[$j]->attributes('type') == "virtuemart_products_") {
                      if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart')) {
                        if (!class_exists( 'VmConfig' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'config.php');
                        $config= VmConfig::loadConfig();
                        $type = "virtuemart_products_".VMLANG;
                      }
                  } else {
                    $type = $xml[$j]->attributes('type');
                  }
                  //$data[$k] = "{SMR ".$xml[$j]->attributes('type'). " " . $preg_param[0][1] . " " . $sqlTables[$xml[$j]->attributes('type')][2]."=". $result[$i] ." ". $limiter ." }";
                  $data[$k] = "{SMR ".$type. " " . $preg_param[0][1] . " " . $sqlTables[$type][2]."=". $result[$i] ." ". $limiter ." }";
                } else {
                  //$repl = "{SMR ".$xml[$j]->attributes('type'). " " . $preg_param[0][1] . " " . $sqlTables[$xml[$j]->attributes('type')][2]."=". $result[$i] ." ". $limiter ." }";
                  $repl = "{SMR ".$type. " " . $preg_param[0][1] . " " . $sqlTables[$type][2]."=". $result[$i] ." ". $limiter ." }";
                  $param = str_replace($preg_param[0][0], $repl, $param);
                  $data[$k] = $param;
                }
              } else {  //for specialinsert code     {nameofcategory, 0}
                preg_match_all('/{([a-z]+)}/', $specialQueries[$xml[$j]->attributes('type')][2], $field, PREG_SET_ORDER );  
                  if ($limiter != "") {
                    $limiter = "/".$limiter."/"; //prepare limiter for specialinsert
                  }
                  if($param == $preg_param[0][0]) {
                    $data[$k] = "{SPR ".$xml[$j]->attributes('type'). " |" . $field[0][1] . "=" . $result[$i] ."| ". $limiter ." }";
                  } else {
                    $repl = "{SPR ".$xml[$j]->attributes('type'). " |" . $field[0][1] . "=" . $result[$i] ."| ". $limiter ." }";
                    $param = str_replace($preg_param[0][0], $repl, $param);
                    $data[$k] = $param;
                  }
              }   
            } 
          }
        } else {
          $data[$k] = $param;
        }           
    }
  }  
 return $data;
 }

}
?>