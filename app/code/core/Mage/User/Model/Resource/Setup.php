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
 * User resource setup model
 *
 * @category    Mage
 * @package     Mage_User
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Model_Resource_Setup extends Mage_Eav_Model_Entity_Setup
{
    /**
     * Prepare user attribute values to save in additional table
     *
     * @param array $attr
     * @return array
     */
    protected function _prepareValues($attr)
    {
        $data = parent::_prepareValues($attr);
        $data = array_merge($data, array(
            'is_visible'                => $this->_getValue($attr, 'visible', 1),
            'is_system'                 => $this->_getValue($attr, 'system', 1),
            'input_filter'              => $this->_getValue($attr, 'input_filter', null),
            'multiline_count'           => $this->_getValue($attr, 'multiline_count', 0),
            'validate_rules'            => $this->_getValue($attr, 'validate_rules', null),
            'data_model'                => $this->_getValue($attr, 'data', null),
            'sort_order'                => $this->_getValue($attr, 'position', 0)
        ));

        return $data;
    }

    /**
     * Add user attributes to user forms
     *
     * @return void
     */
    public function installUserForms()
    {
        $user           = (int)$this->getEntityTypeId('user');
        $userAddress    = (int)$this->getEntityTypeId('user_address');

        $attributeIds       = array();
        $select = $this->getConnection()->select()
            ->from(
                array('ea' => $this->getTable('eav/attribute')),
                array('entity_type_id', 'attribute_code', 'attribute_id'))
            ->where('ea.entity_type_id IN(?)', array($user, $userAddress));
        foreach ($this->getConnection()->fetchAll($select) as $row) {
            $attributeIds[$row['entity_type_id']][$row['attribute_code']] = $row['attribute_id'];
        }

        $data       = array();
        $entities   = $this->getDefaultEntities();
        $attributes = $entities['user']['attributes'];
        foreach ($attributes as $attributeCode => $attribute) {
            $attributeId = $attributeIds[$user][$attributeCode];
            $attribute['system'] = isset($attribute['system']) ? $attribute['system'] : true;
            $attribute['visible'] = isset($attribute['visible']) ? $attribute['visible'] : true;
            if ($attribute['system'] != true || $attribute['visible'] != false) {
                $usedInForms = array(
                    'user_account_create',
                    'user_account_edit',
                    'checkout_register',
                );
                if (!empty($attribute['adminhtml_only'])) {
                    $usedInForms = array('adminhtml_user');
                } else {
                    $usedInForms[] = 'adminhtml_user';
                }
                if (!empty($attribute['admin_checkout'])) {
                    $usedInForms[] = 'adminhtml_checkout';
                }
                foreach ($usedInForms as $formCode) {
                    $data[] = array(
                        'form_code'     => $formCode,
                        'attribute_id'  => $attributeId
                    );
                }
            }
        }

        $attributes = $entities['user_address']['attributes'];
        foreach ($attributes as $attributeCode => $attribute) {
            $attributeId = $attributeIds[$userAddress][$attributeCode];
            $attribute['system'] = isset($attribute['system']) ? $attribute['system'] : true;
            $attribute['visible'] = isset($attribute['visible']) ? $attribute['visible'] : true;
            if (false === ($attribute['system'] == true && $attribute['visible'] == false)) {
                $usedInForms = array(
                    'adminhtml_user_address',
                    'user_address_edit',
                    'user_register_address'
                );
                foreach ($usedInForms as $formCode) {
                    $data[] = array(
                        'form_code'     => $formCode,
                        'attribute_id'  => $attributeId
                    );
                }
            }
        }

        if ($data) {
            $this->getConnection()->insertMultiple($this->getTable('user/form_attribute'), $data);
        }
    }

    /**
     * Retreive default entities: user, user_address
     *
     * @return array
     */
    public function getDefaultEntities()
    {
        $entities = array(
            'user'                       => array(
                'entity_model'                   => 'user/user',
                'attribute_model'                => 'user/attribute',
                'table'                          => 'user/entity',
                'increment_model'                => 'eav/entity_increment_numeric',
                'additional_attribute_table'     => 'user/eav_attribute',
                'entity_attribute_collection'    => 'user/attribute_collection',
                'attributes'                     => array(
                    'website_id'         => array(
                        'type'               => 'static',
                        'label'              => 'Associate to Website',
                        'input'              => 'select',
                        'source'             => 'user/user_attribute_source_website',
                        'backend'            => 'user/user_attribute_backend_website',
                        'sort_order'         => 10,
                        'position'           => 10,
                        'adminhtml_only'     => 1,
                    ),
                    'store_id'           => array(
                        'type'               => 'static',
                        'label'              => 'Create In',
                        'input'              => 'select',
                        'source'             => 'user/user_attribute_source_store',
                        'backend'            => 'user/user_attribute_backend_store',
                        'sort_order'         => 20,
                        'visible'            => false,
                        'adminhtml_only'     => 1,
                    ),
                    'created_in'         => array(
                        'type'               => 'varchar',
                        'label'              => 'Created From',
                        'input'              => 'text',
                        'required'           => false,
                        'sort_order'         => 20,
                        'position'           => 20,
                        'adminhtml_only'     => 1,
                    ),
                    'prefix'             => array(
                        'type'               => 'varchar',
                        'label'              => 'Prefix',
                        'input'              => 'text',
                        'required'           => false,
                        'sort_order'         => 30,
                        'visible'            => false,
                        'system'             => false,
                        'position'           => 30,
                    ),
                    'firstname'          => array(
                        'type'               => 'varchar',
                        'label'              => 'First Name',
                        'input'              => 'text',
                        'sort_order'         => 40,
                        'validate_rules'     => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'           => 40,
                    ),
                    'middlename'         => array(
                        'type'               => 'varchar',
                        'label'              => 'Middle Name/Initial',
                        'input'              => 'text',
                        'required'           => false,
                        'sort_order'         => 50,
                        'visible'            => false,
                        'system'             => false,
                        'position'           => 50,
                    ),
                    'lastname'           => array(
                        'type'               => 'varchar',
                        'label'              => 'Last Name',
                        'input'              => 'text',
                        'sort_order'         => 60,
                        'validate_rules'     => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'           => 60,
                    ),
                    'suffix'             => array(
                        'type'               => 'varchar',
                        'label'              => 'Suffix',
                        'input'              => 'text',
                        'required'           => false,
                        'sort_order'         => 70,
                        'visible'            => false,
                        'system'             => false,
                        'position'           => 70,
                    ),
                    'email'              => array(
                        'type'               => 'static',
                        'label'              => 'Email',
                        'input'              => 'text',
                        'sort_order'         => 80,
                        'validate_rules'     => 'a:1:{s:16:"input_validation";s:5:"email";}',
                        'position'           => 80,
                        'admin_checkout'    => 1
                    ),
                    'group_id'           => array(
                        'type'               => 'static',
                        'label'              => 'Group',
                        'input'              => 'select',
                        'source'             => 'user/user_attribute_source_group',
                        'sort_order'         => 25,
                        'position'           => 25,
                        'adminhtml_only'     => 1,
                        'admin_checkout'     => 1,
                    ),
                    'dob'                => array(
                        'type'               => 'datetime',
                        'label'              => 'Date Of Birth',
                        'input'              => 'date',
                        'frontend'           => 'eav/entity_attribute_frontend_datetime',
                        'backend'            => 'eav/entity_attribute_backend_datetime',
                        'required'           => false,
                        'sort_order'         => 90,
                        'visible'            => false,
                        'system'             => false,
                        'input_filter'       => 'date',
                        'validate_rules'     => 'a:1:{s:16:"input_validation";s:4:"date";}',
                        'position'           => 90,
                        'admin_checkout'     => 1,
                    ),
                    'password_hash'      => array(
                        'type'               => 'varchar',
                        'input'              => 'hidden',
                        'backend'            => 'user/user_attribute_backend_password',
                        'required'           => false,
                        'sort_order'         => 81,
                        'visible'            => false,
                    ),
                    'default_billing'    => array(
                        'type'               => 'int',
                        'label'              => 'Default Billing Address',
                        'input'              => 'text',
                        'backend'            => 'user/user_attribute_backend_billing',
                        'required'           => false,
                        'sort_order'         => 82,
                        'visible'            => false,
                    ),
                    'default_shipping'   => array(
                        'type'               => 'int',
                        'label'              => 'Default Shipping Address',
                        'input'              => 'text',
                        'backend'            => 'user/user_attribute_backend_shipping',
                        'required'           => false,
                        'sort_order'         => 83,
                        'visible'            => false,
                    ),
                    'taxvat'             => array(
                        'type'               => 'varchar',
                        'label'              => 'Tax/VAT Number',
                        'input'              => 'text',
                        'required'           => false,
                        'sort_order'         => 100,
                        'visible'            => false,
                        'system'             => false,
                        'validate_rules'     => 'a:1:{s:15:"max_text_length";i:255;}',
                        'position'           => 100,
                        'admin_checkout'     => 1,
                    ),
                    'confirmation'       => array(
                        'type'               => 'varchar',
                        'label'              => 'Is Confirmed',
                        'input'              => 'text',
                        'required'           => false,
                        'sort_order'         => 85,
                        'visible'            => false,
                    ),
                    'created_at'         => array(
                        'type'               => 'static',
                        'label'              => 'Created At',
                        'input'              => 'date',
                        'required'           => false,
                        'sort_order'         => 86,
                        'visible'            => false,
                        'system'             => false,
                    ),
                    'gender'             => array(
                        'type'               => 'int',
                        'label'              => 'Gender',
                        'input'              => 'select',
                        'source'             => 'eav/entity_attribute_source_table',
                        'required'           => false,
                        'sort_order'         => 110,
                        'visible'            => false,
                        'system'             => false,
                        'validate_rules'     => 'a:0:{}',
                        'position'           => 110,
                        'admin_checkout'     => 1,
                        'option'             => array('values' => array('Male', 'Female'))
                    ),
                )
            ),

            'user_address'               => array(
                'entity_model'                   => 'user/address',
                'attribute_model'                => 'user/attribute',
                'table'                          => 'user/address_entity',
                'additional_attribute_table'     => 'user/eav_attribute',
                'entity_attribute_collection'    => 'user/address_attribute_collection',
                'attributes'                     => array(
                    'prefix'             => array(
                        'type'               => 'varchar',
                        'label'              => 'Prefix',
                        'input'              => 'text',
                        'required'           => false,
                        'sort_order'         => 10,
                        'visible'            => false,
                        'system'             => false,
                        'position'           => 10,
                    ),
                    'firstname'          => array(
                        'type'               => 'varchar',
                        'label'              => 'First Name',
                        'input'              => 'text',
                        'sort_order'         => 20,
                        'validate_rules'     => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'           => 20,
                    ),
                    'middlename'         => array(
                        'type'               => 'varchar',
                        'label'              => 'Middle Name/Initial',
                        'input'              => 'text',
                        'required'           => false,
                        'sort_order'         => 30,
                        'visible'            => false,
                        'system'             => false,
                        'position'           => 30,
                    ),
                    'lastname'           => array(
                        'type'               => 'varchar',
                        'label'              => 'Last Name',
                        'input'              => 'text',
                        'sort_order'         => 40,
                        'validate_rules'     => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'           => 40,
                    ),
                    'suffix'             => array(
                        'type'               => 'varchar',
                        'label'              => 'Suffix',
                        'input'              => 'text',
                        'required'           => false,
                        'sort_order'         => 50,
                        'visible'            => false,
                        'system'             => false,
                        'position'           => 50,
                    ),
                    'company'            => array(
                        'type'               => 'varchar',
                        'label'              => 'Company',
                        'input'              => 'text',
                        'required'           => false,
                        'sort_order'         => 60,
                        'validate_rules'     => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'           => 60,
                    ),
                    'street'             => array(
                        'type'               => 'text',
                        'label'              => 'Street Address',
                        'input'              => 'multiline',
                        'backend'            => 'user/entity_address_attribute_backend_street',
                        'sort_order'         => 70,
                        'multiline_count'    => 2,
                        'validate_rules'     => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'           => 70,
                    ),
                    'city'               => array(
                        'type'               => 'varchar',
                        'label'              => 'City',
                        'input'              => 'text',
                        'sort_order'         => 80,
                        'validate_rules'     => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'           => 80,
                    ),
                    'country_id'         => array(
                        'type'               => 'varchar',
                        'label'              => 'Country',
                        'input'              => 'select',
                        'source'             => 'user/entity_address_attribute_source_country',
                        'sort_order'         => 90,
                        'position'           => 90,
                    ),
                    'region'             => array(
                        'type'               => 'varchar',
                        'label'              => 'State/Province',
                        'input'              => 'text',
                        'backend'            => 'user/entity_address_attribute_backend_region',
                        'required'           => false,
                        'sort_order'         => 100,
                        'position'           => 100,
                    ),
                    'region_id'          => array(
                        'type'               => 'int',
                        'label'              => 'State/Province',
                        'input'              => 'hidden',
                        'source'             => 'user/entity_address_attribute_source_region',
                        'required'           => false,
                        'sort_order'         => 100,
                        'position'           => 100,
                    ),
                    'postcode'           => array(
                        'type'               => 'varchar',
                        'label'              => 'Zip/Postal Code',
                        'input'              => 'text',
                        'sort_order'         => 110,
                        'validate_rules'     => 'a:0:{}',
                        'data'               => 'user/attribute_data_postcode',
                        'position'           => 110,
                    ),
                    'telephone'          => array(
                        'type'               => 'varchar',
                        'label'              => 'Telephone',
                        'input'              => 'text',
                        'sort_order'         => 120,
                        'validate_rules'     => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'           => 120,
                    ),
                    'fax'                => array(
                        'type'               => 'varchar',
                        'label'              => 'Fax',
                        'input'              => 'text',
                        'required'           => false,
                        'sort_order'         => 130,
                        'validate_rules'     => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'           => 130,
                    ),
                )
            )
        );
        return $entities;
    }
}
