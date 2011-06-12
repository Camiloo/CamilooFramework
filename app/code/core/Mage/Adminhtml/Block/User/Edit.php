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
 * User edit block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_User_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'user';

       // if ($this->getUserId() &&
       //     Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/create')) {
       //     $this->_addButton('order', array(
       //         'label' => Mage::helper('user')->__('Create Order'),
       //         'onclick' => 'setLocation(\'' . $this->getCreateOrderUrl() . '\')',
       //         'class' => 'add',
       //     ), 0);
       // }

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('user')->__('Save User'));
        $this->_updateButton('delete', 'label', Mage::helper('user')->__('Delete User'));

        if (Mage::registry('current_user')->isReadonly()) {
            $this->_removeButton('save');
            $this->_removeButton('reset');
        }

        if (!Mage::registry('current_user')->isDeleteable()) {
            $this->_removeButton('delete');
        }
    }

    public function getCreateOrderUrl()
    {
        return $this->getUrl('*/sales_order_create/start', array('user_id' => $this->getUserId()));
    }

    public function getUserId()
    {
        return Mage::registry('current_user')->getId();
    }

    public function getHeaderText()
    {
        if (Mage::registry('current_user')->getId()) {
            return $this->htmlEscape(Mage::registry('current_user')->getName());
        }
        else {
            return Mage::helper('user')->__('New User');
        }
    }

    /**
     * Prepare form html. Add block for configurable product modification interface
     *
     * @return string
     */
    public function getFormHtml()
    {
        $html = parent::getFormHtml();
        //$html .= $this->getLayout()->createBlock('adminhtml/catalog_product_composite_configure')->toHtml();
        return $html;
    }

    public function getValidationUrl()
    {
        return $this->getUrl('*/*/validate', array('_current'=>true));
    }

    protected function _prepareLayout()
    {
        if (!Mage::registry('current_user')->isReadonly()) {
            $this->_addButton('save_and_continue', array(
                'label'     => Mage::helper('user')->__('Save and Continue Edit'),
                'onclick'   => 'saveAndContinueEdit(\''.$this->_getSaveAndContinueUrl().'\')',
                'class'     => 'save'
            ), 10);
        }

        return parent::_prepareLayout();
    }

    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
            '_current'  => true,
            'back'      => 'edit',
            'tab'       => '{{tab_id}}'
        ));
    }
}
