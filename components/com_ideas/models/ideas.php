<?php
// защита от прямого доступа
defined('_JEXEC') or die('Restricted access');
// подключаем класс JModel
jimport('joomla.application.component.model');

class IdeasModelIdeas extends JModel
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

    /**
     * Загружает список админов
     *
     * @return array Список объектов
     */
    public function getAdmins()
    {
        if (empty($this->_admins))
        {
            $query 	= 'SELECT t1.*, t2.username'
                . ' FROM #__content  AS t1, #__users .  AS t2'
                . ' WHERE t2.id = t1.user_id'
                . ' ORDER BY t1.id desc';
            $this->_db->setQuery($query);
            $this->_admins = $this->_db->loadObjectList();
        }

        return $this->_admins;
    }

    public function getIdeas()
    {
        $jinput = JFactory::getApplication()->input;
        $user = $jinput->get("user");

        if ($user!=null) {
            $query 	= 'SELECT t1.*, t2.username,t4.name'
                . ' FROM #__content  AS t1, #__content_regions t3, #__regions t4,  #__users  AS t2'
                . ' WHERE t1.created_by='.$user.' and t1.id=t3.content_id and t3.region_id=t4.id and t2.id = t1.created_by'
                . ' ORDER BY t1.id desc';
            $this->_db->setQuery($query);
            $res = $this->_db->loadObjectList();

            return $res;
        }
        $region = $jinput->get("region");
        if ($region!=null) {
            $query 	= 'SELECT t1.*, t2.username,t4.name'
                . ' FROM #__content  AS t1, #__content_regions t3, #__regions t4,  #__users  AS t2'
                . ' WHERE t3.region_id='.$region.' and t1.id=t3.content_id and t3.region_id=t4.id and t2.id = t1.created_by'
                . ' ORDER BY t1.id desc';
            $this->_db->setQuery($query);
            $res = $this->_db->loadObjectList();

            return $res;
        }

        $query 	= 'SELECT t1.*, t2.username,t4.name'
            . ' FROM #__content  AS t1, #__content_regions t3, #__regions t4,  #__users  AS t2'
            . ' WHERE t1.id=t3.content_id and t3.region_id=t4.id and t2.id = t1.created_by'
            . ' ORDER BY t1.id desc';
        $this->_db->setQuery($query);
        $res = $this->_db->loadObjectList();

        return $res;
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