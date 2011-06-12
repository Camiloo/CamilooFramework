<?php

class Mage_Navadmin_Block_Adminhtml_Navadmin_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('navadmin_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('navadmin')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('navadmin')->__('Item Information'),
          'title'     => Mage::helper('navadmin')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('navadmin/adminhtml_navadmin_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}