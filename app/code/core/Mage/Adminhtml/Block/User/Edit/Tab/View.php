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
 * User account form block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_User_Edit_Tab_View
 extends Mage_Adminhtml_Block_Template
 implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    protected $_user;

    protected $_userLog;

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = Mage::registry('current_user');
        }
        return $this->_user;
    }

    public function getGroupName()
    {
        if ($groupId = $this->getUser()->getGroupId()) {
            return Mage::getModel('user/group')
                ->load($groupId)
                ->getUserGroupCode();
        }
    }

    /**
     * Load User Log model
     *
     * @return Mage_Log_Model_User
     */
    public function getUserLog()
    {
        if (!$this->_userLog) {
          //  $this->_userLog = Mage::getModel('log/user')
          //      ->loadByUser($this->getUser()->getId());
        }
        return $this->_userLog;
    }

    public function getCreateDate()
    {
        $date = Mage::app()->getLocale()->date($this->getUser()->getCreatedAtTimestamp());
        return $this->formatDate($date, Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM, true);
    }

    public function getStoreCreateDate()
    {
        $date = Mage::app()->getLocale()->storeDate(
            $this->getUser()->getStoreId(),
            $this->getUser()->getCreatedAtTimestamp(),
            true
        );
        return $this->formatDate($date, Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM, true);
    }

    public function getStoreCreateDateTimezone()
    {
        return Mage::app()->getStore($this->getUser()->getStoreId())
            ->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE);
    }

    public function getLastLoginDate()
    {
        //if ($date = $this->getUserLog()->getLoginAtTimestamp()) {
        //    $date = Mage::app()->getLocale()->date($date);
        //    return $this->formatDate($date, Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM, true);
        //}
        //return Mage::helper('user')->__('Never');
    }

    public function getStoreLastLoginDate()
    {
       // if ($date = $this->getUserLog()->getLoginAtTimestamp()) {
       //     $date = Mage::app()->getLocale()->storeDate(
       //         $this->getUser()->getStoreId(),
       //         $date,
       //         true
       //     );
       //     return $this->formatDate($date, Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM, true);
       // }
       // return Mage::helper('user')->__('Never');
    }

    public function getStoreLastLoginDateTimezone()
    {
        return Mage::app()->getStore($this->getUser()->getStoreId())
            ->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE);
    }

    public function getCurrentStatus()
    {
        //$log = $this->getUserLog();
        //if ($log->getLogoutAt() ||
        //    strtotime(now())-strtotime($log->getLastVisitAt())>Mage_Log_Model_Visitor::getOnlineMinutesInterval()*60) {
        //    return Mage::helper('user')->__('Offline');
        // }
        //return Mage::helper('user')->__('Online');
    }

    public function getIsConfirmedStatus()
    {
        $this->getUser();
        if (!$this->_user->getConfirmation()) {
            return Mage::helper('user')->__('Confirmed');
        }
        if ($this->_user->isConfirmationRequired()) {
            return Mage::helper('user')->__('Not confirmed, cannot login');
        }
        return Mage::helper('user')->__('Not confirmed, can login');
    }

    public function getCreatedInStore()
    {
        return Mage::app()->getStore($this->getUser()->getStoreId())->getName();
    }

    public function getStoreId()
    {
        return $this->getUser()->getStoreId();
    }

    public function getBillingAddressHtml()
    {
        $html = '';
        if ($address = $this->getUser()->getPrimaryBillingAddress()) {
            $html = $address->format('html');
        }
        else {
            $html = Mage::helper('user')->__('The user does not have default billing address.');
        }
        return $html;
    }

    public function getAccordionHtml()
    {
        return $this->getChildHtml('accordion');
    }

    public function getSalesHtml()
    {
      //  return $this->getChildHtml('sales');
    }

    public function getTabLabel()
    {
        return Mage::helper('user')->__('User View');
    }

    public function getTabTitle()
    {
        return Mage::helper('user')->__('User View');
    }

    public function canShowTab()
    {
        if (Mage::registry('current_user')->getId()) {
            return true;
        }
        return false;
    }

    public function isHidden()
    {
        if (Mage::registry('current_user')->getId()) {
            return false;
        }
        return true;
    }

}
