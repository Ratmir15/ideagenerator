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

jimport( 'joomla.application.component.view' );


class SliderViewSliders extends JView{

	function display($tpl = null){
	  $mainframe = &JFactory::getApplication();
		JToolBarHelper::title(   JText::_( 'Slider Manager' ), 'generic.png' );
		JToolBarHelper::deleteList();
		JToolBarHelper::addNewX();
		JToolBarHelper::customX( 'duplicate', 'copy.png', 'copy_f2.png', 'Duplicate' );
		
		$db =& JFactory::getDBO();

		$context			= 'com_smartsliders.sliders.list.';
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'a.name',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',			'word' );
		$filter_state		= $mainframe->getUserStateFromRequest( $context.'filter_state',		'filter_state',		'',			'word' );
		$search				= $mainframe->getUserStateFromRequest( $context.'search',			'search',			'',			'string' );
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);

		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );
		
		$where = array();

		if ( $filter_state )
		{
			if ( $filter_state == 'P' ) {
				$where[] = 'a.published = 1';
			}
			else if ($filter_state == 'U' ) {
				$where[] = 'a.published = 0';
			}
		}
		if ($search) {
			$where[] = 'LOWER(a.name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		$where		= count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
		
		$lists = array();		
		$lists['published']	= JHTML::_('grid.state',  $filter_state );
		
		
		if (!in_array($filter_order, array('a.name', 'a.id', 'a.created_by', 'a.published'))) {
			$filter_order = 'a.name';
		}

		if (!in_array(strtoupper($filter_order_Dir), array('ASC', 'DESC'))) {
			$filter_order_Dir = '';
		}
		
		$orderby	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir .', a.name';
		
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		
		$this->assignRef('lists',		$lists);
		
		$items = &$this->get('Data');
		
		$arr = &$this->getModel()->getPaginator($where, $orderby, $limitstart, $limit);
			
		$this->assignRef('pageNav', $arr[0]);
		$this->assignRef('items', $arr[1]);
		
		$user =& JFactory::getUser();
		$this->assignRef('user', $user);
		
		if(version_compare(JVERSION,'1.6.0','ge')){
      $db =& JFactory::getDBO();
      $db->setQuery("
        SELECT extension_id
        FROM #__extensions
        WHERE element LIKE 'mod_smartslider' LIMIT 1
      ");
      $this->assignRef('module_id', $db->loadResult());
    }
		
		JHTML::_('behavior.tooltip');
		
		parent::display($tpl);
	}
}