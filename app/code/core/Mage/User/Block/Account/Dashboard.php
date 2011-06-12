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
 * User dashboard block
 *
 * @category   Mage
 * @package    Mage_User
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Block_Account_Dashboard extends Mage_Core_Block_Template
{
    protected $_subscription = null;

    public function getUser()
    {
        return Mage::getSingleton('user/session')->getUser();
    }

    public function getAccountUrl()
    {
        return Mage::getUrl('user/account/edit', array('_secure'=>true));
    }

    public function getAddressesUrl()
    {
        return Mage::getUrl('user/address/index', array('_secure'=>true));
    }

    public function getAddressEditUrl($address)
    {
        return Mage::getUrl('user/address/edit', array('_secure'=>true, 'id'=>$address->getId()));
    }

    public function getOrdersUrl()
    {
        return Mage::getUrl('user/order/index', array('_secure'=>true));
    }

    public function getReviewsUrl()
    {
        return Mage::getUrl('review/user/index', array('_secure'=>true));
    }

    public function getWishlistUrl()
    {
        return Mage::getUrl('user/wishlist/index', array('_secure'=>true));
    }

    public function getTagsUrl()
    {

    }

    public function getSubscriptionObject()
    {
        if(is_null($this->_subscription)) {
            $this->_subscription = Mage::getModel('newsletter/subscriber')->loadByUser($this->getUser());
        }

        return $this->_subscription;
    }

    public function getManageNewsletterUrl()
    {
        return $this->getUrl('*/newsletter/manage');
    }

    public function getSubscriptionText()
    {
        if($this->getSubscriptionObject()->isSubscribed()) {
            return Mage::helper('user')->__('You are currently subscribed to our newsletter.');
        }

        return Mage::helper('user')->__('You are currently not subscribed to our newsletter.');
    }

    public function getPrimaryAddresses()
    {
        $addresses = $this->getUser()->getPrimaryAddresses();
        if (empty($addresses)) {
            return false;
        }
        return $addresses;
    }

    /**
     * Get back url in account dashboard
     *
     * This method is copypasted in:
     * Mage_Wishlist_Block_User_Wishlist  - because of strange inheritance
     * Mage_User_Block_Address_Book - because of secure url
     *
     * @return string
     */
    public function getBackUrl()
    {
        // the RefererUrl must be set in appropriate controller
        if ($this->getRefererUrl()) {
            return $this->getRefererUrl();
        }
        return $this->getUrl('user/account/');
    }
}
