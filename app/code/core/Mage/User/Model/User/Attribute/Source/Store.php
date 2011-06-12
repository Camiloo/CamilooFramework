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
 * User store attribute source
 *
 * @category   Mage
 * @package    Mage_User
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Model_User_Attribute_Source_Store extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
    public function getAllOptions()
    {
        if (!$this->_options) {
            $collection = Mage::getResourceModel('core/store_collection');
            if ('store_id' == $this->getAttribute()->getAttributeCode()) {
                $collection->setWithoutDefaultFilter();
            }
            $this->_options = Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm();
            if ('created_in' == $this->getAttribute()->getAttributeCode()) {
                array_unshift($this->_options, array('value' => '0', 'label' => Mage::helper('user')->__('Admin')));
            }
        }
        return $this->_options;
    }

    public function getOptionText($value)
    {
        if(!$value)$value ='0';
        $isMultiple = false;
        if (strpos($value, ',')) {
            $isMultiple = true;
            $value = explode(',', $value);
        }

        if (!$this->_options) {
            $collection = Mage::getResourceModel('core/store_collection');
            if ('store_id' == $this->getAttribute()->getAttributeCode()) {
                $collection->setWithoutDefaultFilter();
            }
            $this->_options = $collection->load()->toOptionArray();
            if ('created_in' == $this->getAttribute()->getAttributeCode()) {
                array_unshift($this->_options, array('value' => '0', 'label' => Mage::helper('user')->__('Admin')));
            }
        }

        if ($isMultiple) {
            $values = array();
            foreach ($value as $val) {
                $values[] = $this->_options[$val];
            }
            return $values;
        }
        else {
            return $this->_options[$value];
        }
        return false;
    }
}
