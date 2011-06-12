<?php
class Mage_Navadmin_Block_Adminhtml_Navadmin extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_navadmin';
    $this->_blockGroup = 'navadmin';
    $this->_headerText = Mage::helper('navadmin')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('navadmin')->__('Add Item');
    parent::__construct();
  }
}