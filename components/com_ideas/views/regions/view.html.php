<?php
// защита от прямого доступа
defined('_JEXEC') or die('Restricted access');
// подключаем класс JView
jimport('joomla.application.component.view');

class IdeasViewRegions extends JView
{
    function display($tpl = null)
    {
        // получаем список админов
        $rows = $this->get('Regions');

        // присваиваем список виду
        $this->assignRef('rows', $rows);
        // отображаем наш вид
        parent::display($tpl);
    }
}