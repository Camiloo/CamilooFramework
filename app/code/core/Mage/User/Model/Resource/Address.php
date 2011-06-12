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
 * User address entity resource model
 *
 * @category    Mage
 * @package     Mage_User
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Model_Resource_Address extends Mage_Eav_Model_Entity_Abstract
{
    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $resource = Mage::getSingleton('core/resource');
        $this->setType('user_address')->setConnection(
            $resource->getConnection('user_read'),
            $resource->getConnection('user_write')
        );
    }

    /**
     * Set default shipping to address
     *
     * @param Varien_Object $address
     * @return Mage_User_Model_Resource_Address
     */
    protected function _afterSave(Varien_Object $address)
    {
        if ($address->getIsUserSaveTransaction()) {
            return $this;
        }
        if ($address->getId() && ($address->getIsDefaultBilling() || $address->getIsDefaultShipping())) {
            $user = Mage::getModel('user/user')
                ->load($address->getUserId());

            if ($address->getIsDefaultBilling()) {
                $user->setDefaultBilling($address->getId());
            }
            if ($address->getIsDefaultShipping()) {
                $user->setDefaultShipping($address->getId());
            }
            $user->save();
        }
        return $this;
    }

    /**
     * Return user id
     * @deprecated
     *
     * @param Mage_User_Model_Address $object
     * @return int
     */
    public function getUserId($object)
    {
        return $object->getData('user_id') ? $object->getData('user_id') : $object->getParentId();
    }

    /**
     * Set user id
     * @deprecated
     *
     * @param Mage_User_Model_Address $object
     * @param int $id
     * @return Mage_User_Model_Address
     */
    public function setUserId($object, $id)
    {
        return $this;
    }
}
