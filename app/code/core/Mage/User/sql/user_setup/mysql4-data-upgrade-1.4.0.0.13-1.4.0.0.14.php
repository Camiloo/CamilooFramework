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

/* @var $installer Mage_User_Model_Entity_Setup */
$installer = $this;
/* @var $eavConfig Mage_Eav_Model_Config */
$eavConfig = Mage::getSingleton('eav/config');

// update user system attributes used_in_forms data
$attributes = array(
    'confirmation'      => array(),
    'default_billing'   => array(),
    'default_shipping'  => array(),
    'password_hash'     => array(),
    'website_id'        => array('adminhtml_only' => 1),
    'created_in'        => array('adminhtml_only' => 1),
    'store_id'          => array(),
    'group_id'          => array('adminhtml_only' => 1, 'admin_checkout' => 1),
    'prefix'            => array(),
    'firstname'         => array(),
    'middlename'        => array(),
    'lastname'          => array(),
    'suffix'            => array(),
    'email'             => array('admin_checkout' => 1),
    'dob'               => array('admin_checkout' => 1),
    'taxvat'            => array('admin_checkout' => 1),
    'gender'            => array('admin_checkout' => 1),
);

$defaultUsedInForms = array(
    'user_account_create',
    'user_account_edit',
    'checkout_register',
);

foreach ($attributes as $attributeCode => $data) {
    $attribute = $eavConfig->getAttribute('user', $attributeCode);
    if (!$attribute) {
        continue;
    }
    if (false === ($attribute->getData('is_system') == 1 && $attribute->getData('is_visible') == 0)) {
        $usedInForms = $defaultUsedInForms;
        if (!empty($data['adminhtml_only'])) {
            $usedInForms = array('adminhtml_user');
        } else {
            $usedInForms[] = 'adminhtml_user';
        }
        if (!empty($data['admin_checkout'])) {
            $usedInForms[] = 'adminhtml_checkout';
        }
        $attribute->setData('used_in_forms', $usedInForms);
    }
    $attribute->save();
}

// update user address system attributes used_in_forms data
$attributes = array(
    'prefix', 'firstname', 'middlename', 'lastname', 'suffix', 'company', 'street', 'city', 'country_id',
    'region', 'region_id', 'postcode', 'telephone', 'fax'
);

$defaultUsedInForms = array(
    'adminhtml_user_address',
    'user_address_edit',
    'user_register_address'
);

foreach ($attributes as $attributeCode) {
    $attribute = $eavConfig->getAttribute('user_address', $attributeCode);
    if (!$attribute) {
        continue;
    }
    if (false === ($attribute->getData('is_system') == 1 && $attribute->getData('is_visible') == 0)) {
        $attribute->setData('used_in_forms', $defaultUsedInForms);
    }
    $attribute->save();
}
