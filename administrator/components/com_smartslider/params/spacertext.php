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

class JElementSpacertext extends JElement
{

	var	$_name = 'SpacerText';

	function fetchElement($name, $value, &$node, $control_name){
    $value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES), ENT_QUOTES);

		return '<input disabled type="text" name="'.$control_name.'['.$name.']" id="'.$control_name.$name.'" value="'.$value.'" />';
	}
}