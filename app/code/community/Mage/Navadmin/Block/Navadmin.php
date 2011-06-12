<?php
class Mage_Navadmin_Block_Navadmin extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getNavadmin()     
     { 
        if (!$this->hasData('navadmin')) {
            $this->setData('navadmin', Mage::registry('navadmin'));
        }
        return $this->getData('navadmin');
        
    }
}