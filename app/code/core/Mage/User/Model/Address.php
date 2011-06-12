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
 * User address model
 *
 * @category   Mage
 * @package    Mage_User
 * @author     Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Model_Address extends Mage_User_Model_Address_Abstract
{
    protected $_user;

    protected function _construct()
    {
        $this->_init('user/address');
    }

    /**
     * Retrieve address user identifier
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->_getData('user_id') ? $this->_getData('user_id') : $this->getParentId();
    }

    /**
     * Declare address user identifier
     *
     * @param integer $id
     * @return Mage_User_Model_Address
     */
    public function setUserId($id)
    {
        $this->setParentId($id);
        $this->setData('user_id', $id);
        return $this;
    }

    /**
     * Retrieve address user
     *
     * @return Mage_User_Model_User | false
     */
    public function getUser()
    {
        if (!$this->getUserId()) {
            return false;
        }
        if (empty($this->_user)) {
            $this->_user = Mage::getModel('user/user')
                ->load($this->getUserId());
        }
        return $this->_user;
    }

    /**
     * Specify address user
     *
     * @param Mage_User_Model_User $user
     */
    public function setUser(Mage_User_Model_User $user)
    {
        $this->_user = $user;
        $this->setUserId($user->getId());
        return $this;
    }

    /**
     * Delete user address
     *
     * @return Mage_User_Model_Address
     */
    public function delete()
    {
        parent::delete();
        $this->setData(array());
        return $this;
    }

    /**
     * Retrieve address entity attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = $this->getData('attributes');
        if (is_null($attributes)) {
            $attributes = $this->_getResource()
                ->loadAllAttributes($this)
                ->getSortedAttributes();
            $this->setData('attributes', $attributes);
        }
        return $attributes;
    }

    public function __clone()
    {
        $this->setId(null);
    }

    /**
     * Return Entity Type instance
     *
     * @return Mage_Eav_Model_Entity_Type
     */
    public function getEntityType()
    {
        return $this->_getResource()->getEntityType();
    }

    /**
     * Return Entity Type ID
     *
     * @return int
     */
    public function getEntityTypeId()
    {
        $entityTypeId = $this->getData('entity_type_id');
        if (!$entityTypeId) {
            $entityTypeId = $this->getEntityType()->getId();
            $this->setData('entity_type_id', $entityTypeId);
        }
        return $entityTypeId;
    }
}
