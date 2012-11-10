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

class JElementSliderType extends JElement{

  var $_moduleName = '';
  
  var $_name = 'SliderType';
  
  var $_types = array();

  function render(&$xmlElement, $value, $control_name = 'params'){
    $this->p = &$this->_parent;
    $this->typesDir = JPATH_SITE . DS . 'modules' . DS . 'mod_smartslider' . DS . 'types';
  	$name	= $xmlElement->attributes('name');
  	$label	= $xmlElement->attributes('label');
  	$descr	= $xmlElement->attributes('description');
  	//make sure we have a valid label
		$label = $label ? $label : $name;
		$result[0] = $this->fetchTooltip($label, $descr, $xmlElement, $control_name, $name);
		$result[1] = $this->fetchElement($name, $value, $xmlElement, $control_name);
		$result[2] = $descr;
		$result[3] = $label;
		$result[4] = $value;
		$result[5] = $name;

		return $result;
  }
 
  function fetchElement($name, $value, &$node, $control_name){
    $this->control_name = $control_name;
    $document =& JFactory::getDocument();
    $document->addScript(JURI::root().'modules/mod_smartslider/js/dojo.js');
    $document->addScript(JURI::base().'components/com_smartslider/params/slidertype.js');
    
    $typeSelector = $this->generateTypeSelector();
    
    $document->addScriptDeclaration('
      dojo.addOnLoad(function(){
        var theme = new ThemeConfigurator({
          data: '.json_encode($this->_types).'
        });
      });
    ');
    return $typeSelector;
  }
  
  function generateTypeSelector(){
    $types = JFolder::folders($this->typesDir);
    
    $options = array();
    
    if(is_array($types)){
      foreach($types as $type){
        $options[] = JHTML::_('select.option', $type, implode(' ', preg_split('/(?=[A-Z])/', ucfirst($type))));
        $this->_types[$type] = new stdClass();
        
        $GLOBALS['scripts'] = array();
        $xmlfile = $this->typesDir . DS . $type . DS . 'type.xml';
    		$params = new JParameter( '', $xmlfile);
    		$params->addElementPath(JPATH_ADMINISTRATOR. DS .'components'. DS .'com_smartslider'. DS . 'params');
        $params->bind($this->_parent->toArray());
        //if($this->_parent->get('params'))
    		  //parseParams($params, $this->_parent->get('params'));

    		$this->_types[$type]->html = $params->render('params');
    		$this->_types[$type]->script = implode('',$GLOBALS['scripts']);
        
        /* Themes start */
        $this->_types[$type]->themes = '';
        $themesDir = $this->typesDir . DS . $type;
        $themes = JFolder::folders($themesDir);
        
        $xml = & JFactory::getXMLParser('Simple');
        $xml->loadFile(dirname(__FILE__).'/themefield.xml');
        
        $sel = &$xml->document->params[0]->param[0];
        if ( is_array($themes) ){
          foreach($themes as $theme){
            $sel->addChild('option', array('value' => $theme), 3 )->_data = ucfirst($theme);
          }
        }
        $themesparams = new JParameter( '');
        $themesparams->setXML($xml->document->params[0]);
    		$themesparams->addElementPath(JPATH_ADMINISTRATOR. DS .'components'. DS .'com_smartslider'. DS . 'params');
    		$themesparams->bind($this->_parent->toArray());
    		//if($this->_parent->get('params'))
        //  parseParams($themesparams, $this->_parent->get('params'));
        
        $this->_types[$type]->themes = new stdClass();
        
        if ( is_array($themes) ){
          foreach($themes as $theme){
            $GLOBALS['scripts'] = array();
    		    $themeparams = new JParameter( '', $this->typesDir . DS . $type . DS . $theme . DS . 'theme.xml');
    		    $themeparams->addElementPath(JPATH_ADMINISTRATOR. DS .'components'. DS .'com_smartslider'. DS . 'params');
    		    $themeparams->bind($this->_parent->toArray());
    		    
    		    //if($this->_parent->get('params'))
    		    //  parseParams($themeparams, $this->_parent->get('params'));
    		    $this->_types[$type]->themes->$theme->html = $themeparams->render('params');
    		    $this->_types[$type]->themes->$theme->script = implode('',$GLOBALS['scripts']);
          }
        }
        $this->_types[$type]->chooser = new stdClass();
        
        $this->_types[$type]->chooser->html = $themesparams->render('params');
        
        /* Themes end */
        
      }
    }
    return JHTML::_('select.genericlist',  $options, ''.$this->control_name.'[type]', 'class="inputbox"', 'value', 'text', $this->p->get('type'));
  }
  
  function generateThemeSelector(){
    $themes = JFolder::folders($this->themesdir);
    $this->themeParams = array('default' => '');
    $this->themeScripts = array('default' => '');
    
    $options = array();
    $options[] = JHTML::_('select.option', '', '- '.JText::_('Use default').' -');
    
    if ( is_array($themes) ){
    	foreach($themes as $theme){
    	  $GLOBALS['themescripts'] = array();
    		$options[] = JHTML::_('select.option', $theme, ucfirst($theme));
    		$xml = $this->themesdir.$theme.'/theme.xml';
        $params = new JParameter('', $xml, 'module');
        for($x = 0; count($params->_xml['_default']->_children) > $x; $x++){
          $c = &$params->_xml['_default']->_children[$x];
          if(isset($c->_attributes['directory'])){
            $c->_attributes['directory'] = str_replace('/', DS, '/modules/'.$this->_moduleName.'/themes/'.$theme.'/'.$c->_attributes['directory']); 
          }
        }
        if($this->_parent->_raw)
          parseParams($params, $this->_parent->_raw);
        if(defined('DEMO')){
          if(isset($_SESSION[$_REQUEST['module']."_params"])){
            $pp = new JParameter($_SESSION[$_REQUEST['module']."_params"]);
            if($pp->_raw)
              parseParams($params, $pp->_raw);
          }
        }
    		$params->addElementPath( JPATH_ROOT . str_replace('/', DS, '/modules/'.$this->_moduleName.'/params') );
    		if($theme == 'default') $theme.=2;
        $this->themeParams[$theme] = $params->render();
        $this->themeScripts[$theme] = implode(' ',$GLOBALS['themescripts']);
    	}
    }
    $themeField = JHTML::_('select.genericlist',  $options, ''.$this->control_name.'[theme]', 'class="inputbox"', 'value', 'text', $this->p->get('theme'));
    ob_start();
    include('themeselector.tmpl.php');
    $this->themeSelector = ob_get_contents();
    ob_end_clean();
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
?>