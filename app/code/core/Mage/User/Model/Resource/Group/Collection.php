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
 * User group collection
 *
 * @category    Mage
 * @package     Mage_User
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Model_Resource_Group_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('user/group');
    }

    /**
     * Set tax group filter
     *
     * @param mixed $classId
     * @return Mage_User_Model_Resource_Group_Collection
     */
    public function setTaxGroupFilter($classId)
    {
        $this->getSelect()->joinLeft(
            array('tax_class_group' => $this->getTable('tax/tax_class_group')),
            'tax_class_group.class_group_id = main_table.user_group_id'
        );
        $this->addFieldToFilter('tax_class_group.class_parent_id', $classId);
        return $this;
    }

    /**
     * Set ignore ID filter
     *
     * @param array $indexes
     * @return Mage_User_Model_Resource_Group_Collection
     */
    public function setIgnoreIdFilter($indexes)
    {
        if (count($indexes)) {
            $this->addFieldToFilter('main_table.user_group_id', array('nin' => $indexes));
        }
        return $this;
    }

    /**
     * Set real groups filter
     *
     * @return Mage_User_Model_Resource_Group_Collection
     */
    public function setRealGroupsFilter()
    {
        return $this->addFieldToFilter('user_group_id', array('gt' => 0));
    }

    /**
     * Add tax class
     *
     * @return Mage_User_Model_Resource_Group_Collection
     */
    public function addTaxClass()
    {
        $this->getSelect()->joinLeft(
            array('tax_class_table' => $this->getTable('tax/tax_class')),
            "main_table.tax_class_id = tax_class_table.class_id");
        return $this;
    }

    /**
     * Retreive option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return parent::_toOptionArray('user_group_id', 'user_group_code');
    }

    /**
     * Retreive option hash
     *
     * @return array
     */
    public function toOptionHash()
    {
        return parent::_toOptionHash('user_group_id', 'user_group_code');
    }
}
