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
 * User address book block
 *
 * @category   Mage
 * @package    Mage_User
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Block_Address_Book extends Mage_Core_Block_Template
{
    protected function _prepareLayout()
    {
        $this->getLayout()->getBlock('head')
            ->setTitle(Mage::helper('user')->__('Address Book'));

        return parent::_prepareLayout();
    }

    public function getAddAddressUrl()
    {
        return $this->getUrl('user/address/new', array('_secure'=>true));
    }

    public function getBackUrl()
    {
        if ($this->getRefererUrl()) {
            return $this->getRefererUrl();
        }
        return $this->getUrl('user/account/', array('_secure'=>true));
    }

    public function getDeleteUrl()
    {
        return $this->getUrl('user/address/delete');
    }

    public function getAddressEditUrl($address)
    {
        return $this->getUrl('user/address/edit', array('_secure'=>true, 'id'=>$address->getId()));
    }

    public function getPrimaryBillingAddress()
    {
        return $this->getUser()->getPrimaryBillingAddress();
    }

    public function getPrimaryShippingAddress()
    {
        return $this->getUser()->getPrimaryShippingAddress();
    }

    public function hasPrimaryAddress()
    {
        return $this->getPrimaryBillingAddress() || $this->getPrimaryShippingAddress();
    }

    public function getAdditionalAddresses()
    {
        $addresses = $this->getUser()->getAdditionalAddresses();
        return empty($addresses) ? false : $addresses;
    }

    public function getAddressHtml($address)
    {
        return $address->format('html');
        //return $address->toString($address->getHtmlFormat());
    }

    public function getUser()
    {
        $user = $this->getData('user');
        if (is_null($user)) {
            $user = Mage::getSingleton('user/session')->getUser();
            $this->setData('user', $user);
        }
        return $user;
    }
}
