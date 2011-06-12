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
 * @package     Mage_User
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * User address controller
 *
 * @category   Mage
 * @package    Mage_User
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_AddressController extends Mage_Core_Controller_Front_Action
{
    /**
     * Retrieve user session object
     *
     * @return Mage_User_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('user/session');
    }

    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::getSingleton('user/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }
    }

    /**
     * User addresses list
     */
    public function indexAction()
    {
        if (count($this->_getSession()->getUser()->getAddresses())) {
            $this->loadLayout();
            $this->_initLayoutMessages('user/session');
            $this->_initLayoutMessages('catalog/session');

            $block = $this->getLayout()->getBlock('address_book');
            if ($block) {
                $block->setRefererUrl($this->_getRefererUrl());
            }
            $this->renderLayout();
        } else {
            $this->getResponse()->setRedirect(Mage::getUrl('*/*/new'));
        }
    }

    public function editAction()
    {
        $this->_forward('form');
    }

    public function newAction()
    {
        $this->_forward('form');
    }

    /**
     * Address book form
     */
    public function formAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('user/session');
        $navigationBlock = $this->getLayout()->getBlock('user_account_navigation');
        if ($navigationBlock) {
            $navigationBlock->setActive('user/address');
        }
        $this->renderLayout();
    }

    public function formPostAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/');
        }
        // Save data
        if ($this->getRequest()->isPost()) {
            $user = $this->_getSession()->getUser();
            /* @var $address Mage_User_Model_Address */
            $address  = Mage::getModel('user/address');
            $addressId = $this->getRequest()->getParam('id');
            if ($addressId) {
                $existsAddress = $user->getAddressById($addressId);
                if ($existsAddress->getId() && $existsAddress->getUserId() == $user->getId()) {
                    $address->setId($existsAddress->getId());
                }
            }

            $errors = array();

            /* @var $addressForm Mage_User_Model_Form */
            $addressForm = Mage::getModel('user/form');
            $addressForm->setFormCode('user_address_edit')
                ->setEntity($address);
            $addressData    = $addressForm->extractData($this->getRequest());
            $addressErrors  = $addressForm->validateData($addressData);
            if ($addressErrors !== true) {
                $errors = $addressErrors;
            }

            try {
                $addressForm->compactData($addressData);
                $address->setUserId($user->getId())
                    ->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
                    ->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false));

                $addressErrors = $address->validate();
                if ($addressErrors !== true) {
                    $errors = array_merge($errors, $addressErrors);
                }

                if (count($errors) === 0) {
                    $address->save();
                    $this->_getSession()->addSuccess($this->__('The address has been saved.'));
                    $this->_redirectSuccess(Mage::getUrl('*/*/index', array('_secure'=>true)));
                    return;
                } else {
                    $this->_getSession()->setAddressFormData($this->getRequest()->getPost());
                    foreach ($errors as $errorMessage) {
                        $this->_getSession()->addError($errorMessage);
                    }
                }
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->setAddressFormData($this->getRequest()->getPost())
                    ->addException($e, $e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->setAddressFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Cannot save address.'));
            }
        }

        return $this->_redirectError(Mage::getUrl('*/*/edit', array('id' => $address->getId())));
    }

    public function deleteAction()
    {
        $addressId = $this->getRequest()->getParam('id', false);

        if ($addressId) {
            $address = Mage::getModel('user/address')->load($addressId);

            // Validate address_id <=> user_id
            if ($address->getUserId() != $this->_getSession()->getUserId()) {
                $this->_getSession()->addError($this->__('The address does not belong to this user.'));
                $this->getResponse()->setRedirect(Mage::getUrl('*/*/index'));
                return;
            }

            try {
                $address->delete();
                $this->_getSession()->addSuccess($this->__('The address has been deleted.'));
            } catch (Exception $e){
                $this->_getSession()->addException($e, $this->__('An error occurred while deleting the address.'));
            }
        }
        $this->getResponse()->setRedirect(Mage::getUrl('*/*/index'));
    }
}
