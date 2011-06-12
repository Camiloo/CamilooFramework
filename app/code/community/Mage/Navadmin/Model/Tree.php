<?php

class Mage_Navadmin_Model_Tree extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 2;

    static public function getOptionArray()
    {
		$x = array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('navadmin')->__('zzEnabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('navadmin')->__('zzDisabled'),
              ),
          );
          return $x;
    }

}