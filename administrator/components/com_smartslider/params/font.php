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

jimport('joomla.filesystem.file');

class JElementFont extends JElement
{
  var $_moduleName = '';
  
	var	$_name = 'font';
	
	var $_node = '';
	
	var $_google = array();
	var $_googleName = array();
	var $_googlefonts = array();

	function fetchElement($name, $value, &$node, $control_name){
		$this->_node = &$node;
		$this->_googlefonts = array();
		$this->_google = array();
		$this->_googleName = array();
    $this->init();
    
    $html = '';
    $document =& JFactory::getDocument();
    $document->addScript(JURI::base().'components/com_smartslider/params/jpicker/jquery-1.4.4.min.js');
    $document->addScript(JURI::base().'components/com_smartslider/params/jpicker/jpicker-1.1.6.min.js');
    $document->addStyleSheet(JURI::base().'components/com_smartslider/params/jpicker/css/jPicker-1.1.6.min.css');
    
    $document->addScript(JURI::root().'modules/mod_smartslider/js/dojo.js');
    $document->addScript(JURI::base().'components/com_smartslider/params/font.js');
    $h = '
<fieldset style="position:relative; top: 35%; margin-left: -450px; left: 50%; width: 900px; background-color: #fff;border-radius: 8px;">
  <h3 style="color: #0B55C4;">Font Chooser</h3>
  <div id="closefont" style="cursor: pointer; position: absolute; top:-20px; right:-10px; width: 30px; height: 30px; background: url(\'components/com_smartslider/params/images/closebox.png\') repeat;"></div>
  <table style="width: 100%;font-weight: bold;">
    <tr>
      <td width="10%">Font type: </td>
      <td width="30%">'.$this->genChooser().'</td>
      <td width="15%">Alternative fonts: </td>
      <td width="45%">'.$this->genAlternative().'</td>
    </tr>
    <tr>
      <td>Font family: </td>
      <td>'.$this->genFamily().'</td>
      <td>Font size: </td>
      <td>'.$this->genSize().'</td>
    </tr>
    <tr>
      <td>Bold:<br />Italic</td>
      <td>
      <input type="checkbox" value="1" id="fontbold" style="float: none;">
      <br />
      <input type="checkbox" value="1" id="fontitalic" style="float: none;">
      </td>
      <td>Color:</td>
      <td>
        <input style="padding: 2px 0;" type="text" id="fontcolor1" value="" class="color" />
      </td>
    </tr>
    <tr>
      <td>Font shadow:</td>
      <td>
        <input type="checkbox" value="1" id="fontshadowbox">
      </td>
      <td>Font shadow properties:</td>
      <td>
        <input id="fontshadowx" value="0px" type="text" size="4">
        <input id="fontshadowy" value="0px" type="text" size="4">
        <input id="fontshadowsize" value="0px" type="text" size="4">
        <input style="padding: 2px 0;" type="text" id="fontshadowcolor" value="" class="color" />
        </td>
    </tr>
    <tr>
      <td>Preview text:</td>
      <td>
        <input style="padding: 2px 0;" type="text" id="previewbg" value="ffffff" class="color" /><input id="textpreview" type="text" value="Preview Text"></td>
      <td>Preview:</td>
      <td style="padding: 5px;" id="fontpreview"></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td style="padding-top: 20px;"><button id="savefont" type="button" style="margin-right: 20px; font-size: 14px;font-weight: bold;">Save</button><button style="font-size: 14px;font-weight: bold;" id="cancelfont" type="button">Cancel</button></td>
    </tr>
  </table>
</fieldset>';
    
    $GLOBALS['scripts'][] ='
      dojo.addOnLoad(function(){
        new FontConfigurator({
          hidden: dojo.byId("'.$control_name.$name.'"),
          changefont: dojo.byId("'.$control_name.$name.'change"),
          fonts: '.json_encode($this->_googlefonts).',
          h: '.json_encode($h).'
        });
      });
    ';
		$html.='<input type="hidden" name="'.$control_name.'['.$name.']" id="'.$control_name.$name.'" value="'.str_replace('"',"'",$value).'" />';
		$html.="<a style='float: left;' id='".$control_name.$name."change' href='#'>[".JText::_('Change font settings')."]</a>";
		return $html;
	}
	
	function init(){
	  $p = dirname(__FILE__).DS.'google/';
    $google = JFolder::files($p, '.txt');
    foreach($google as $g){
      $this->_google[] = JFile::stripExt($g);
      preg_match_all('/((?:^|[A-Z])[a-z]+)/',JFile::stripExt($g),$matches);
      $this->_googleName[] = implode(' ', $matches[1]);
      $fonts = file($p.$g, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      if ($fonts !== false) {
        $this->_googlefonts[JFile::stripExt($g)] = array_map('rtrim',$fonts);
      }
    }
  }
	
	function genChooser(){
    $chooser = "
    <select id='fonttype'>
      <option value='0'>". JText::_('Use only the alternative fonts') ."</value>
      <optgroup label='Google fonts'>
    ";
    foreach($this->_google AS $k => $g){
      $chooser.= "<option value='".$g."'>". JText::_($this->_googleName[$k]) ."</option>";
    }
    $chooser.= "
      </optgroup>
    </select>
    ";
    
    return $chooser;
  }
  
	function genAlternative(){
    $alt = "<input id='alternativefont' type='text' value='Arial, Helvetica'>";
    
    return $alt;
  }
  
	function genFamily(){
    $fam = "
        <select id='fontfamily'>
        
        </select>
    ";
    
    return $fam;
  }
  
	function genSize(){
    $fam = "
        <input type='text' value='12px' id='fontsize'>
    ";
    
    return $fam;
  }
	
}

if (!function_exists('json_encode'))
{
  function json_encode($a=false)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }

      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}