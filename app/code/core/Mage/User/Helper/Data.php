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
 * User Data Helper
 *
 * @category   Mage
 * @package    Mage_User
 * @author     Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Query param name for last url visited
     */
    const REFERER_QUERY_PARAM_NAME = 'referer';

    /**
     * User groups collection
     *
     * @var Mage_User_Model_Entity_Group_Collection
     */
    protected $_groups;

    /**
     * Check user is logged in
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return Mage::getSingleton('user/session')->isLoggedIn();
    }

    /**
     * Retrieve logged in user
     *
     * @return Mage_User_Model_User
     */
    public function getUser()
    {
        if (empty($this->_user)) {
            $this->_user = Mage::getSingleton('user/session')->getUser();
        }
        return $this->_user;
    }

    /**
     * Retrieve user groups collection
     *
     * @return Mage_User_Model_Entity_Group_Collection
     */
    public function getGroups()
    {
        if (empty($this->_groups)) {
            $this->_groups = Mage::getModel('user/group')->getResourceCollection()
                ->setRealGroupsFilter()
                ->load();
        }
        return $this->_groups;
    }

    /**
     * Retrieve current (loggined) user object
     *
     * @return Mage_User_Model_User
     */
    public function getCurrentUser()
    {
        return $this->getUser();
    }

    /**
     * Retrieve current user name
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->getUser()->getName();
    }

    /**
     * Check user has address
     *
     * @return bool
     */
    public function userHasAddresses()
    {
        return count($this->getUser()->getAddresses()) > 0;
    }

    /**************************************************************************
     * User urls
     */

    /**
     * Retrieve user login url
     *
     * @return string
     */
    public function getLoginUrl()
    {
        $params = array();

        $referer = $this->_getRequest()->getParam(self::REFERER_QUERY_PARAM_NAME);

        if (!$referer && !Mage::getStoreConfigFlag('user/startup/redirect_dashboard')) {
            if (!Mage::getSingleton('user/session')->getNoReferer()) {
                $referer = Mage::getUrl('*/*/*', array('_current' => true, '_use_rewrite' => true));
                $referer = Mage::helper('core')->urlEncode($referer);
            }
        }
        if ($referer) {
            $params = array(self::REFERER_QUERY_PARAM_NAME => $referer);
        }

        return $this->_getUrl('user/account/login', $params);
    }

    /**
     * Retrieve user login POST URL
     *
     * @return string
     */
    public function getLoginPostUrl()
    {
        $params = array();
        if ($this->_getRequest()->getParam(self::REFERER_QUERY_PARAM_NAME)) {
            $params = array(
                self::REFERER_QUERY_PARAM_NAME => $this->_getRequest()->getParam(self::REFERER_QUERY_PARAM_NAME)
            );
        }
        return $this->_getUrl('user/account/loginPost', $params);
    }

    /**
     * Retrieve user logout url
     *
     * @return string
     */
    public function getLogoutUrl()
    {
        return $this->_getUrl('user/account/logout');
    }

    /**
     * Retrieve user dashboard url
     *
     * @return string
     */
    public function getDashboardUrl()
    {
        return $this->_getUrl('user/account');
    }

    /**
     * Retrieve user account page url
     *
     * @return string
     */
    public function getAccountUrl()
    {
        return $this->_getUrl('user/account');
    }

    /**
     * Retrieve user register form url
     *
     * @return string
     */
    public function getRegisterUrl()
    {
        return $this->_getUrl('user/account/create');
    }

    /**
     * Retrieve user register form post url
     *
     * @return string
     */
    public function getRegisterPostUrl()
    {
        return $this->_getUrl('user/account/createpost');
    }

    /**
     * Retrieve user account edit form url
     *
     * @return string
     */
    public function getEditUrl()
    {
        return $this->_getUrl('user/account/edit');
    }

    /**
     * Retrieve user edit POST URL
     *
     * @return string
     */
    public function getEditPostUrl()
    {
        return $this->_getUrl('user/account/editpost');
    }

    /**
     * Retrieve url of forgot password page
     *
     * @return string
     */
    public function getForgotPasswordUrl()
    {
        return $this->_getUrl('user/account/forgotpassword');
    }

    /**
     * Check is confirmation required
     *
     * @return bool
     */
    public function isConfirmationRequired()
    {
        return $this->getUser()->isConfirmationRequired();
    }

    /**
     * Retrieve confirmation URL for Email
     *
     * @param string $email
     * @return string
     */
    public function getEmailConfirmationUrl($email = null)
    {
        return $this->_getUrl('user/account/confirmation', array('email' => $email));
    }

    /**
     * Check whether users registration is allowed
     *
     * @return bool
     */
    public function isRegistrationAllowed()
    {
        $result = new Varien_Object(array('is_allowed' => true));
        Mage::dispatchEvent('user_registration_is_allowed', array('result' => $result));
        return $result->getIsAllowed();
    }

    /**
     * Retrieve name prefix dropdown options
     *
     * @return array|bool
     */
    public function getNamePrefixOptions($store = null)
    {
        return $this->_prepareNamePrefixSuffixOptions(
            Mage::helper('user/address')->getConfig('prefix_options', $store)
        );
    }

    /**
     * Retrieve name suffix dropdown options
     *
     * @return array|bool
     */
    public function getNameSuffixOptions($store = null)
    {
        return $this->_prepareNamePrefixSuffixOptions(
            Mage::helper('user/address')->getConfig('suffix_options', $store)
        );
    }

    /**
     * Unserialize and clear name prefix or suffix options
     *
     * @param string $options
     * @return array|bool
     */
    protected function _prepareNamePrefixSuffixOptions($options)
    {
        $options = trim($options);
        if (empty($options)) {
            return false;
        }
        $result = array();
        $options = explode(';', $options);
        foreach ($options as $value) {
            $value = $this->escapeHtml(trim($value));
            $result[$value] = $value;
        }
        return $result;
    }
}
