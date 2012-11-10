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

jimport('joomla.application.component.controller');
require_once('buttons'.DS.'help.php');

class SliderController extends JController{
	function display(){
    
    JSubMenuHelper::addEntry(JText::_('Dashboard'), 'index.php?option=com_smartslider', !isset($_REQUEST['controller']) || $_REQUEST['controller'] == '');
    JSubMenuHelper::addEntry(JText::_('Sliders'), 'index.php?option=com_smartslider&controller=slider', isset($_REQUEST['controller']) && $_REQUEST['controller'] == 'slider');
    JSubMenuHelper::addEntry(JText::_('Slides'), 'index.php?option=com_smartslider&controller=slide', isset($_REQUEST['controller']) && $_REQUEST['controller'] == 'slide');
    
    //ACL
      if(version_compare(JVERSION,'1.6.0','>=')) {
        JToolBarHelper::preferences( 'com_smartslider');
      }
    //ACL end
        
    /* SmartInsert plugin checker
    * If plugin not allowed, the slidegenerator and smartinsert feature could not be used
    * If not allowed, controller.php send a notice to the back-end user.
    */                     
    $db = & JFactory::GetDBO();
    $link = "";
    if (version_compare(JVERSION,'1.6.0','>=')) {
      $query = "SELECT ".$db->nameQuote('enabled').", ".$db->nameQuote('extension_id')
                ." FROM ".$db->nameQuote('#__extensions')
                ." WHERE ".$db->nameQuote('element')." = ".$db->quote('smartsliderinsert');
      $link = JRoute::_( 'index.php?option=com_plugins&view=plugin&layout=edit&task=plugin.edit&extension_id=' );
    } else {
      $query = "SELECT ".$db->nameQuote('published').", ".$db->nameQuote('id')
                ." FROM ".$db->nameQuote('#__plugins')
                ." WHERE ".$db->nameQuote('element')." = ".$db->quote('smartsliderinsert');
      $link = JRoute::_( 'index.php?option=com_plugins&view=plugin&client=site&task=edit&cid[]=' );
    }
    $db->setQuery($query);
    $result = $db->LoadRow();
    if ((int)$result[0] == 0) {
      JError::raiseNotice( 100, JText::_('Smartinsert plugin not enabled!') .'<a href=\''.$link.$result[1].'\'>'.JText::_( ' Click here' ).'</a>'. JText::_(' to enable plugin. ') );
    }
        
    if(!isset($_REQUEST['controller']) || $_REQUEST['controller'] == ''){
      JToolBarHelper::title(   JText::_( 'Smart Slider Dashboard' ), 'generic.png' );
      echo '
<iframe frameBorder="0" src="http://smartslider.demo.offlajn.com/dashboard.html?tmpl=component" width="696" height="595">
  <p>Your browser does not support iframes.</p>
</iframe>';
    }else{
		  parent::display();
		}
    $bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'HelpSlider');
	}
}
//echo JText::_('Error: You must specify the slider first. Use the "Add new Slide" button.');