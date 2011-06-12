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

/* @var $addressHelper Mage_User_Helper_Address */
$addressHelper = Mage::helper('user/address');
$store         = Mage::app()->getStore(Mage_Core_Model_App::ADMIN_STORE_ID);

/* @var $eavConfig Mage_Eav_Model_Config */
$eavConfig = Mage::getSingleton('eav/config');

// update user system attributes data
$attributes = array(
    'confirmation'      => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 0,
        'sort_order'        => 0
    ),
    'default_billing'   => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 0,
        'sort_order'        => 0
    ),
    'default_shipping'  => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 0,
        'sort_order'        => 0
    ),
    'password_hash'     => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 0,
        'sort_order'        => 0
    ),
    'website_id'        => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 10,
        'adminhtml_only'    => 1
    ),
    'created_in'        => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 20,
        'is_required'       => 0,
        'adminhtml_only'    => 1
    ),
    'store_id'          => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 0,
        'sort_order'        => 0
    ),
    'group_id'          => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 25,
        'adminhtml_only'    => 1,
        'admin_checkout'    => 1
    ),
    'prefix'            => array(
        'is_user_defined'   => 0,
        'is_system'         => 0,
        'is_visible'        => $addressHelper->getConfig('prefix_show', $store) == '' ? 0 : 1,
        'sort_order'        => 30,
        'is_required'       => $addressHelper->getConfig('prefix_show', $store) == 'req' ? 1 : 0
    ),
    'firstname'         => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 40,
        'is_required'       => 1,
        'validate_rules'    => array(
            'max_text_length'   => 255,
            'min_text_length'   => 1
        ),
    ),
    'middlename'        => array(
        'is_user_defined'   => 0,
        'is_system'         => 0,
        'is_visible'        => $addressHelper->getConfig('middlename_show', $store) == '' ? 0 : 1,
        'sort_order'        => 50,
        'is_required'       => $addressHelper->getConfig('middlename_show', $store) == 'req' ? 1 : 0
    ),
    'lastname'          => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 60,
        'is_required'       => 1,
        'validate_rules'    => array(
            'max_text_length'   => 255,
            'min_text_length'   => 1
        ),
    ),
    'suffix'            => array(
        'is_user_defined'   => 0,
        'is_system'         => 0,
        'is_visible'        => $addressHelper->getConfig('suffix_show', $store) == '' ? 0 : 1,
        'sort_order'        => 70,
        'is_required'       => $addressHelper->getConfig('suffix_show', $store) == 'req' ? 1 : 0
    ),
    'email'             => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 80,
        'is_required'       => 1,
        'validate_rules'    => array(
            'input_validation'  => 'email'
        ),
        'admin_checkout'    => 1
    ),
    'dob'               => array(
        'is_user_defined'   => 0,
        'is_system'         => 0,
        'is_visible'        => $addressHelper->getConfig('dob_show', $store) == '' ? 0 : 1,
        'sort_order'        => 90,
        'is_required'       => $addressHelper->getConfig('dob_show', $store) == 'req' ? 1 : 0,
        'validate_rules'    => array(
            'input_validation'  => 'date'
        ),
        'input_filter'      => 'date',
        'admin_checkout'    => 1
    ),
    'taxvat'            => array(
        'is_user_defined'   => 0,
        'is_system'         => 0,
        'is_visible'        => $addressHelper->getConfig('dob_show', $store) == '' ? 0 : 1,
        'sort_order'        => 100,
        'is_required'       => $addressHelper->getConfig('dob_show', $store) == 'req' ? 1 : 0,
        'validate_rules'    => array(
            'max_text_length'   => 255,
        ),
        'admin_checkout'    => 1
    ),
    'gender'            => array(
        'is_user_defined'   => 0,
        'is_system'         => 0,
        'is_visible'        => $addressHelper->getConfig('gender_show', $store) == '' ? 0 : 1,
        'sort_order'        => 110,
        'is_required'       => $addressHelper->getConfig('gender_show', $store) == 'req' ? 1 : 0,
        'validate_rules'    => array(),
        'admin_checkout'    => 1
    ),
);

foreach ($attributes as $attributeCode => $data) {
    $attribute = $eavConfig->getAttribute('user', $attributeCode);
    $attribute->setWebsite($store->getWebsite());
    $attribute->addData($data);
    if (false === ($data['is_system'] == 1 && $data['is_visible'] == 0)) {
        $usedInForms = array(
            'user_account_create',
            'user_account_edit',
            'checkout_register',
        );
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

// update user address system attributes data
$attributes = array(
    'prefix'            => array(
        'is_user_defined'   => 0,
        'is_system'         => 0,
        'is_visible'        => $addressHelper->getConfig('prefix_show', $store) == '' ? 0 : 1,
        'sort_order'        => 10,
        'is_required'       => $addressHelper->getConfig('prefix_show', $store) == 'req' ? 1 : 0,
    ),
    'firstname'         => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 20,
        'is_required'       => 1,
        'validate_rules'    => array(
            'max_text_length'   => 255,
            'min_text_length'   => 1
        ),
    ),
    'middlename'        => array(
        'is_user_defined'   => 0,
        'is_system'         => 0,
        'is_visible'        => $addressHelper->getConfig('middlename_show', $store) == '' ? 0 : 1,
        'sort_order'        => 30,
        'is_required'       => $addressHelper->getConfig('middlename_show', $store) == 'req' ? 1 : 0,
    ),
    'lastname'          => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 40,
        'is_required'       => 1,
        'validate_rules'    => array(
            'max_text_length'   => 255,
            'min_text_length'   => 1
        ),
    ),
    'suffix'            => array(
        'is_user_defined'   => 0,
        'is_system'         => 0,
        'is_visible'        => $addressHelper->getConfig('suffix_show', $store) == '' ? 0 : 1,
        'sort_order'        => 50,
        'is_required'       => $addressHelper->getConfig('suffix_show', $store) == 'req' ? 1 : 0,
    ),
    'company'           => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 60,
        'is_required'       => 0,
        'validate_rules'    => array(
            'max_text_length'   => 255,
            'min_text_length'   => 1
        ),
    ),
    'street'           => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 70,
        'multiline_count'   => $addressHelper->getConfig('street_lines', $store),
        'is_required'       => 1,
        'validate_rules'    => array(
            'max_text_length'   => 255,
            'min_text_length'   => 1
        ),
    ),
    'city'              => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 80,
        'is_required'       => 1,
        'validate_rules'    => array(
            'max_text_length'   => 255,
            'min_text_length'   => 1
        ),
    ),
    'country_id'        => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 90,
        'is_required'       => 1,
    ),
    'region'            => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 100,
        'is_required'       => 0,
    ),
    'region_id'         => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 100,
        'is_required'       => 0,
    ),
    'postcode'          => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 110,
        'is_required'       => 1,
        'validate_rules'    => array(
        ),
    ),
    'telephone'         => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 120,
        'is_required'       => 1,
        'validate_rules'    => array(
            'max_text_length'   => 255,
            'min_text_length'   => 1
        ),
    ),
    'fax'               => array(
        'is_user_defined'   => 0,
        'is_system'         => 1,
        'is_visible'        => 1,
        'sort_order'        => 130,
        'is_required'       => 0,
        'validate_rules'    => array(
            'max_text_length'   => 255,
            'min_text_length'   => 1
        ),
    ),
);

foreach ($attributes as $attributeCode => $data) {
    $attribute = $eavConfig->getAttribute('user_address', $attributeCode);
    $attribute->setWebsite($store->getWebsite());
    $attribute->addData($data);
    if (false === ($data['is_system'] == 1 && $data['is_visible'] == 0)) {
        $usedInForms = array(
            'adminhtml_user_address',
            'user_address_edit',
            'user_register_address'
        );
        $attribute->setData('used_in_forms', $usedInForms);
    }
    $attribute->save();
}
