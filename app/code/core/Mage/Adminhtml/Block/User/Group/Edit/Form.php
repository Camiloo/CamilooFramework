<?php
/**
 * Camilooframework
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Camilooframework to newer
 * versions in the future. If you wish to customize Camilooframework for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml user groups edit form
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_User_Group_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare form for render
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $form = new Varien_Data_Form();
        $userGroup = Mage::registry('current_group');

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('user')->__('Group Information')));

        $name = $fieldset->addField('user_group_code', 'text',
            array(
                'name'  => 'code',
                'label' => Mage::helper('user')->__('Group Name'),
                'title' => Mage::helper('user')->__('Group Name'),
                'class' => 'required-entry',
                'required' => true,
            )
        );

        if ($userGroup->getId()==0 && $userGroup->getUserGroupCode() ) {
            $name->setDisabled(true);
        }

        //$fieldset->addField('tax_class_id', 'select',
        //    array(
        //        'name'  => 'tax_class',
        //        'label' => Mage::helper('user')->__('Tax Class'),
        //         'title' => Mage::helper('user')->__('Tax Class'),
        //        'class' => 'required-entry',
        //        'required' => true,
        //        'values' => Mage::getSingleton('tax/class_source_user')->toOptionArray()
        //    )
        //);

        if (!is_null($userGroup->getId())) {
            // If edit add id
            $form->addField('id', 'hidden',
                array(
                    'name'  => 'id',
                    'value' => $userGroup->getId(),
                )
            );
        }

        if( Mage::getSingleton('adminhtml/session')->getUserGroupData() ) {
            $form->addValues(Mage::getSingleton('adminhtml/session')->getUserGroupData());
            Mage::getSingleton('adminhtml/session')->setUserGroupData(null);
        } else {
            $form->addValues($userGroup->getData());
        }

        $form->setUseContainer(true);
        $form->setId('edit_form');
        $form->setAction($this->getUrl('*/*/save'));
        $this->setForm($form);
    }
}
