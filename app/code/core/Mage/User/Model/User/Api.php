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
 * User api
 *
 * @category   Mage
 * @package    Mage_User
 * @author     Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Model_User_Api extends Mage_User_Model_Api_Resource
{
    protected $_mapAttributes = array(
        'user_id' => 'entity_id'
    );
    /**
     * Prepare data to insert/update.
     * Creating array for stdClass Object
     *
     * @param stdClass $data
     * @return array
     */
    protected function _prepareData($data)
    {
       foreach ($this->_mapAttributes as $attributeAlias=>$attributeCode) {
            if(isset($data[$attributeAlias]))
            {
                $data[$attributeCode] = $data[$attributeAlias];
                unset($data[$attributeAlias]);
            }
        }
        return $data;
    }

    /**
     * Create new user
     *
     * @param array $userData
     * @return int
     */
    public function create($userData)
    {
        $userData = $this->_prepareData($userData);
        try {
            $user = Mage::getModel('user/user')
                ->setData($userData)
                ->save();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }
        return $user->getId();
    }

    /**
     * Retrieve user data
     *
     * @param int $userId
     * @param array $attributes
     * @return array
     */
    public function info($userId, $attributes = null)
    {
        $user = Mage::getModel('user/user')->load($userId);

        if (!$user->getId()) {
            $this->_fault('not_exists');
        }

        if (!is_null($attributes) && !is_array($attributes)) {
            $attributes = array($attributes);
        }

        $result = array();

        foreach ($this->_mapAttributes as $attributeAlias=>$attributeCode) {
            $result[$attributeAlias] = $user->getData($attributeCode);
        }

        foreach ($this->getAllowedAttributes($user, $attributes) as $attributeCode=>$attribute) {
            $result[$attributeCode] = $user->getData($attributeCode);
        }

        return $result;
    }

    /**
     * Retrieve cutomers data
     *
     * @param  array $filters
     * @return array
     */
    public function items($filters)
    {
        $collection = Mage::getModel('user/user')->getCollection()
            ->addAttributeToSelect('*');

        if (is_array($filters)) {
            try {
                foreach ($filters as $field => $value) {
                    if (isset($this->_mapAttributes[$field])) {
                        $field = $this->_mapAttributes[$field];
                    }

                    $collection->addFieldToFilter($field, $value);
                }
            } catch (Mage_Core_Exception $e) {
                $this->_fault('filters_invalid', $e->getMessage());
            }
        }

        $result = array();
        foreach ($collection as $user) {
            $data = $user->toArray();
            $row  = array();

            foreach ($this->_mapAttributes as $attributeAlias => $attributeCode) {
                $row[$attributeAlias] = (isset($data[$attributeCode]) ? $data[$attributeCode] : null);
            }

            foreach ($this->getAllowedAttributes($user) as $attributeCode => $attribute) {
                if (isset($data[$attributeCode])) {
                    $row[$attributeCode] = $data[$attributeCode];
                }
            }

            $result[] = $row;
        }

        return $result;
    }

    /**
     * Update user data
     *
     * @param int $userId
     * @param array $userData
     * @return boolean
     */
    public function update($userId, $userData)
    {
        $userData = $this->_prepareData($userData);
        
        $user = Mage::getModel('user/user')->load($userId);

        if (!$user->getId()) {
            $this->_fault('not_exists');
        }

        foreach ($this->getAllowedAttributes($user) as $attributeCode=>$attribute) {
            if (isset($userData[$attributeCode])) {
                $user->setData($attributeCode, $userData[$attributeCode]);
            }
        }

        $user->save();
        return true;
    }

    /**
     * Delete user
     *
     * @param int $userId
     * @return boolean
     */
    public function delete($userId)
    {
        $user = Mage::getModel('user/user')->load($userId);

        if (!$user->getId()) {
            $this->_fault('not_exists');
        }

        try {
            $user->delete();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('not_deleted', $e->getMessage());
        }

        return true;
    }

} // Class Mage_User_Model_User_Api End
