<?php
// защита от прямого доступа
defined('_JEXEC') or die('Restricted access');
// подключаем класс JModel
jimport('joomla.application.component.model');

class IdeasModelRegions extends JModel
{
    /**
     * Список админов
     *
     * @var array Список объектов
     */
    private $_admins;

    /**
     * Конструктор
     */
    function __construct()
    {
        parent::__construct();
    }


    public function getRegions()
    {
        $query 	= 'SELECT t1.*'
            . ' FROM #__regions t1 '
            . ' ORDER BY t1.name asc';
        $this->_db->setQuery($query);
        $res = $this->_db->loadObjectList();

        return $res;
    }

}