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

jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );
		
class JElementTemplateList extends JElement{

  var $_moduleName = '';
  
  var $_name = 'TemplateList';
  
  var $_data = '';

  function render(&$xmlElement, $value, $control_name = 'params'){
    $this->html = '';
    $this->canvas	= $xmlElement->attributes('canvas') == 'fixed' ? 1 : 0;
    $this->ext	= $xmlElement->attributes('imgext');
    
    $this->_data = new stdClass();
    $this->p = &$this->_parent;
    $this->row = $this->_parent->row;

    $this->folder	= $xmlElement->attributes('folder');
    
    $this->dir = JPATH_SITE . DS . 'modules' . DS . 'mod_smartslider' . DS . 'types' . DS . $this->row->type . DS . $this->row->theme . DS . $this->folder;
    
    $this->name	= $xmlElement->attributes('name');
    $this->row->params->def($this->name, $value);
    
    $this->editor	= $xmlElement->attributes('editor');
  	$label	= $xmlElement->attributes('label');
  	$descr	= $xmlElement->attributes('description');
  	//make sure we have a valid label
		$label = $label ? $label : $this->name;
		$result[0] = $this->fetchTooltip($label, $descr, $xmlElement, $control_name, $this->name);
		$result[1] = $this->fetchElement($this->name, $value, $xmlElement, $control_name);
		$result[2] = $descr;
		$result[3] = $label;
		$result[4] = $value;
		$result[5] = $this->name;

		return $result;
  }
 
  function fetchElement($name, $value, &$node, $control_name){
    $this->control_name = $control_name;
    $document =& JFactory::getDocument();
    $document->addScript(JURI::root().'modules/mod_smartslider/js/dojo.js');
    $document->addScript(JURI::base().'components/com_smartslider/params/editor.js');
    $document->addStyleSheet(JURI::base().'components/com_smartslider/params/templatelist.css');
    
    $typeSelector = $this->generateTemplateSelector();
    
    $document->addScriptDeclaration('
      dojo.addOnLoad(function(){
        new SlideEditor({
          node: dojo.byId("param'.$this->name.'"),
          editor: dojo.byId("'.$this->editor.'"),
          data: '.json_encode($this->_data).',
          v16: "'.(version_compare(JVERSION,'1.6.0','ge') ? 1 : 0).'",
          ext: "'.$this->ext.'"
        });
      });
    ');

    return $typeSelector;
  }
  
  function generateTemplateSelector(){
    $files = JFolder::files($this->dir, '.phtml');
    $imgurl = JURI::base().'../modules/mod_smartslider/types/'.$this->row->type.'/'.$this->row->theme.'/'.$this->folder.'/';
    $options = array();
    $html = '<div style="clear:both"></div>';
      $x = 0;
    if(is_array($files)){
      foreach($files as $file){
        $content = file_get_contents($this->dir.DS.$file);
        
        $file = JFile::stripExt( $file );
        $lfile = strtolower($file);
        $this->_data->$lfile = new stdClass();
        $isBlank = false;
        if($lfile == 'blank') $isBlank = true;
        $this->_data->$lfile->html = $this->parseTemplate($content,$isBlank);
        
        $pattern = '/(.*?[a-z]{1})([A-Z]{1}.*?)/';
        $replace = '${1} ${2}';
        
        $name = preg_replace($pattern, $replace, $file);
        
        $options[] = JHTML::_('select.option', $lfile, ucfirst($name));
        $html.='<img id="param'.$this->name.$x.'" onclick="var el=dojo.byId(\'param'.$this->name.'\'); el.selectedIndex = '.$x.';el.callChange();" class="miniimage" src="'.$imgurl.$file.'.png" />';
        $x++;
      }
    }
    return $this->html.JHTML::_('select.genericlist',  $options, 'param['.$this->name.']', 'class="inputbox"', 'value', 'text', $this->row->params->get($this->name)).$html;
  }
  
  function parseTemplate($c, $isBlank = false){
    preg_match_all ('/{param ([a-z]*) "(.*?)"( "(.*?)")?( "(.*?)")?}(([^{]*){\/param})?/',$c , $out, PREG_SET_ORDER  );

    $filtered = array();

    foreach($out AS $o){
      if(!isset($filtered[$o[2]]))
        $filtered[$o[2]] = $o;
    }
      
      
    $xml = &JFactory::getXMLParser('Simple');
    $xml->loadString('<params addPath="/administrator/components/com_smartslider/params"></params>');
    $root = &$xml->document;
    
    $fields = array();
    $values = $this->row->params->toArray();
    //print_r($values);
    foreach($filtered AS $o){
      $name = preg_replace('/[^a-z]/', '', strtolower($o[2]) );
      
      $newname = $this->name.$name;
      $fields[] = $newname;
      if(isset($values[$newname])) $o[4] = $values[$newname];
      $a = array('name' => $newname, 'type' => $o[1], 'default' => isset($o[4]) ? $o[4] : '', 'label' => $o[2], 'description' => isset($o[6]) ? $o[6] : '' );
      if($o[1] == 'textarea'){
        $a['rows'] = 7;
        $a['cols'] = 60;
      }elseif($o[1] == 'text'){
        $a['size'] = 60;
      }
      $el = $root->addChild('param', $a);
      if($o[1] == 'list'){
        $options = explode(',', $o[8]);
        for($x = 0; $x < count($options); $x++ ){
          $el->addChild('option', array('value' => $options[$x]))->setData($options[++$x]);
        }
      }
      $c = preg_replace('/{param ([a-z]*) "'.$o[2].'"( "(.*?)")?( "(.*?)")?}(([^{]*){\/param})?/', '<%=param'.$newname.'%>', $c);
    }

    $params = new JParameter('');
    $params->setXML($root);
    $params->bind($values);
    $GLOBALS['scripts'] = array();
    
    $render = '';
    
    if(!$isBlank){
      $render.= '<a class="editashtml" id="'.$this->control_name.$this->name.'ashtml"></a>';
    }
    $render.= $params->render('param');
    
    $htmlfield = $this->control_name.$this->name.'html';
    $render.= '<textarea name="param['.$htmlfield.']" style="display:none;" id="param'.$htmlfield.'"></textarea>';
    if($this->canvas){
      $theme = JPATH_SITE . DS . 'modules' . DS . 'mod_smartslider' . DS . 'types' . DS . $this->row->type . DS . $this->row->theme . DS . 'style.css.php';
      ob_start();
      $calc = true;
      $this->env = new stdClass();
      $this->env->slider = new stdClass();
      $this->env->slider->params = &$this->row->sliderparams;
      
      $db =& JFactory::getDBO();
      $query = 'SELECT *'
      . ' FROM #__offlajn_slide'
      . ' WHERE published = 1 AND slider = '.((int)$this->row->slider)
      . ' ORDER BY ordering';
      $db->setQuery($query);
      $slides = $db->loadObjectList();
      $this->slides = array();
      $count = count($slides);
      if($count != 0){
        for($i = 0, $j = 0; $i < $count; ++$i, ++$j){
          $p = new JParameter('');
          $p->loadIni($slides[$i]->params);
          $slides[$i]->params = $p;
          $slides[$i]->childs = array();
          if($slides[$i]->groupprev == 1 && isset($this->slides[$j-1])){
            --$j;
          }else{
            $this->slides[$j] = &$slides[$i];
          }
          $this->slides[$j]->childs[] = &$slides[$i];
        }
      }
      if(JRequest::getVar('task') == 'add'){
        $this->slides[] = new stdClass();
      }
      $this->env->slides = &$this->slides;
      $this->calc = true;
      include($theme);
      ob_end_clean();
      $this->html.= '<script type="text/javascript">document.canvaswidth='.$canvasWidth.';document.canvasheight='.$canvasHeight.';</script>';
    }
    
    return array($c, $render, $params->getNumParams(), implode('',$GLOBALS['scripts']), $fields, 'param'.$htmlfield, $this->control_name.$this->name.'ashtml');
  }
}
?>