<?php
/**
 * @version     1.0.0
 * @package     com_ideas
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ratmir Panov <ratmirpanov@gmail.com> - http://vk.com/ratmirpanov
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

// Execute the task.
$controller	= JController::getInstance('Ideas');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
