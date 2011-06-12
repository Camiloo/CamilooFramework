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
 * User groups controller
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_User_GroupController extends Mage_Adminhtml_Controller_Action
{
    protected function _initGroup()
    {
        $this->_title($this->__('Users'))->_title($this->__('User Groups'));

        Mage::register('current_group', Mage::getModel('user/group'));
        $groupId = $this->getRequest()->getParam('id');
        if (!is_null($groupId)) {
            Mage::registry('current_group')->load($groupId);
        }

    }
    /**
     * User groups list.
     */
    public function indexAction()
    {
        $this->_title($this->__('Users'))->_title($this->__('User Groups'));

        $this->loadLayout();
        $this->_setActiveMenu('user/group');
        $this->_addBreadcrumb(Mage::helper('user')->__('Users'), Mage::helper('user')->__('Users'));
        $this->_addBreadcrumb(Mage::helper('user')->__('User Groups'), Mage::helper('user')->__('User Groups'));
        $this->renderLayout();
    }

    /**
     * Edit or create user group.
     */
    public function newAction()
    {
        $this->_initGroup();
        $this->loadLayout();
        $this->_setActiveMenu('user/group');
        $this->_addBreadcrumb(Mage::helper('user')->__('Users'), Mage::helper('user')->__('Users'));
        $this->_addBreadcrumb(Mage::helper('user')->__('User Groups'), Mage::helper('user')->__('User Groups'), $this->getUrl('*/user_group'));

        $currentGroup = Mage::registry('current_group');

        if (!is_null($currentGroup->getId())) {
            $this->_addBreadcrumb(Mage::helper('user')->__('Edit Group'), Mage::helper('user')->__('Edit User Groups'));
        } else {
            $this->_addBreadcrumb(Mage::helper('user')->__('New Group'), Mage::helper('user')->__('New User Groups'));
        }

        $this->_title($currentGroup->getId() ? $currentGroup->getCode() : $this->__('New Group'));

        $this->getLayout()->getBlock('content')
            ->append($this->getLayout()->createBlock('adminhtml/user_group_edit', 'group')
                        ->setEditMode((bool)Mage::registry('current_group')->getId()));

        $this->renderLayout();
    }

    /**
     * Edit user group action. Forward to new action.
     */
    public function editAction()
    {
        $this->_forward('new');
    }

    /**
     * Create or save user group.
     */
    public function saveAction()
    {
        $userGroup = Mage::getModel('user/group');
        $id = $this->getRequest()->getParam('id');
        if (!is_null($id)) {
            $userGroup->load($id);
        }

        if ($taxClass = $this->getRequest()->getParam('tax_class')) {
            try {
                $userGroup->setCode($this->getRequest()->getParam('code'))
                    ->setTaxClassId($taxClass)
                    ->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('user')->__('The user group has been saved.'));
                $this->getResponse()->setRedirect($this->getUrl('*/user_group'));
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setUserGroupData($userGroup->getData());
                $this->getResponse()->setRedirect($this->getUrl('*/user_group/edit', array('id' => $id)));
                return;
            }
        } else {
            $this->_forward('new');
        }

    }

    /**
     * Delete user group action
     */
    public function deleteAction()
    {
        $userGroup = Mage::getModel('user/group');
        if ($id = (int)$this->getRequest()->getParam('id')) {
            try {
                $userGroup->load($id);
                $userGroup->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('user')->__('The user group has been deleted.'));
                $this->getResponse()->setRedirect($this->getUrl('*/user_group'));
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->getResponse()->setRedirect($this->getUrl('*/user_group/edit', array('id' => $id)));
                return;
            }
        }

        $this->_redirect('*/user_group');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('user/group');
    }
}
