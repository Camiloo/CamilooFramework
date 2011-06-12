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
 * User entity resource model
 *
 * @category    Mage
 * @package     Mage_User
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Model_Resource_User extends Mage_Eav_Model_Entity_Abstract
{
    /**
     * Resource initialization
     */
    public function __construct()
    {
        $this->setType('user');
        $this->setConnection('user_read', 'user_write');
    }

    /**
     * Retrieve user entity default attributes
     *
     * @return array
     */
    protected function _getDefaultAttributes()
    {
        return array(
            'entity_type_id',
            'attribute_set_id',
            'created_at',
            'updated_at',
            'increment_id',
            'store_id',
            'website_id'
        );
    }

    /**
     * Check user scope, email and confirmation key before saving
     *
     * @param Mage_User_Model_User $user
     * @throws Mage_User_Exception
     * @return Mage_User_Model_Resource_User
     */
    protected function _beforeSave(Varien_Object $user)
    {
        parent::_beforeSave($user);

        if (!$user->getEmail()) {
            throw Mage::exception('Mage_User', Mage::helper('user')->__('User email is required'));
        }

        $adapter = $this->_getWriteAdapter();
        $bind    = array('email' => $user->getEmail());

        $select = $adapter->select()
            ->from($this->getEntityTable(), array($this->getEntityIdField()))
            ->where('email = :email');
        if ($user->getSharingConfig()->isWebsiteScope()) {
            $bind['website_id'] = (int)$user->getWebsiteId();
            $select->where('website_id = :website_id');
        }
        if ($user->getId()) {
            $bind['entity_id'] = (int)$user->getId();
            $select->where('entity_id != :entity_id');
        }

        $result = $adapter->fetchOne($select, $bind);
        if ($result) {
            throw Mage::exception(
                'Mage_User', Mage::helper('user')->__('This user email already exists'),
                Mage_User_Model_User::EXCEPTION_EMAIL_EXISTS
            );
        }

        // set confirmation key logic
        if ($user->getForceConfirmed()) {
            $user->setConfirmation(null);
        } elseif (!$user->getId() && $user->isConfirmationRequired()) {
            $user->setConfirmation($user->getRandomConfirmationKey());
        }
        // remove user confirmation key from database, if empty
        if (!$user->getConfirmation()) {
            $user->setConfirmation(null);
        }

        return $this;
    }

    /**
     * Save user addresses and set default addresses in attributes backend
     *
     * @param Varien_Object $user
     * @return Mage_Eav_Model_Entity_Abstract
     */
    protected function _afterSave(Varien_Object $user)
    {
        $this->_saveAddresses($user);
        return parent::_afterSave($user);
    }

    /**
     * Save/delete user address
     *
     * @param Mage_User_Model_User $user
     * @return Mage_User_Model_Resource_User
     */
    protected function _saveAddresses(Mage_User_Model_User $user)
    {
        $defaultBillingId   = $user->getData('default_billing');
        $defaultShippingId  = $user->getData('default_shipping');
        foreach ($user->getAddresses() as $address) {
            if ($address->getData('_deleted')) {
                if ($address->getId() == $defaultBillingId) {
                    $user->setData('default_billing', null);
                }
                if ($address->getId() == $defaultShippingId) {
                    $user->setData('default_shipping', null);
                }
                $address->delete();
            } else {
                $address->setParentId($user->getId())
                    ->setStoreId($user->getStoreId())
                    ->setIsUserSaveTransaction(true)
                    ->save();
                if (($address->getIsPrimaryBilling() || $address->getIsDefaultBilling())
                    && $address->getId() != $defaultBillingId
                ) {
                    $user->setData('default_billing', $address->getId());
                }
                if (($address->getIsPrimaryShipping() || $address->getIsDefaultShipping())
                    && $address->getId() != $defaultShippingId
                ) {
                    $user->setData('default_shipping', $address->getId());
                }
            }
        }
        if ($user->dataHasChangedFor('default_billing')) {
            $this->saveAttribute($user, 'default_billing');
        }
        if ($user->dataHasChangedFor('default_shipping')) {
            $this->saveAttribute($user, 'default_shipping');
        }

        return $this;
    }

    /**
     * Retrieve select object for loading base entity row
     *
     * @param Varien_Object $object
     * @param mixed $rowId
     * @return Varien_Db_Select
     */
    protected function _getLoadRowSelect($object, $rowId)
    {
        $select = parent::_getLoadRowSelect($object, $rowId);
        if ($object->getWebsiteId() && $object->getSharingConfig()->isWebsiteScope()) {
            $select->where('website_id =?', (int)$object->getWebsiteId());
        }

        return $select;
    }

    /**
     * Load user by email
     *
     * @throws Mage_Core_Exception
     *
     * @param Mage_User_Model_User $user
     * @param string $email
     * @param bool $testOnly
     * @return Mage_User_Model_Resource_User
     */
    public function loadByEmail(Mage_User_Model_User $user, $email, $testOnly = false)
    {
        $adapter = $this->_getReadAdapter();
        $bind    = array('user_email' => $email);
        $select  = $adapter->select()
            ->from($this->getEntityTable(), array($this->getEntityIdField()))
            ->where('email = :user_email');

        if ($user->getSharingConfig()->isWebsiteScope()) {
            if (!$user->hasData('website_id')) {
                Mage::throwException(
                    Mage::helper('user')->__('User website ID must be specified when using the website scope')
                );
            }
            $bind['website_id'] = (int)$user->getWebsiteId();
            $select->where('website_id = :website_id');
        }

        $userId = $adapter->fetchOne($select, $bind);
        if ($userId) {
            $this->load($user, $userId);
        } else {
            $user->setData(array());
        }

        return $this;
    }

    /**
     * Change user password
     *
     * @param Mage_User_Model_User $user
     * @param string $newPassword
     * @return Mage_User_Model_Resource_User
     */
    public function changePassword(Mage_User_Model_User $user, $newPassword)
    {
        $user->setPassword($newPassword);
        $this->saveAttribute($user, 'password_hash');
        return $this;
    }

    /**
     * Check whether there are email duplicates of users in global scope
     *
     * @return bool
     */
    public function findEmailDuplicates()
    {
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('user/entity'), array('email', 'cnt' => 'COUNT(*)'))
            ->group('email')
            ->order('cnt DESC')
            ->limit(1);
        $lookup = $adapter->fetchRow($select);
        if (empty($lookup)) {
            return false;
        }
        return $lookup['cnt'] > 1;
    }

    /**
     * Check user by id
     *
     * @param int $userId
     * @return bool
     */
    public function checkUserId($userId)
    {
        $adapter = $this->_getReadAdapter();
        $bind    = array('entity_id' => (int)$userId);
        $select  = $adapter->select()
            ->from($this->getTable('user/entity'), 'entity_id')
            ->where('entity_id = :entity_id')
            ->limit(1);

        $result = $adapter->fetchOne($select, $bind);
        if ($result) {
            return true;
        }
        return false;
    }

    /**
     * Get user website id
     *
     * @param int $userId
     * @return int
     */
    public function getWebsiteId($userId)
    {
        $adapter = $this->_getReadAdapter();
        $bind    = array('entity_id' => (int)$userId);
        $select  = $adapter->select()
            ->from($this->getTable('user/entity'), 'website_id')
            ->where('entity_id = :entity_id');

        return $adapter->fetchOne($select, $bind);
    }

    /**
     * Custom setter of increment ID if its needed
     *
     * @param Varien_Object $object
     * @return Mage_User_Model_Resource_User
     */
    public function setNewIncrementId(Varien_Object $object)
    {
        if (Mage::getStoreConfig(Mage_User_Model_User::XML_PATH_GENERATE_HUMAN_FRIENDLY_ID)) {
            parent::setNewIncrementId($object);
        }
        return $this;
    }
}
