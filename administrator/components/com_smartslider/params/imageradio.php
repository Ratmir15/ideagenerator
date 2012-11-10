<?php 
/*------------------------------------------------------------------------
# mod_jo_accordion - Vertical Accordion Menu for Joomla 1.5 
# ------------------------------------------------------------------------
# author    Roland Soos 
# copyright Copyright (C) 2011 Offlajn.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.offlajn.com
-------------------------------------------------------------------------*/
?>
<?php

class JElementImageRadio extends JElement{
	
  var	$_name = 'ImageRadio';
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		jimport( 'joomla.filesystem.folder' );
		jimport( 'joomla.filesystem.file' );

		// path to images directory
		$path		= JPATH_ROOT.DS.$node->attributes('directory');
		$filter		= '\.png$|\.gif$|\.jpg$|\.bmp$|\.ico$';
		$exclude	= $node->attributes('exclude');
		$stripExt	= $node->attributes('stripext');
		$desc	= "<span style='float: right; clear: both;'><br />".JText::_($node->attributes('description'))."</span>";
		$files		= JFolder::files($path, $filter);

		$options = array ();

		
    $imageurl = JURI::root().$node->attributes('directory').'/';


		if (!$node->attributes('hide_none'))
		{
			$options[] = JHTML::_('select.option', '-1', '- '.JText::_('None').' -');
		}

		if ( is_array($files) )
		{
			foreach ($files as $file)
			{
				if ($exclude)
				{
					if (preg_match( chr( 1 ) . $exclude . chr( 1 ), $file ))
					{
						continue;
					}
				}
				if ($stripExt)
				{
					$file = JFile::stripExt( $file );
				}
				$options[] = JHTML::_('select.option', $file, '<img src="'.str_replace('\\','/',$imageurl.$file).'" />');
			}
		}
		if(version_compare(JVERSION,'1.6.0','<') || version_compare(JVERSION,'1.7.0','>=')){
		  return "<div class='imagelist' style='float: left; clear: left;'>".JHTML::_('select.radiolist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value, $control_name.$name).$desc."</div>";
    }else{
		  return "<div class='imagelist' style='float: left; clear: left;'>".$this->radiolist($options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value, $control_name.$name).$desc."</div>";
    }
	}
  
  function radiolist(
		$data, $name, $attribs = null, $optKey = 'value', $optText = 'text',
		$selected = null, $idtag = false, $translate = false
	) {
		reset($data);
		$html = '';

		if (is_array($attribs)) {
			$attribs = JArrayHelper::toString($attribs);
		}

		$id_text = $idtag ? $idtag : $name;

		foreach ($data as $ind => $obj)
		{
			$k  = $obj->$optKey;
			$t  = $translate ? JText::_($obj->$optText) : $obj->$optText;
			$id = (isset($obj->id) ? $obj->id : null);

			$extra  = '';
			$extra  .= $id ? ' id="' . $obj->id . '"' : '';
			if (is_array($selected))
			{
				foreach ($selected as $val)
				{
					$k2 = is_object($val) ? $val->$optKey : $val;
					if ($k == $k2)
					{
						$extra .= ' selected="selected"';
						break;
					}
				}
			} else {
				$extra .= ((string)$k == (string)$selected ? ' checked="checked"' : '');
			}
			$html .= "\n\t" .'<input type="radio" name="' . $name . '"'
				. ' id="' . $id_text . $k . '" value="' . $k .'"'
				. ' ' . $extra . ' ' . $attribs . '/>'
				. "\n\t" . '<label for="' . $id_text . $k . '"'
				. ' id="' . $id_text . $k . '-lbl" class="radiobtn">'.$t.'</label>';
		}
		$html .= "\n";
		return $html;
	}
	
}