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
 * User account controller
 *
 * @category   Mage
 * @package    Mage_User
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_AccountController extends Mage_Core_Controller_Front_Action
{
    /**
     * Action list where need check enabled cookie
     *
     * @var array
     */
    protected $_cookieCheckActions = array('loginPost', 'createpost');

    /**
     * Retrieve user session model object
     *
     * @return Mage_User_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('user/session');
    }

    /**
     * Action predispatch
     *
     * Check user authentication for some actions
     */
    public function preDispatch()
    {
        // a brute-force protection here would be nice

        parent::preDispatch();

        if (!$this->getRequest()->isDispatched()) {
            return;
        }

        $action = $this->getRequest()->getActionName();
        $pattern = '/^(create|login|logoutSuccess|forgotpassword|forgotpasswordpost|confirm|confirmation)/i';
        if (!preg_match($pattern, $action)) {
            if (!$this->_getSession()->authenticate($this)) {
                $this->setFlag('', 'no-dispatch', true);
            }
        } else {
            $this->_getSession()->setNoReferer(true);
        }
    }

    /**
     * Action postdispatch
     *
     * Remove No-referer flag from user session after each action
     */
    public function postDispatch()
    {
        parent::postDispatch();
        $this->_getSession()->unsNoReferer(false);
    }

    /**
     * Default user account page
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('user/session');
//        $this->_initLayoutMessages('catalog/session');

        $this->getLayout()->getBlock('content')->append(
            $this->getLayout()->createBlock('user/account_dashboard')
        );
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Account'));
        $this->renderLayout();
    }

    /**
     * User login form page
     */
    public function loginAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $this->getResponse()->setHeader('Login-Required', 'true');
        $this->loadLayout();
        $this->_initLayoutMessages('user/session');
        //$this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }

    /**
     * Login post action
     */
    public function loginPostAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    $session->login($login['username'], $login['password']);
                    if ($session->getUser()->getIsJustConfirmed()) {
                        $this->_welcomeUser($session->getUser(), true);
                    }
                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_User_Model_User::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $value = Mage::helper('user')->getEmailConfirmationUrl($login['username']);
                            $message = Mage::helper('user')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
                            break;
                        case Mage_User_Model_User::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $message = $e->getMessage();
                            break;
                        default:
                            $message = $e->getMessage();
                    }
                    $session->addError($message);
                    $session->setUsername($login['username']);
                } catch (Exception $e) {
                    // Mage::logException($e); // PA DSS violation: this exception log can disclose user password
                }
            } else {
                $session->addError($this->__('Login and password are required.'));
            }
        }

        $this->_loginPostRedirect();
    }

    /**
     * Define target URL and redirect user after logging in
     */
    protected function _loginPostRedirect()
    {
        $session = $this->_getSession();

        if (!$session->getBeforeAuthUrl() || $session->getBeforeAuthUrl() == Mage::getBaseUrl()) {

            // Set default URL to redirect user to
            $session->setBeforeAuthUrl(Mage::helper('user')->getAccountUrl());
            // Redirect user to the last page visited after logging in
            if ($session->isLoggedIn()) {
                if (!Mage::getStoreConfigFlag('user/startup/redirect_dashboard')) {
                    $referer = $this->getRequest()->getParam(Mage_User_Helper_Data::REFERER_QUERY_PARAM_NAME);
                    if ($referer) {
                        $referer = Mage::helper('core')->urlDecode($referer);
                        if ($this->_isUrlInternal($referer)) {
                            $session->setBeforeAuthUrl($referer);
                        }
                    }
                } else if ($session->getAfterAuthUrl()) {
                    $session->setBeforeAuthUrl($session->getAfterAuthUrl(true));
                }
            } else {
                $session->setBeforeAuthUrl(Mage::helper('user')->getLoginUrl());
            }
        } else if ($session->getBeforeAuthUrl() == Mage::helper('user')->getLogoutUrl()) {
            $session->setBeforeAuthUrl(Mage::helper('user')->getDashboardUrl());
        } else {
            if (!$session->getAfterAuthUrl()) {
                $session->setAfterAuthUrl($session->getBeforeAuthUrl());
            }
            if ($session->isLoggedIn()) {
                $session->setBeforeAuthUrl($session->getAfterAuthUrl(true));
            }
        }
        $this->_redirectUrl($session->getBeforeAuthUrl(true));
    }

    /**
     * User logout action
     */
    public function logoutAction()
    {
        $this->_getSession()->logout()
            ->setBeforeAuthUrl(Mage::getUrl());

        $this->_redirect('*/*/logoutSuccess');
    }

    /**
     * Logout success page
     */
    public function logoutSuccessAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * User register form page
     */
    public function createAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*');
            return;
        }

        $this->loadLayout();
        $this->_initLayoutMessages('user/session');
        $this->renderLayout();
    }

    /**
     * Create user account action
     */
    public function createPostAction()
    {
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session->setEscapeMessages(true); // prevent XSS injection in user input
        if ($this->getRequest()->isPost()) {
            $errors = array();

            if (!$user = Mage::registry('current_user')) {
                $user = Mage::getModel('user/user')->setId(null);
            }

            /* @var $userForm Mage_User_Model_Form */
            $userForm = Mage::getModel('user/form');
            $userForm->setFormCode('user_account_create')
                ->setEntity($user);

            $userData = $userForm->extractData($this->getRequest());

            if ($this->getRequest()->getParam('is_subscribed', false)) {
                $user->setIsSubscribed(1);
            }

            /**
             * Initialize user group id
             */
            $user->getGroupId();

            if ($this->getRequest()->getPost('create_address')) {
                /* @var $address Mage_User_Model_Address */
                $address = Mage::getModel('user/address');
                /* @var $addressForm Mage_User_Model_Form */
                $addressForm = Mage::getModel('user/form');
                $addressForm->setFormCode('user_register_address')
                    ->setEntity($address);

                $addressData    = $addressForm->extractData($this->getRequest(), 'address', false);
                $addressErrors  = $addressForm->validateData($addressData);
                if ($addressErrors === true) {
                    $address->setId(null)
                        ->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
                        ->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false));
                    $addressForm->compactData($addressData);
                    $user->addAddress($address);

                    $addressErrors = $address->validate();
                    if (is_array($addressErrors)) {
                        $errors = array_merge($errors, $addressErrors);
                    }
                } else {
                    $errors = array_merge($errors, $addressErrors);
                }
            }

            try {
                $userErrors = $userForm->validateData($userData);
                if ($userErrors !== true) {
                    $errors = array_merge($userErrors, $errors);
                } else {
                    $userForm->compactData($userData);
                    $user->setPassword($this->getRequest()->getPost('password'));
                    $user->setConfirmation($this->getRequest()->getPost('confirmation'));
                    $userErrors = $user->validate();
                    if (is_array($userErrors)) {
                        $errors = array_merge($userErrors, $errors);
                    }
                }

                $validationResult = count($errors) == 0;

                if (true === $validationResult) {
                    $user->save();

                    if ($user->isConfirmationRequired()) {
                        $user->sendNewAccountEmail('confirmation', $session->getBeforeAuthUrl());
                        $session->addSuccess($this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.', Mage::helper('user')->getEmailConfirmationUrl($user->getEmail())));
                        $this->_redirectSuccess(Mage::getUrl('*/*/index', array('_secure'=>true)));
                        return;
                    } else {
                        $session->setUserAsLoggedIn($user);
                        $url = $this->_welcomeUser($user);
                        $this->_redirectSuccess($url);
                        return;
                    }
                } else {
                    $session->setUserFormData($this->getRequest()->getPost());
                    if (is_array($errors)) {
                        foreach ($errors as $errorMessage) {
                            $session->addError($errorMessage);
                        }
                    } else {
                        $session->addError($this->__('Invalid user data'));
                    }
                }
            } catch (Mage_Core_Exception $e) {
                $session->setUserFormData($this->getRequest()->getPost());
                if ($e->getCode() === Mage_User_Model_User::EXCEPTION_EMAIL_EXISTS) {
                    $url = Mage::getUrl('user/account/forgotpassword');
                    $message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);
                    $session->setEscapeMessages(false);
                } else {
                    $message = $e->getMessage();
                }
                $session->addError($message);
            } catch (Exception $e) {
                $session->setUserFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Cannot save the user.'));
            }
        }

        $this->_redirectError(Mage::getUrl('*/*/create', array('_secure' => true)));
    }

    /**
     * Add welcome message and send new account email.
     * Returns success URL
     *
     * @param Mage_User_Model_User $user
     * @param bool $isJustConfirmed
     * @return string
     */
    protected function _welcomeUser(Mage_User_Model_User $user, $isJustConfirmed = false)
    {
        $this->_getSession()->addSuccess(
            $this->__('Thank you for registering with %s.', Mage::app()->getStore()->getFrontendName())
        );

        $user->sendNewAccountEmail($isJustConfirmed ? 'confirmed' : 'registered');

        $successUrl = Mage::getUrl('*/*/index', array('_secure'=>true));
        if ($this->_getSession()->getBeforeAuthUrl()) {
            $successUrl = $this->_getSession()->getBeforeAuthUrl(true);
        }
        return $successUrl;
    }

    /**
     * Confirm user account by id and confirmation key
     */
    public function confirmAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        try {
            $id      = $this->getRequest()->getParam('id', false);
            $key     = $this->getRequest()->getParam('key', false);
            $backUrl = $this->getRequest()->getParam('back_url', false);
            if (empty($id) || empty($key)) {
                throw new Exception($this->__('Bad request.'));
            }

            // load user by id (try/catch in case if it throws exceptions)
            try {
                $user = Mage::getModel('user/user')->load($id);
                if ((!$user) || (!$user->getId())) {
                    throw new Exception('Failed to load user by id.');
                }
            }
            catch (Exception $e) {
                throw new Exception($this->__('Wrong user account specified.'));
            }

            // check if it is inactive
            if ($user->getConfirmation()) {
                if ($user->getConfirmation() !== $key) {
                    throw new Exception($this->__('Wrong confirmation key.'));
                }

                // activate user
                try {
                    $user->setConfirmation(null);
                    $user->save();
                }
                catch (Exception $e) {
                    throw new Exception($this->__('Failed to confirm user account.'));
                }

                // log in and send greeting email, then die happy
                $this->_getSession()->setUserAsLoggedIn($user);
                $successUrl = $this->_welcomeUser($user, true);
                $this->_redirectSuccess($backUrl ? $backUrl : $successUrl);
                return;
            }

            // die happy
            $this->_redirectSuccess(Mage::getUrl('*/*/index', array('_secure'=>true)));
            return;
        }
        catch (Exception $e) {
            // die unhappy
            $this->_getSession()->addError($e->getMessage());
            $this->_redirectError(Mage::getUrl('*/*/index', array('_secure'=>true)));
            return;
        }
    }

    /**
     * Send confirmation link to specified email
     */
    public function confirmationAction()
    {
        $user = Mage::getModel('user/user');
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }

        // try to confirm by email
        $email = $this->getRequest()->getPost('email');
        if ($email) {
            try {
                $user->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($email);
                if (!$user->getId()) {
                    throw new Exception('');
                }
                if ($user->getConfirmation()) {
                    $user->sendNewAccountEmail('confirmation');
                    $this->_getSession()->addSuccess($this->__('Please, check your email for confirmation key.'));
                } else {
                    $this->_getSession()->addSuccess($this->__('This email does not require confirmation.'));
                }
                $this->_getSession()->setUsername($email);
                $this->_redirectSuccess(Mage::getUrl('*/*/index', array('_secure' => true)));
            } catch (Exception $e) {
                $this->_getSession()->addException($e, $this->__('Wrong email.'));
                $this->_redirectError(Mage::getUrl('*/*/*', array('email' => $email, '_secure' => true)));
            }
            return;
        }

        // output form
        $this->loadLayout();

        $this->getLayout()->getBlock('accountConfirmation')
            ->setEmail($this->getRequest()->getParam('email', $email));

        $this->_initLayoutMessages('user/session');
        $this->renderLayout();
    }

    /**
     * Forgot user password page
     */
    public function forgotPasswordAction()
    {
        $this->loadLayout();

        $this->getLayout()->getBlock('forgotPassword')->setEmailValue(
            $this->_getSession()->getForgottenEmail()
        );
        $this->_getSession()->unsForgottenEmail();

        $this->_initLayoutMessages('user/session');
        $this->renderLayout();
    }

    /**
     * Forgot user password action
     */
    public function forgotPasswordPostAction()
    {
        $email = $this->getRequest()->getPost('email');
        if ($email) {
            if (!Zend_Validate::is($email, 'EmailAddress')) {
                $this->_getSession()->setForgottenEmail($email);
                $this->_getSession()->addError($this->__('Invalid email address.'));
                $this->getResponse()->setRedirect(Mage::getUrl('*/*/forgotpassword'));
                return;
            }
            $user = Mage::getModel('user/user')
                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                ->loadByEmail($email);

            if ($user->getId()) {
                try {
                    $newPassword = $user->generatePassword();
                    $user->changePassword($newPassword, false);
                    $user->sendPasswordReminderEmail();

                    $this->_getSession()->addSuccess($this->__('A new password has been sent.'));

                    $this->getResponse()->setRedirect(Mage::getUrl('*/*'));
                    return;
                }
                catch (Exception $e){
                    $this->_getSession()->addError($e->getMessage());
                }
            } else {
                $this->_getSession()->addError($this->__('This email address was not found in our records.'));
                $this->_getSession()->setForgottenEmail($email);
            }
        } else {
            $this->_getSession()->addError($this->__('Please enter your email.'));
            $this->getResponse()->setRedirect(Mage::getUrl('*/*/forgotpassword'));
            return;
        }

        $this->getResponse()->setRedirect(Mage::getUrl('*/*/forgotpassword'));
    }

    /**
     * Forgot user account information page
     */
    public function editAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('user/session');
       // $this->_initLayoutMessages('catalog/session');

        $block = $this->getLayout()->getBlock('user_edit');
        if ($block) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
        $data = $this->_getSession()->getUserFormData(true);
        $user = $this->_getSession()->getUser();
        if (!empty($data)) {
            $user->addData($data);
        }
        if ($this->getRequest()->getParam('changepass')==1){
            $user->setChangePassword(1);
        }

        $this->getLayout()->getBlock('head')->setTitle($this->__('Account Information'));

        $this->renderLayout();
    }

    /**
     * Change user password action
     */
    public function editPostAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/edit');
        }

        if ($this->getRequest()->isPost()) {
            /** @var $user Mage_User_Model_User */
            $user = $this->_getSession()->getUser();

            /** @var $userForm Mage_User_Model_Form */
            $userForm = Mage::getModel('user/form');
            $userForm->setFormCode('user_account_edit')
                ->setEntity($user);

            $userData = $userForm->extractData($this->getRequest());

            $errors = array();
            $userErrors = $userForm->validateData($userData);
            if ($userErrors !== true) {
                $errors = array_merge($userErrors, $errors);
            } else {
                $userForm->compactData($userData);
                $errors = array();

                // If password change was requested then add it to common validation scheme
                if ($this->getRequest()->getParam('change_password')) {
                    $currPass   = $this->getRequest()->getPost('current_password');
                    $newPass    = $this->getRequest()->getPost('password');
                    $confPass   = $this->getRequest()->getPost('confirmation');

                    $oldPass = $this->_getSession()->getUser()->getPasswordHash();
                    if (Mage::helper('core/string')->strpos($oldPass, ':')) {
                        list($_salt, $salt) = explode(':', $oldPass);
                    } else {
                        $salt = false;
                    }

                    if ($user->hashPassword($currPass, $salt) == $oldPass) {
                        if (strlen($newPass)) {
                            /**
                             * Set entered password and its confirmation - they
                             * will be validated later to match each other and be of right length
                             */
                            $user->setPassword($newPass);
                            $user->setConfirmation($confPass);
                        } else {
                            $errors[] = $this->__('New password field cannot be empty.');
                        }
                    } else {
                        $errors[] = $this->__('Invalid current password');
                    }
                }

                // Validate account and compose list of errors if any
                $userErrors = $user->validate();
                if (is_array($userErrors)) {
                    $errors = array_merge($errors, $userErrors);
                }
            }

            if (!empty($errors)) {
                $this->_getSession()->setUserFormData($this->getRequest()->getPost());
                foreach ($errors as $message) {
                    $this->_getSession()->addError($message);
                }
                $this->_redirect('*/*/edit');
                return $this;
            }

            try {
                $user->setConfirmation(null);
                $user->save();
                $this->_getSession()->setUser($user)
                    ->addSuccess($this->__('The account information has been saved.'));

                $this->_redirect('user/account');
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->setUserFormData($this->getRequest()->getPost())
                    ->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->setUserFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Cannot save the user.'));
            }
        }

        $this->_redirect('*/*/edit');
    }

    /**
     * Filtering posted data. Converting localized data if needed
     *
     * @param array
     * @return array
     */
    protected function _filterPostData($data)
    {
        $data = $this->_filterDates($data, array('dob'));
        return $data;
    }
}
