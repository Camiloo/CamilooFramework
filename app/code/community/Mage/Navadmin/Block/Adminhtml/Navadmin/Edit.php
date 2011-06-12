<?php

class Mage_Navadmin_Block_Adminhtml_Navadmin_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'navadmin';
        $this->_controller = 'adminhtml_navadmin';
        
        $this->_updateButton('save', 'label', Mage::helper('navadmin')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('navadmin')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('navadmin_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'navadmin_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'navadmin_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('navadmin_data') && Mage::registry('navadmin_data')->getId() ) {
            return Mage::helper('navadmin')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('navadmin_data')->getTitle()));
        } else {
            return Mage::helper('navadmin')->__('Add Item');
        }
    }
}