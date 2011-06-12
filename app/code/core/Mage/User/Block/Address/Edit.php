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
 * User address edit block
 *
 * @category   Mage
 * @package    Mage_User
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Block_Address_Edit extends Mage_Directory_Block_Data
{
    protected $_address;
    protected $_countryCollection;
    protected $_regionCollection;

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->_address = Mage::getModel('user/address');

        // Init address object
        if ($id = $this->getRequest()->getParam('id')) {
            $this->_address->load($id);
            if ($this->_address->getUserId() != Mage::getSingleton('user/session')->getUserId()) {
                $this->_address->setData(array());
            }
        }

        if (!$this->_address->getId()) {
            $this->_address->setPrefix($this->getUser()->getPrefix())
                ->setFirstname($this->getUser()->getFirstname())
                ->setMiddlename($this->getUser()->getMiddlename())
                ->setLastname($this->getUser()->getLastname())
                ->setSuffix($this->getUser()->getSuffix());
        }

        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle($this->getTitle());
        }
        if ($postedData = Mage::getSingleton('user/session')->getAddressFormData(true)) {
            $this->_address->setData($postedData);
        }
    }

    /**
     * Generate name block html
     *
     * @return string
     */
    public function getNameBlockHtml()
    {
        $nameBlock = $this->getLayout()
            ->createBlock('user/widget_name')
            ->setObject($this->getAddress());

        return $nameBlock->toHtml();
    }

    public function getTitle()
    {
        if ($title = $this->getData('title')) {
            return $title;
        }
        if ($this->getAddress()->getId()) {
            $title = Mage::helper('user')->__('Edit Address');
        }
        else {
            $title = Mage::helper('user')->__('Add New Address');
        }
        return $title;
    }

    public function getBackUrl()
    {
        if ($this->getData('back_url')) {
            return $this->getData('back_url');
        }

        if ($this->getUserAddressCount()) {
            return $this->getUrl('user/address');
        } else {
            return $this->getUrl('user/account/');
        }
    }

    public function getSaveUrl()
    {
        return Mage::getUrl('user/address/formPost', array('_secure'=>true, 'id'=>$this->getAddress()->getId()));
    }

    public function getAddress()
    {
        return $this->_address;
    }

    public function getCountryId()
    {
        if ($countryId = $this->getAddress()->getCountryId()) {
            return $countryId;
        }
        return parent::getCountryId();
    }

    public function getRegionId()
    {
        return $this->getAddress()->getRegionId();
    }

    public function getUserAddressCount()
    {
        return count(Mage::getSingleton('user/session')->getUser()->getAddresses());
    }

    public function canSetAsDefaultBilling()
    {
        if (!$this->getAddress()->getId()) {
            return $this->getUserAddressCount();
        }
        return !$this->isDefaultBilling();
    }

    public function canSetAsDefaultShipping()
    {
        if (!$this->getAddress()->getId()) {
            return $this->getUserAddressCount();
        }
        return !$this->isDefaultShipping();;
    }

    public function isDefaultBilling()
    {
        $defaultBilling = Mage::getSingleton('user/session')->getUser()->getDefaultBilling();
        return $this->getAddress()->getId() && $this->getAddress()->getId() == $defaultBilling;
    }

    public function isDefaultShipping()
    {
        $defaultShipping = Mage::getSingleton('user/session')->getUser()->getDefaultShipping();
        return $this->getAddress()->getId() && $this->getAddress()->getId() == $defaultShipping;
    }

    public function getUser()
    {
        return Mage::getSingleton('user/session')->getUser();
    }

    public function getBackButtonUrl()
    {
        if ($this->getUserAddressCount()) {
            return $this->getUrl('user/address');
        } else {
            return $this->getUrl('user/account/');
        }
    }
}
