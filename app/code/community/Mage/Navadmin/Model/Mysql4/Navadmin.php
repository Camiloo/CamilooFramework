<?php

class Mage_Navadmin_Model_Mysql4_Navadmin extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the navadmin_id refers to the key field in your database table.
        $this->_init('navadmin/navadmin', 'navadmin_id');
    }
}