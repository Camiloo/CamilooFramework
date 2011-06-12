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
 * User session model
 *
 * @category   Mage
 * @package    Mage_User
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Model_Session extends Mage_Core_Model_Session_Abstract
{
    /**
     * User object
     *
     * @var Mage_User_Model_User
     */
    protected $_user;

    /**
     * Flag with user id validations result
     *
     * @var bool
     */
    protected $_isUserIdChecked = null;

    /**
     * Retrieve user sharing configuration model
     *
     * @return Mage_User_Model_Config_Share
     */
    public function getUserConfigShare()
    {
        return Mage::getSingleton('user/config_share');
    }

    public function __construct()
    {
        $namespace = 'user';
        if ($this->getUserConfigShare()->isWebsiteScope()) {
            $namespace .= '_' . (Mage::app()->getStore()->getWebsite()->getCode());
        }

        $this->init($namespace);
        Mage::dispatchEvent('user_session_init', array('user_session'=>$this));
    }

    /**
     * Set user object and setting user id in session
     *
     * @param   Mage_User_Model_User $user
     * @return  Mage_User_Model_Session
     */
    public function setUser(Mage_User_Model_User $user)
    {
        // check if user is not confirmed
        if ($user->isConfirmationRequired()) {
            if ($user->getConfirmation()) {
                throw new Exception('This user is not confirmed and cannot log in.',
                    Mage_User_Model_User::EXCEPTION_EMAIL_NOT_CONFIRMED
                );
            }
        }
        $this->_user = $user;
        $this->setId($user->getId());
        // save user as confirmed, if it is not
        if ((!$user->isConfirmationRequired()) && $user->getConfirmation()) {
            $user->setConfirmation(null)->save();
            $user->setIsJustConfirmed(true);
        }
        return $this;
    }

    /**
     * Retrieve costomer model object
     *
     * @return Mage_User_Model_User
     */
    public function getUser()
    {
        if ($this->_user instanceof Mage_User_Model_User) {
            return $this->_user;
        }

        $user = Mage::getModel('user/user')
            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
        if ($this->getId()) {
            $user->load($this->getId());
        }

        $this->setUser($user);
        return $this->_user;
    }

    /**
     * Retrieve user id from current session
     *
     * @return int || null
     */
    public function getUserId()
    {
        if ($this->isLoggedIn()) {
            return $this->getId();
        }
        return null;
    }

    /**
     * Get user group id
     * If user is not logged in system not logged in group id will be returned
     *
     * @return int
     */
    public function getUserGroupId()
    {
        if ($this->isLoggedIn()) {
            return $this->getUser()->getGroupId();
        } else {
            return Mage_User_Model_Group::NOT_LOGGED_IN_ID;
        }
    }

    /**
     * Checking custommer loggin status
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return (bool)$this->getId() && (bool)$this->checkUserId($this->getId());
    }

    /**
     * Check exists user (light check)
     *
     * @param int $userId
     * @return bool
     */
    public function checkUserId($userId)
    {
        if ($this->_isUserIdChecked === null) {
            $this->_isUserIdChecked = Mage::getResourceSingleton('user/user')->checkUserId($userId);
        }
        return $this->_isUserIdChecked;
    }

    /**
     * User authorization
     *
     * @param   string $username
     * @param   string $password
     * @return  bool
     */
    public function login($username, $password)
    {
        /** @var $user Mage_User_Model_User */
        $user = Mage::getModel('user/user')
            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId());

        if ($user->authenticate($username, $password)) {
            $this->setUserAsLoggedIn($user);
            $this->renewSession();
            return true;
        }
        return false;
    }

    public function setUserAsLoggedIn($user)
    {
        $this->setUser($user);
        Mage::dispatchEvent('user_login', array('user'=>$user));
        return $this;
    }

    /**
     * Authorization user by identifier
     *
     * @param   int $userId
     * @return  bool
     */
    public function loginById($userId)
    {
        $user = Mage::getModel('user/user')->load($userId);
        if ($user->getId()) {
            $this->setUserAsLoggedIn($user);
            return true;
        }
        return false;
    }

    /**
     * Logout user
     *
     * @return Mage_User_Model_Session
     */
    public function logout()
    {
        if ($this->isLoggedIn()) {
            Mage::dispatchEvent('user_logout', array('user' => $this->getUser()) );
            $this->setId(null);
            $this->getCookie()->delete($this->getSessionName());
        }
        return $this;
    }

    /**
     * Authenticate controller action by login user
     *
     * @param   Mage_Core_Controller_Varien_Action $action
     * @return  bool
     */
    public function authenticate(Mage_Core_Controller_Varien_Action $action, $loginUrl = null)
    {
        if (!$this->isLoggedIn()) {
            $this->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_current'=>true)));
            if (is_null($loginUrl)) {
                $loginUrl = Mage::helper('user')->getLoginUrl();
            }
            $action->getResponse()->setRedirect($loginUrl);
            return false;
        }
        return true;
    }
}
