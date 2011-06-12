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

/* @var $eavConfig Mage_Eav_Model_Config */
$eavConfig = Mage::getSingleton('eav/config');

$websites  = Mage::app()->getWebsites(false);
foreach ($websites as $website) {
    /* @var $website Mage_Core_Model_Website */
    $store = $website->getDefaultStore();
    if (!$store) {
        continue;
    }

    // user attributes
    $attributes = array(
        'prefix',
        'middlename',
        'suffix',
        'dob',
        'taxvat',
        'gender'
    );

    foreach ($attributes as $attributeCode) {
        $attribute      = $eavConfig->getAttribute('user', $attributeCode);
        $configValue    = $addressHelper->getConfig($attributeCode . '_show', $store);
        $isVisible      = $attribute->getData('is_visible');
        $isRequired     = $attribute->getData('is_required');

        if ($configValue == 'opt' || $configValue == '1') {
            $scopeIsVisible     = '1';
            $scopeIsRequired    = '0';
        } else if ($configValue == 'req') {
            $scopeIsVisible     = '1';
            $scopeIsRequired    = '1';
        } else {
            $scopeIsVisible     = '0';
            $scopeIsRequired    = '0';
        }

        if ($isVisible != $scopeIsVisible || $isRequired != $scopeIsRequired) {
            $attribute->setWebsite($website);
            $attribute->setScopeIsVisible($scopeIsVisible);
            $attribute->setScopeIsRequired($scopeIsRequired);
            $attribute->save();
        }
    }

    // user address attributes
    $attributes = array(
        'prefix',
        'middlename',
        'suffix',
    );

    foreach ($attributes as $attributeCode) {
        $attribute      = $eavConfig->getAttribute('user_address', $attributeCode);
        $configValue    = $addressHelper->getConfig($attributeCode . '_show', $store);
        $isVisible      = $attribute->getData('is_visible');
        $isRequired     = $attribute->getData('is_required');

        if ($configValue == 'opt' || $configValue == '1') {
            $scopeIsVisible     = '1';
            $scopeIsRequired    = '0';
        } else if ($configValue == 'req') {
            $scopeIsVisible     = '1';
            $scopeIsRequired    = '1';
        } else {
            $scopeIsVisible     = '0';
            $scopeIsRequired    = '0';
        }

        if ($isVisible != $scopeIsVisible || $isRequired != $scopeIsRequired) {
            $attribute->setWebsite($website);
            $attribute->setScopeIsVisible($scopeIsVisible);
            $attribute->setScopeIsRequired($scopeIsRequired);
            $attribute->save();
        }
    }

    $attribute = $eavConfig->getAttribute('user_address', 'street');
    $value     = $addressHelper->getConfig('street_lines', $store);
    if ($attribute->getData('multiline_count') != $value) {
        $attribute->setWebsite($website);
        $attribute->setScopeMultilineCount($value);
        $attribute->save();
    }
}
