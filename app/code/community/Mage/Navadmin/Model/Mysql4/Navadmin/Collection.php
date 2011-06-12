<?php

class Mage_Navadmin_Model_Mysql4_Navadmin_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('navadmin/navadmin');
    }
}