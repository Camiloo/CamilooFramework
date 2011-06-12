<?php

class Mage_Navadmin_Model_Navadmin extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('navadmin/navadmin');
    }
}