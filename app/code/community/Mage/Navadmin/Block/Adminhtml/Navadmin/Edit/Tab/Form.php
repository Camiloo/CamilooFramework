<?php

class Mage_Navadmin_Block_Adminhtml_Navadmin_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('navadmin_form', array('legend'=>Mage::helper('navadmin')->__('Item information')));

      $fieldset->addField('pid', 'select', array(
          'label'     => Mage::helper('navadmin')->__('Children of'),
          'name'      => 'pid',
          'values'    => Mage::helper('navadmin')->getSelectcat(),
      ));

      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('navadmin')->__('Label'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('link', 'text', array(
          'label'     => Mage::helper('navadmin')->__('Link'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'link',
      ));

      $fieldset->addField('target', 'select', array(
          'label'     => Mage::helper('navadmin')->__('Target'),
          'name'      => 'target',
          'values'    => array(
              array(
                  'value'     => 'self',
                  'label'     => Mage::helper('navadmin')->__('Self'),
              ),

              array(
                  'value'     => '_blank',
                  'label'     => Mage::helper('navadmin')->__('New window'),
              ),
          ),
      ));

      $fieldset->addField('position', 'text', array(
          'label'     => Mage::helper('navadmin')->__('Position'),
          'required'  => false,
          'name'      => 'position',
      ));

      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('navadmin')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('navadmin')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('navadmin')->__('Disabled'),
              ),
          ),
      ));


      if ( Mage::getSingleton('adminhtml/session')->getNavadminData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getNavadminData());
          Mage::getSingleton('adminhtml/session')->setNavadminData(null);
      } elseif ( Mage::registry('navadmin_data') ) {
          $form->setValues(Mage::registry('navadmin_data')->getData());
      }
      return parent::_prepareForm();
  }
}