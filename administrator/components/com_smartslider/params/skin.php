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


class JElementSkin extends JElement
{

	var	$_name = 'Skin';

	function fetchElement($name, $value, &$node, $control_name){
    $options = array();
    $datas = array();
    $options[] = JHTML::_('select.option', 'custom', 'Custom');
    foreach($node->children() AS $default){
      $options[] = JHTML::_('select.option', $node->attributes('theme').'_'.$default->_name, ucfirst($default->_name));
      $datas[$node->attributes('theme').'_'.$default->_name] = array();
      foreach($default->_children AS $c){
        $datas[$node->attributes('theme').'_'.$default->_name][$c->_name] = $c->_data;
      }
    }
    $document = &JFactory::getDocument();
    $script = '
      if(!window.themes) window.themes = {};
      dojo.mixin(window.themes, '.json_encode($datas).');';
    if(!isset($GLOBALS['themeskinscript'])){
      $script.= '
      function OfflajnfireEvent(element,event){
          if (document.createEventObject){
          // dispatch for IE
          var evt = document.createEventObject();
          return element.fireEvent("on"+event,evt)
          }
          else{
          // dispatch for firefox + others
          var evt = document.createEvent("HTMLEvents");
          evt.initEvent(event, true, true ); // event type,bubbling,cancelable
          return !element.dispatchEvent(evt);
          }
      }
      function changeSkins(el){
        var value = el.options[el.selectedIndex].value;
        var def = eval("window.themes."+value);
        for (var k in def){
          var formel = document.adminForm["params["+k+"]"];
          if(formel.length){
            if(formel[0].nodeName == "INPUT"){
              for(var i=0; i<formel.length; i++){
                if(formel[i].value == def[k]){
                  formel[i].checked = true;
                }
              }
            }else if(formel[0].nodeName == "OPTION"){
              for(var i=0; i<formel.length; i++){
                if(formel[i].value == def[k]){
                  formel.selectedIndex = formel[i].index;
                }
              }
            }
          }else{
            try{
              var e = dojo.byId("params"+k);
              e.value = def[k];
              //e.onchange();
              OfflajnfireEvent(e,"keyup");
              OfflajnfireEvent(e,"change");
            }catch(e){
            };
          }
        }
        if(window.skinspan) dojo.destroy(window.skinspan);
        if(value != "custom")
          window.skinspan = dojo.create("span", {style: "margin-left: 10px;", innerHTML: "The <b>"+value.replace(/_/g," ").replace(/(?:^|\s)\w/g, function(match) {
        return match.toUpperCase();
    })+" skin</b> parameters have been set."}, el.parentNode, "last");
        el.selectedIndex = 0;
      }';
      $GLOBALS['themeskinscript'] = 1;
    }
    $document->addScriptDeclaration($script);
    
		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox" onchange="changeSkins(this);"', 'value', 'text', $value);
	}
}