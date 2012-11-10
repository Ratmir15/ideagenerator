<?php
/**
 * @version		$Id: SmartSliderInsert revision date lasteditedby $
 * @package		Joomla
 * @subpackage	Smartslider
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
 
 // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );

class plgSmartsliderSmartSliderInsert extends JPlugin {
  var $body;
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	function plgSmartsliderSmartSliderInsert(& $subject ) {
		parent::__construct($subject);
 	}
	function onSliderLoad( &$slider){
    $app = &JFactory::getApplication();
   if(!$app->isSite() && !$GLOBALS['forceadmin']) return;        
    $this->body = $slider;     
    $this->db =& JFactory::getDBO();
		if(false === strpos($this->body, "{S")){
      return $this->body;
    }

    $contents = array();
    
    if(strpos($this->body, "{SMR")) {
      preg_match_all('/{SMR\s+([a-zA-Z_0-9]+)\s+([a-zA-Z_]+)\s+([a-zA-Z_]+)=([0-9]+)(\s+([0-9]+))?\s*}/', $this->body, $contents, PREG_SET_ORDER);
      $this->makeDefaultQuery($contents);
    }
    
    if(strpos($this->body, "{SPR")) {
      preg_match_all('/{SPR\s+([a-z_0-9]+)\s+([|a-zA-Z0-9=\s]+)\s*}/', $this->body, $contents, PREG_SET_ORDER);
      $this->makeSpecialQuery($contents);
    }  	 
    return $this->body;
  }
	
	function closeHtmlTags($text) {
      if( preg_match_all("|<([a-zA-Z]+)>|",$text,$aBuffer) ) {
        if( !empty($aBuffer[1]) ) {
          preg_match_all("|</([a-zA-Z]+)>|",$text,$aBuffer2);
          if( count($aBuffer[1]) != count($aBuffer2[1]) ) {
            foreach( $aBuffer[1] as $index => $tag ) {
                if( empty($aBuffer2[1][$index]) || $aBuffer2[1][$index] != $tag)
                   $text .= '</'.$tag.'>';
            }
          }
        }
      }
      return $text;
  }
	
	function makeDefaultQuery($elements) {
      foreach($elements AS $content){
        if($this->checkDefaultParams($content[1], $content[2])) {         
          $query = "
              SELECT ".$this->db->nameQuote($content[2])."
                FROM ".$this->db->nameQuote("#__".$content[1])."
                WHERE  ".$this->db->nameQuote($content[3])." = ".$this->db->quote($content[4]);
          $this->db->setQuery($query);
            if(count($result = $this->db->loadResult())) {      
              if (isset($content[5])) {
                $result = $this->closeHtmlTags(substr($result, 0, $content[5]));  
              }
              $this->body = str_replace($content[0], $result, $this->body);
            }  
        }
      }
  }
  
  function makeSpecialQuery($elements) {
    require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_smartslider' . DS . 'params' . DS . 'insert' . DS . 'specialqueries.php' );
    require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_smartslider' . DS . 'params' . DS . 'insert' . DS . 'specialfunctions.php' );
    foreach($elements AS $content) {
      $query = $specialQueries[$content[1]][2];  
      preg_match_all('/\s*\|([a-zA-Z]+)=(.*?)\|/', $content[2], $replaces, PREG_SET_ORDER);
      foreach($replaces as $rep) {
        $query = str_replace("{".$rep[1]."}", $this->db->quote($rep[2]), $query);          
      }
      $this->db->setQuery($query);
      $result = call_user_func($specialQueries[$content[1]][3], $this->db->loadRow());
      $this->body = str_replace($content[0], $result, $this->body);  
    }
  }
  
  function checkDefaultParams($tbl, $fld) {
    require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_smartslider' . DS . 'params' . DS . 'insert' . DS . 'sqltables.php' );
    if( isset( $sqlTables[$tbl] ) && in_array( $fld, $sqlTables[$tbl] ) ) {
        return true;
    } 
        return false;    
  }

}