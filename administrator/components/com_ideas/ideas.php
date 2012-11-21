<?php
/**
 * @version     1.0.0
 * @package     com_ideas
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ratmir Panov <ratmirpanov@gmail.com> - http://vk.com/ratmirpanov
 */


// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_ideas')) 
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JController::getInstance('Ideas');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
