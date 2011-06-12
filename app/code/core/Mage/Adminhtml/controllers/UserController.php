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
 * User admin controller
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_UserController extends Mage_Adminhtml_Controller_Action
{

    protected function _initUser($idFieldName = 'id')
    {
        $this->_title($this->__('Users'))->_title($this->__('Manage Users'));

        $userId = (int) $this->getRequest()->getParam($idFieldName);
        $user = Mage::getModel('user/user');

        if ($userId) {
            $user->load($userId);
        }

        Mage::register('current_user', $user);
        return $this;
    }

    /**
     * Users list action
     */
    public function indexAction()
    {
        $this->_title($this->__('Users'))->_title($this->__('Manage Users'));

        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }
        $this->loadLayout();

        /**
         * Set active menu item
         */
        $this->_setActiveMenu('user/manage');

        /**
         * Append users block to content
         */
        $this->_addContent(
            $this->getLayout()->createBlock('adminhtml/user', 'user')
        );

        /**
         * Add breadcrumb item
         */
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Users'), Mage::helper('adminhtml')->__('Users'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Users'), Mage::helper('adminhtml')->__('Manage Users'));

        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->createBlock('adminhtml/user_grid')->toHtml());
    }

    /**
     * User edit action
     */
    public function editAction()
    {
        $this->_initUser();
        $this->loadLayout();

        /* @var $user Mage_User_Model_User */
        $user = Mage::registry('current_user');

        // set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getUserData(true);

        // restore data from SESSION
        if ($data) {
            $request = clone $this->getRequest();
            $request->setParams($data);

            if (isset($data['account'])) {
                /* @var $userForm Mage_User_Model_Form */
                $userForm = Mage::getModel('user/form');
                $userForm->setEntity($user)
                    ->setFormCode('adminhtml_user')
                    ->setIsAjaxRequest(true);
                $formData = $userForm->extractData($request, 'account');
                $userForm->restoreData($formData);
            }

            if (isset($data['address']) && is_array($data['address'])) {
                /* @var $addressForm Mage_User_Model_Form */
                $addressForm = Mage::getModel('user/form');
                $addressForm->setFormCode('adminhtml_user_address');

                foreach (array_keys($data['address']) as $addressId) {
                    if ($addressId == '_template_') {
                        continue;
                    }

                    $address = $user->getAddressItemById($addressId);
                    if (!$address) {
                        $address = Mage::getModel('user/address');
                        $user->addAddress($address);
                    }

                    $formData = $addressForm->setEntity($address)
                        ->extractData($request);
                    $addressForm->restoreData($formData);
                }
            }
        }

        $this->_title($user->getId() ? $user->getName() : $this->__('New User'));

        /**
         * Set active menu item
         */
        $this->_setActiveMenu('user/new');

        $this->renderLayout();
    }

    /**
     * Create new user action
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Delete user action
     */
    public function deleteAction()
    {
        $this->_initUser();
        $user = Mage::registry('current_user');
        if ($user->getId()) {
            try {
                $user->load($user->getId());
                $user->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The user has been deleted.'));
            }
            catch (Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/user');
    }

    /**
     * Save user action
     */
    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
        if ($data) {
            $redirectBack   = $this->getRequest()->getParam('back', false);
            $this->_initUser('user_id');

            /* @var $user Mage_User_Model_User */
            $user = Mage::registry('current_user');

            /* @var $userForm Mage_User_Model_Form */
            $userForm = Mage::getModel('user/form');
            $userForm->setEntity($user)
                ->setFormCode('adminhtml_user')
                ->ignoreInvisible(false)
            ;

            $formData   = $userForm->extractData($this->getRequest(), 'account');
            $errors     = $userForm->validateData($formData);
            if ($errors !== true) {
                foreach ($errors as $error) {
                    $this->_getSession()->addError($error);
                }
                $this->_getSession()->setUserData($data);
                $this->getResponse()->setRedirect($this->getUrl('*/user/edit', array('id' => $user->getId())));
                return;
            }

            $userForm->compactData($formData);

            // unset template data
            if (isset($data['address']['_template_'])) {
                unset($data['address']['_template_']);
            }

            $modifiedAddresses = array();
            if (!empty($data['address'])) {
                /* @var $addressForm Mage_User_Model_Form */
                $addressForm = Mage::getModel('user/form');
                $addressForm->setFormCode('adminhtml_user_address')->ignoreInvisible(false);

                foreach (array_keys($data['address']) as $index) {
                    $address = $user->getAddressItemById($index);
                    if (!$address) {
                        $address   = Mage::getModel('user/address');
                    }

                    $requestScope = sprintf('address/%s', $index);
                    $formData = $addressForm->setEntity($address)
                        ->extractData($this->getRequest(), $requestScope);
                    $errors   = $addressForm->validateData($formData);
                    if ($errors !== true) {
                        foreach ($errors as $error) {
                            $this->_getSession()->addError($error);
                        }
                        $this->_getSession()->setUserData($data);
                        $this->getResponse()->setRedirect($this->getUrl('*/user/edit', array(
                            'id' => $user->getId())
                        ));
                        return;
                    }

                    $addressForm->compactData($formData);

                    // Set post_index for detect default billing and shipping addresses
                    $address->setPostIndex($index);

                    if ($address->getId()) {
                        $modifiedAddresses[] = $address->getId();
                    } else {
                        $user->addAddress($address);
                    }
                }
            }

            // default billing and shipping
            if (isset($data['account']['default_billing'])) {
                $user->setData('default_billing', $data['account']['default_billing']);
            }
            if (isset($data['account']['default_shipping'])) {
                $user->setData('default_shipping', $data['account']['default_shipping']);
            }
            if (isset($data['account']['confirmation'])) {
                $user->setData('confirmation', $data['account']['confirmation']);
            }

            // not modified user addresses mark for delete
            foreach ($user->getAddressesCollection() as $userAddress) {
                if ($userAddress->getId() && !in_array($userAddress->getId(), $modifiedAddresses)) {
                    $userAddress->setData('_deleted', true);
                }
            }

            if (isset($data['subscription'])) {
                $user->setIsSubscribed(true);
            } else {
                $user->setIsSubscribed(false);
            }

            if (isset($data['account']['sendemail_store_id'])) {
                $user->setSendemailStoreId($data['account']['sendemail_store_id']);
            }

            $isNewUser = $user->isObjectNew();
            try {
                $sendPassToEmail = false;
                // force new user active
                if ($isNewUser) {
                    $user->setPassword($data['account']['password']);
                    $user->setForceConfirmed(true);
                    if ($user->getPassword() == 'auto') {
                        $sendPassToEmail = true;
                        $user->setPassword($user->generatePassword());
                    }
                }

                Mage::dispatchEvent('adminhtml_user_prepare_save', array(
                    'user'  => $user,
                    'request'   => $this->getRequest()
                ));

                $user->save();

                // send welcome email
                if ($user->getWebsiteId() && (isset($data['account']['sendemail']) || $sendPassToEmail)) {
                    $storeId = $user->getSendemailStoreId();
                    if ($isNewUser) {
                        $user->sendNewAccountEmail('registered', '', $storeId);
                    }
                    // confirm not confirmed user
                    else if ((!$user->getConfirmation())) {
                        $user->sendNewAccountEmail('confirmed', '', $storeId);
                    }
                }

                if (!empty($data['account']['new_password'])) {
                    $newPassword = $data['account']['new_password'];
                    if ($newPassword == 'auto') {
                        $newPassword = $user->generatePassword();
                    }
                    $user->changePassword($newPassword);
                    $user->sendPasswordReminderEmail();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('The user has been saved.')
                );
                Mage::dispatchEvent('adminhtml_user_save_after', array(
                    'user'  => $user,
                    'request'   => $this->getRequest()
                ));

                if ($redirectBack) {
                    $this->_redirect('*/*/edit', array(
                        'id'    => $user->getId(),
                        '_current'=>true
                    ));
                    return;
                }
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_getSession()->setUserData($data);
                $this->getResponse()->setRedirect($this->getUrl('*/user/edit', array('id' => $user->getId())));
            } catch (Exception $e) {
                $this->_getSession()->addException($e,
                    Mage::helper('adminhtml')->__('An error occurred while saving the user.'));
                $this->_getSession()->setUserData($data);
                $this->getResponse()->setRedirect($this->getUrl('*/user/edit', array('id'=>$user->getId())));
                return;
            }
        }
        $this->getResponse()->setRedirect($this->getUrl('*/user'));
    }

    /**
     * Export user grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName   = 'users.csv';
        $content    = $this->getLayout()->createBlock('adminhtml/user_grid')
            ->getCsvFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export user grid to XML format
     */
    public function exportXmlAction()
    {
        $fileName   = 'users.xml';
        $content    = $this->getLayout()->createBlock('adminhtml/user_grid')
            ->getExcelFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Prepare file download response
     *
     * @todo remove in 1.3
     * @deprecated please use $this->_prepareDownloadResponse()
     * @see Mage_Adminhtml_Controller_Action::_prepareDownloadResponse()
     * @param string $fileName
     * @param string $content
     * @param string $contentType
     */
    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $this->_prepareDownloadResponse($fileName, $content, $contentType);
    }

    /**
     * User orders grid
     *
     */
    public function ordersAction() {
        $this->_initUser();
        $this->getResponse()->setBody($this->getLayout()->createBlock('adminhtml/user_edit_tab_orders')->toHtml());
    }

    /**
     * User last orders grid for ajax
     *
     */
    public function lastOrdersAction() {
        $this->_initUser();
        $this->getResponse()->setBody($this->getLayout()->createBlock('adminhtml/user_edit_tab_view_orders')->toHtml());
    }

    /**
     * User newsletter grid
     *
     */
    public function newsletterAction()
    {
        $this->_initUser();
        $subscriber = Mage::getModel('newsletter/subscriber')
            ->loadByUser(Mage::registry('current_user'));

        Mage::register('subscriber', $subscriber);
        $this->getResponse()->setBody($this->getLayout()->createBlock('adminhtml/user_edit_tab_newsletter_grid')->toHtml());
    }

    public function wishlistAction()
    {
        $this->_initUser();
        $user = Mage::registry('current_user');
        if ($user->getId()) {
            if($itemId = (int) $this->getRequest()->getParam('delete')) {
                try {
                    Mage::getModel('wishlist/item')->load($itemId)
                        ->delete();
                }
                catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }

        $this->getLayout()->getUpdate()
            ->addHandle(strtolower($this->getFullActionName()));
        $this->loadLayoutUpdates()->generateLayoutXml()->generateLayoutBlocks();

        $this->renderLayout();
    }

    /**
     * User last view wishlist for ajax
     *
     */
    public function viewWishlistAction()
    {
        $this->_initUser();
        $this->getResponse()->setBody($this->getLayout()->createBlock('adminhtml/user_edit_tab_view_wishlist')->toHtml());
    }

    /**
     * [Handle and then] get a cart grid contents
     *
     * @return string
     */
    public function cartAction()
    {
        $this->_initUser();
        $websiteId = $this->getRequest()->getParam('website_id');

        // delete an item from cart
        if ($deleteItemId = $this->getRequest()->getPost('delete')) {
            $quote = Mage::getModel('sales/quote')
                ->setWebsite(Mage::app()->getWebsite($websiteId))
                ->loadByUser(Mage::registry('current_user'));
            $item = $quote->getItemById($deleteItemId);
            if ($item && $item->getId()) {
                $quote->removeItem($deleteItemId);
                $quote->collectTotals()->save();
            }
        }

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('adminhtml/user_edit_tab_cart', '', array('website_id'=>$websiteId))
                ->toHtml()
        );
    }

    /**
     * Get shopping cart to view only
     *
     */
    public function viewCartAction()
    {
        $this->_initUser();

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('adminhtml/user_edit_tab_view_cart')
                ->setWebsiteId($this->getRequest()->getParam('website_id'))
                ->toHtml()
        );
    }

    /**
     * Get shopping carts from all websites for specified client
     *
     * @return string
     */
    public function cartsAction()
    {
        $this->_initUser();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('adminhtml/user_edit_tab_carts')->toHtml()
        );
    }

    public function productReviewsAction()
    {
        $this->_initUser();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('adminhtml/user_edit_tab_reviews', 'admin.user.reviews')
                ->setUserId(Mage::registry('current_user')->getId())
                ->setUseAjax(true)
                ->toHtml()
        );
    }

    public function productTagsAction()
    {
        $this->_initUser();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('adminhtml/user_edit_tab_tag', 'admin.user.tags')
                ->setUserId(Mage::registry('current_user')->getId())
                ->setUseAjax(true)
                ->toHtml()
        );
    }

    public function tagGridAction()
    {
        $this->_initUser();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('adminhtml/user_edit_tab_tag', 'admin.user.tags')
                ->setUserId(Mage::registry('current_user'))
                ->toHtml()
        );
    }

    public function validateAction()
    {
        $response       = new Varien_Object();
        $response->setError(0);
        $websiteId      = Mage::app()->getStore()->getWebsiteId();
        $accountData    = $this->getRequest()->getPost('account');

        $user = Mage::getModel('user/user');
        $userId = $this->getRequest()->getParam('id');
        if ($userId) {
            $user->load($userId);
            $websiteId = $user->getWebsiteId();
        } else if (isset($accountData['website_id'])) {
            $websiteId = $accountData['website_id'];
        }

        /* @var $userForm Mage_User_Model_Form */
        $userForm = Mage::getModel('user/form');
        $userForm->setEntity($user)
            ->setFormCode('adminhtml_user')
            ->setIsAjaxRequest(true)
            ->ignoreInvisible(false)
        ;

        $data   = $userForm->extractData($this->getRequest(), 'account');
        $errors = $userForm->validateData($data);
        if ($errors !== true) {
            foreach ($errors as $error) {
                $this->_getSession()->addError($error);
            }
            $response->setError(1);
        }

        # additional validate email
        if (!$response->getError()) {
            # Trying to load user with the same email and return error message
            # if user with the same email address exisits
            $checkUser = Mage::getModel('user/user')
                ->setWebsiteId($websiteId);
            $checkUser->loadByEmail($accountData['email']);
            if ($checkUser->getId() && ($checkUser->getId() != $user->getId())) {
                $response->setError(1);
                $this->_getSession()->addError(
                    Mage::helper('adminhtml')->__('User with the same email already exists.')
                );
            }
        }

        $addressesData = $this->getRequest()->getParam('address');
        if (is_array($addressesData)) {
            /* @var $addressForm Mage_User_Model_Form */
            $addressForm = Mage::getModel('user/form');
            $addressForm->setFormCode('adminhtml_user_address')->ignoreInvisible(false);
            foreach (array_keys($addressesData) as $index) {
                if ($index == '_template_') {
                    continue;
                }
                $address = $user->getAddressItemById($index);
                if (!$address) {
                    $address   = Mage::getModel('user/address');
                }

                $requestScope = sprintf('address/%s', $index);
                $formData = $addressForm->setEntity($address)
                    ->extractData($this->getRequest(), $requestScope);

                $errors = $addressForm->validateData($formData);
                if ($errors !== true) {
                    foreach ($errors as $error) {
                        $this->_getSession()->addError($error);
                    }
                    $response->setError(1);
                }
            }
        }

        if ($response->getError()) {
            $this->_initLayoutMessages('adminhtml/session');
            $response->setMessage($this->getLayout()->getMessagesBlock()->getGroupedHtml());
        }

        $this->getResponse()->setBody($response->toJson());
    }

    public function massSubscribeAction()
    {
        $usersIds = $this->getRequest()->getParam('user');
        if(!is_array($usersIds)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select user(s).'));

        } else {
            try {
                foreach ($usersIds as $userId) {
                    $user = Mage::getModel('user/user')->load($userId);
                    $user->setIsSubscribed(true);
                    $user->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were updated.', count($usersIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massUnsubscribeAction()
    {
        $usersIds = $this->getRequest()->getParam('user');
        if(!is_array($usersIds)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select user(s).'));
        } else {
            try {
                foreach ($usersIds as $userId) {
                    $user = Mage::getModel('user/user')->load($userId);
                    $user->setIsSubscribed(false);
                    $user->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were updated.', count($usersIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    public function massDeleteAction()
    {
        $usersIds = $this->getRequest()->getParam('user');
        if(!is_array($usersIds)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select user(s).'));
        } else {
            try {
                $user = Mage::getModel('user/user');
                foreach ($usersIds as $userId) {
                    $user->reset()
                        ->load($userId)
                        ->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were deleted.', count($usersIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    public function massAssignGroupAction()
    {
        $usersIds = $this->getRequest()->getParam('user');
        if(!is_array($usersIds)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select user(s).'));
        } else {
            try {
                foreach ($usersIds as $userId) {
                    $user = Mage::getModel('user/user')->load($userId);
                    $user->setGroupId($this->getRequest()->getParam('group'));
                    $user->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were updated.', count($usersIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    public function viewfileAction()
    {
        $file   = null;
        $plain  = false;
        if ($this->getRequest()->getParam('file')) {
            // download file
            $file   = Mage::helper('core')->urlDecode($this->getRequest()->getParam('file'));
        } else if ($this->getRequest()->getParam('image')) {
            // show plain image
            $file   = Mage::helper('core')->urlDecode($this->getRequest()->getParam('image'));
            $plain  = true;
        } else {
            return $this->norouteAction();
        }

        $path = Mage::getBaseDir('media') . DS . 'user';

        $ioFile = new Varien_Io_File();
        $ioFile->open(array('path' => $path));
        $fileName   = $ioFile->getCleanPath($path . $file);
        $path       = $ioFile->getCleanPath($path);

        if ((!$ioFile->fileExists($fileName) || strpos($fileName, $path) !== 0)
            && !Mage::helper('core/file_storage')->processStorageFile(str_replace('/', DS, $fileName))
        ) {
            return $this->norouteAction();
        }

        if ($plain) {
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            switch (strtolower($extension)) {
                case 'gif':
                    $contentType = 'image/gif';
                    break;
                case 'jpg':
                    $contentType = 'image/jpeg';
                    break;
                case 'png':
                    $contentType = 'image/png';
                    break;
                default:
                    $contentType = 'application/octet-stream';
                    break;
            }

            $ioFile->streamOpen($fileName, 'r');
            $contentLength = $ioFile->streamStat('size');
            $contentModify = $ioFile->streamStat('mtime');

            $this->getResponse()
                ->setHttpResponseCode(200)
                ->setHeader('Pragma', 'public', true)
                ->setHeader('Content-type', $contentType, true)
                ->setHeader('Content-Length', $contentLength)
                ->setHeader('Last-Modified', date('r', $contentModify))
                ->clearBody();
            $this->getResponse()->sendHeaders();

            while (false !== ($buffer = $ioFile->streamRead())) {
                echo $buffer;
            }
        } else {
            $name = pathinfo($fileName, PATHINFO_BASENAME);
            $this->_prepareDownloadResponse($name, array(
                'type'  => 'filename',
                'value' => $fileName
            ));
        }

        exit();
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('user/manage');
    }

    /**
     * Filtering posted data. Converting localized data if needed
     *
     * @param array
     * @return array
     */
    protected function _filterPostData($data)
    {
        $data['account'] = $this->_filterDates($data['account'], array('dob'));
        return $data;
    }
}
