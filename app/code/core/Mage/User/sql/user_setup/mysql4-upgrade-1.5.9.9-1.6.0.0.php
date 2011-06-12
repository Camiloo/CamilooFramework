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

/* @var $installer Mage_User_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

/**
 * Drop foreign keys
 */
$installer->getConnection()->dropForeignKey(
    $installer->getTable('user/address_entity'),
    'FK_CUSTOMER_ADDRESS_CUSTOMER_ID'
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('user/eav_attribute'),
    'FK_CUSTOMER_EAV_ATTRIBUTE_ID'
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('user/eav_attribute_website'),
    'FK_CUST_EAV_ATTR_WEBST_ATTR_EAV_ATTR'
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('user/eav_attribute_website'),
    'FK_CUST_EAV_ATTR_WEBST_WEBST_CORE_WEBST'
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('user/entity'),
    'FK_CUSTOMER_ENTITY_STORE'
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('user/entity'),
    'FK_CUSTOMER_WEBSITE'
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('user/form_attribute'),
    'FK_CUSTOMER_FORM_ATTRIBUTE_ATTRIBUTE'
);


$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_address_entity_datetime'),
    'FK_CUSTOMER_ADDRESS_DATETIME_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_address_entity_datetime'),
    'FK_CUSTOMER_ADDRESS_DATETIME_ENTITY'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_address_entity_datetime'),
    'FK_CUSTOMER_ADDRESS_DATETIME_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_address_entity_decimal'),
    'FK_CUSTOMER_ADDRESS_DECIMAL_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_address_entity_decimal'),
    'FK_CUSTOMER_ADDRESS_DECIMAL_ENTITY'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_address_entity_decimal'),
    'FK_CUSTOMER_ADDRESS_DECIMAL_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_address_entity_int'),
    'FK_CUSTOMER_ADDRESS_INT_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_address_entity_int'),
    'FK_CUSTOMER_ADDRESS_INT_ENTITY'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_address_entity_int'),
    'FK_CUSTOMER_ADDRESS_INT_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_address_entity_text'),
    'FK_CUSTOMER_ADDRESS_TEXT_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_address_entity_text'),
    'FK_CUSTOMER_ADDRESS_TEXT_ENTITY'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_address_entity_text'),
    'FK_CUSTOMER_ADDRESS_TEXT_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_address_entity_varchar'),
    'FK_CUSTOMER_ADDRESS_VARCHAR_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_address_entity_varchar'),
    'FK_CUSTOMER_ADDRESS_VARCHAR_ENTITY'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_address_entity_varchar'),
    'FK_CUSTOMER_ADDRESS_VARCHAR_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_entity_datetime'),
    'FK_CUSTOMER_DATETIME_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_entity_datetime'),
    'FK_CUSTOMER_DATETIME_ENTITY'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_entity_datetime'),
    'FK_CUSTOMER_DATETIME_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_entity_decimal'),
    'FK_CUSTOMER_DECIMAL_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_entity_decimal'),
    'FK_CUSTOMER_DECIMAL_ENTITY'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_entity_decimal'),
    'FK_CUSTOMER_DECIMAL_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_entity_int'),
    'FK_CUSTOMER_INT_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_entity_int'),
    'FK_CUSTOMER_INT_ENTITY'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_entity_int'),
    'FK_CUSTOMER_INT_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_entity_text'),
    'FK_CUSTOMER_TEXT_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_entity_text'),
    'FK_CUSTOMER_TEXT_ENTITY'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_entity_text'),
    'FK_CUSTOMER_TEXT_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_entity_varchar'),
    'FK_CUSTOMER_VARCHAR_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_entity_varchar'),
    'FK_CUSTOMER_VARCHAR_ENTITY'
);

$connection = $installer->getConnection()->dropForeignKey(
    $installer->getTable('user_entity_varchar'),
    'FK_CUSTOMER_VARCHAR_ENTITY_TYPE'
);


/**
 * Drop indexes
 */
$installer->getConnection()->dropIndex(
    $installer->getTable('user/address_entity'),
    'FK_CUSTOMER_ADDRESS_CUSTOMER_ID'
);

$installer->getConnection()->dropIndex(
    $installer->getTable('user/eav_attribute_website'),
    'IDX_WEBSITE'
);

$installer->getConnection()->dropIndex(
    $installer->getTable('user/entity'),
    'FK_CUSTOMER_ENTITY_STORE'
);

$installer->getConnection()->dropIndex(
    $installer->getTable('user/entity'),
    'IDX_ENTITY_TYPE'
);

$installer->getConnection()->dropIndex(
    $installer->getTable('user/entity'),
    'IDX_AUTH'
);

$installer->getConnection()->dropIndex(
    $installer->getTable('user/entity'),
    'FK_CUSTOMER_WEBSITE'
);

$installer->getConnection()->dropIndex(
    $installer->getTable('user/form_attribute'),
    'IDX_CUSTOMER_FORM_ATTRIBUTE_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_datetime'),
    'IDX_ATTRIBUTE_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_datetime'),
    'FK_CUSTOMER_ADDRESS_DATETIME_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_datetime'),
    'FK_CUSTOMER_ADDRESS_DATETIME_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_datetime'),
    'FK_CUSTOMER_ADDRESS_DATETIME_ENTITY'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_datetime'),
    'IDX_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_decimal'),
    'IDX_ATTRIBUTE_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_decimal'),
    'FK_CUSTOMER_ADDRESS_DECIMAL_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_decimal'),
    'FK_CUSTOMER_ADDRESS_DECIMAL_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_decimal'),
    'FK_CUSTOMER_ADDRESS_DECIMAL_ENTITY'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_decimal'),
    'IDX_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_int'),
    'IDX_ATTRIBUTE_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_int'),
    'FK_CUSTOMER_ADDRESS_INT_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_int'),
    'FK_CUSTOMER_ADDRESS_INT_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_int'),
    'FK_CUSTOMER_ADDRESS_INT_ENTITY'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_int'),
    'IDX_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_text'),
    'IDX_ATTRIBUTE_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_text'),
    'FK_CUSTOMER_ADDRESS_TEXT_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_text'),
    'FK_CUSTOMER_ADDRESS_TEXT_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_text'),
    'FK_CUSTOMER_ADDRESS_TEXT_ENTITY'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_varchar'),
    'IDX_ATTRIBUTE_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_varchar'),
    'FK_CUSTOMER_ADDRESS_VARCHAR_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_varchar'),
    'FK_CUSTOMER_ADDRESS_VARCHAR_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_varchar'),
    'FK_CUSTOMER_ADDRESS_VARCHAR_ENTITY'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_address_entity_varchar'),
    'IDX_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_datetime'),
    'IDX_ATTRIBUTE_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_datetime'),
    'FK_CUSTOMER_DATETIME_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_datetime'),
    'FK_CUSTOMER_DATETIME_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_datetime'),
    'FK_CUSTOMER_DATETIME_ENTITY'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_datetime'),
    'IDX_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_decimal'),
    'IDX_ATTRIBUTE_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_decimal'),
    'FK_CUSTOMER_DECIMAL_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_decimal'),
    'FK_CUSTOMER_DECIMAL_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_decimal'),
    'FK_CUSTOMER_DECIMAL_ENTITY'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_decimal'),
    'IDX_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_int'),
    'IDX_ATTRIBUTE_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_int'),
    'FK_CUSTOMER_INT_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_int'),
    'FK_CUSTOMER_INT_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_int'),
    'FK_CUSTOMER_INT_ENTITY'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_int'),
    'IDX_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_text'),
    'IDX_ATTRIBUTE_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_text'),
    'FK_CUSTOMER_TEXT_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_text'),
    'FK_CUSTOMER_TEXT_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_text'),
    'FK_CUSTOMER_TEXT_ENTITY'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_varchar'),
    'IDX_ATTRIBUTE_VALUE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_varchar'),
    'FK_CUSTOMER_VARCHAR_ENTITY_TYPE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_varchar'),
    'FK_CUSTOMER_VARCHAR_ATTRIBUTE'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_varchar'),
    'FK_CUSTOMER_VARCHAR_ENTITY'
);

$connection = $installer->getConnection()->dropIndex(
    $installer->getTable('user_entity_varchar'),
    'IDX_VALUE'
);


/**
 * Change columns
 */
$tables = array(
    $installer->getTable('user/entity') => array(
        'columns' => array(
            'entity_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Entity Id'
            ),
            'entity_type_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Type Id'
            ),
            'attribute_set_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Attribute Set Id'
            ),
            'website_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'comment'   => 'Website Id'
            ),
            'email' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 255,
                'comment'   => 'Email'
            ),
            'group_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Group Id'
            ),
            'increment_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 50,
                'comment'   => 'Increment Id'
            ),
            'store_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'default'   => '0',
                'comment'   => 'Store Id'
            ),
            'created_at' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
                'nullable'  => false,
                'comment'   => 'Created At'
            ),
            'updated_at' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
                'nullable'  => false,
                'comment'   => 'Updated At'
            ),
            'is_active' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '1',
                'comment'   => 'Is Active'
            )
        ),
        'comment' => 'User Entity'
    ),
    $installer->getTable('user/address_entity') => array(
        'columns' => array(
            'entity_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Entity Id'
            ),
            'entity_type_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Type Id'
            ),
            'attribute_set_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Attribute Set Id'
            ),
            'increment_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 50,
                'comment'   => 'Increment Id'
            ),
            'parent_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'comment'   => 'Parent Id'
            ),
            'created_at' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
                'nullable'  => false,
                'comment'   => 'Created At'
            ),
            'updated_at' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
                'nullable'  => false,
                'comment'   => 'Updated At'
            ),
            'is_active' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '1',
                'comment'   => 'Is Active'
            )
        ),
        'comment' => 'User Address Entity'
    ),
    $installer->getTable('user/user_group') => array(
        'columns' => array(
            'user_group_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'identity'  => true,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'User Group Id'
            ),
            'user_group_code' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 32,
                'nullable'  => false,
                'comment'   => 'User Group Code'
            ),
            'tax_class_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Tax Class Id'
            )
        ),
        'comment' => 'User Group'
    ),
    $installer->getTable('user/eav_attribute') => array(
        'columns' => array(
            'attribute_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Attribute Id'
            ),
            'is_visible' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '1',
                'comment'   => 'Is Visible'
            ),
            'input_filter' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 255,
                'comment'   => 'Input Filter'
            ),
            'multiline_count' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '1',
                'comment'   => 'Multiline Count'
            ),
            'validate_rules' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => '64K',
                'comment'   => 'Validate Rules'
            ),
            'is_system' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Is System'
            ),
            'sort_order' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Sort Order'
            ),
            'data_model' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 255,
                'comment'   => 'Data Model'
            )
        ),
        'comment' => 'User Eav Attribute'
    ),
    $installer->getTable('user/eav_attribute_website') => array(
        'columns' => array(
            'attribute_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Attribute Id'
            ),
            'website_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Website Id'
            ),
            'is_visible' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'comment'   => 'Is Visible'
            ),
            'is_required' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'comment'   => 'Is Required'
            ),
            'default_value' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => '64K',
                'comment'   => 'Default Value'
            ),
            'multiline_count' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'comment'   => 'Multiline Count'
            )
        ),
        'comment' => 'User Eav Attribute Website'
    ),
    $installer->getTable('user/form_attribute') => array(
        'columns' => array(
            'form_code' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 32,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Form Code'
            ),
            'attribute_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Attribute Id'
            )
        ),
        'comment' => 'User Form Attribute'
    ),
    $installer->getTable('user_address_entity_datetime') => array(
        'columns' => array(
            'value_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Value Id'
            ),
            'entity_type_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Type Id'
            ),
            'attribute_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Attribute Id'
            ),
            'entity_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Id'
            ),
            'value' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
                'nullable'  => false,
                'comment'   => 'Value'
            )
        ),
        'comment' => 'User Address Entity Datetime'
    ),
    $installer->getTable('user_address_entity_decimal') => array(
        'columns' => array(
            'value_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Value Id'
            ),
            'entity_type_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Type Id'
            ),
            'attribute_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Attribute Id'
            ),
            'entity_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Id'
            ),
            'value' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_DECIMAL,
                'scale'     => 4,
                'precision' => 12,
                'nullable'  => false,
                'default'   => '0.0000',
                'comment'   => 'Value'
            )
        ),
        'comment' => 'User Address Entity Decimal'
    ),
    $installer->getTable('user_address_entity_int') => array(
        'columns' => array(
            'value_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Value Id'
            ),
            'entity_type_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Type Id'
            ),
            'attribute_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Attribute Id'
            ),
            'entity_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Id'
            ),
            'value' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Value'
            )
        ),
        'comment' => 'User Address Entity Int'
    ),
    $installer->getTable('user_address_entity_text') => array(
        'columns' => array(
            'value_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Value Id'
            ),
            'entity_type_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Type Id'
            ),
            'attribute_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Attribute Id'
            ),
            'entity_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Id'
            ),
            'value' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => '64K',
                'nullable'  => false,
                'comment'   => 'Value'
            )
        ),
        'comment' => 'User Address Entity Text'
    ),
    $installer->getTable('user_address_entity_varchar') => array(
        'columns' => array(
            'value_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Value Id'
            ),
            'entity_type_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Type Id'
            ),
            'attribute_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Attribute Id'
            ),
            'entity_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Id'
            ),
            'value' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 255,
                'comment'   => 'Value'
            )
        ),
        'comment' => 'User Address Entity Varchar'
    ),
    $installer->getTable('user_entity_datetime') => array(
        'columns' => array(
            'value_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Value Id'
            ),
            'entity_type_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Type Id'
            ),
            'attribute_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Attribute Id'
            ),
            'entity_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Id'
            ),
            'value' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
                'nullable'  => false,
                'comment'   => 'Value'
            )
        ),
        'comment' => 'User Entity Datetime'
    ),
    $installer->getTable('user_entity_decimal') => array(
        'columns' => array(
            'value_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Value Id'
            ),
            'entity_type_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Type Id'
            ),
            'attribute_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Attribute Id'
            ),
            'entity_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Id'
            ),
            'value' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_DECIMAL,
                'scale'     => 4,
                'precision' => 12,
                'nullable'  => false,
                'default'   => '0.0000',
                'comment'   => 'Value'
            )
        ),
        'comment' => 'User Entity Decimal'
    ),
    $installer->getTable('user_entity_int') => array(
        'columns' => array(
            'value_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Value Id'
            ),
            'entity_type_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Type Id'
            ),
            'attribute_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Attribute Id'
            ),
            'entity_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Id'
            ),
            'value' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Value'
            )
        ),
        'comment' => 'User Entity Int'
    ),
    $installer->getTable('user_entity_text') => array(
        'columns' => array(
            'value_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Value Id'
            ),
            'entity_type_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Type Id'
            ),
            'attribute_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Attribute Id'
            ),
            'entity_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Id'
            ),
            'value' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => '64K',
                'nullable'  => false,
                'comment'   => 'Value'
            )
        ),
        'comment' => 'User Entity Text'
    ),
    $installer->getTable('user_entity_varchar') => array(
        'columns' => array(
            'value_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true,
                'comment'   => 'Value Id'
            ),
            'entity_type_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Type Id'
            ),
            'attribute_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Attribute Id'
            ),
            'entity_id' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
                'comment'   => 'Entity Id'
            ),
            'value' => array(
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 255,
                'comment'   => 'Value'
            )
        ),
        'comment' => 'User Entity Varchar'
    )
);

$installer->getConnection()->modifyTables($tables);


/**
 * Add indexes
 */
$installer->getConnection()->addIndex(
    $installer->getTable('user/address_entity'),
    $installer->getIdxName('user/address_entity', array('parent_id')),
    array('parent_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$installer->getConnection()->addIndex(
    $installer->getTable('user/eav_attribute_website'),
    $installer->getIdxName('user/eav_attribute_website', array('website_id')),
    array('website_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$installer->getConnection()->addIndex(
    $installer->getTable('user/entity'),
    $installer->getIdxName('user/entity', array('store_id')),
    array('store_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$installer->getConnection()->addIndex(
    $installer->getTable('user/entity'),
    $installer->getIdxName('user/entity', array('entity_type_id')),
    array('entity_type_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$installer->getConnection()->addIndex(
    $installer->getTable('user/entity'),
    $installer->getIdxName('user/entity', array('email', 'website_id')),
    array('email', 'website_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$installer->getConnection()->addIndex(
    $installer->getTable('user/entity'),
    $installer->getIdxName('user/entity', array('website_id')),
    array('website_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$installer->getConnection()->addIndex(
    $installer->getTable('user/form_attribute'),
    $installer->getIdxName('user/form_attribute', array('attribute_id')),
    array('attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_datetime'),
    $installer->getIdxName(
        'user_address_entity_datetime',
        array('entity_id', 'attribute_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('entity_id', 'attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_datetime'),
    $installer->getIdxName('user_address_entity_datetime', array('entity_type_id')),
    array('entity_type_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_datetime'),
    $installer->getIdxName('user_address_entity_datetime', array('attribute_id')),
    array('attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_datetime'),
    $installer->getIdxName('user_address_entity_datetime', array('entity_id')),
    array('entity_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_datetime'),
    $installer->getIdxName('user_address_entity_datetime', array('entity_id', 'attribute_id', 'value')),
    array('entity_id', 'attribute_id', 'value'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_decimal'),
    $installer->getIdxName(
        'user_address_entity_decimal',
        array('entity_id', 'attribute_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('entity_id', 'attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_decimal'),
    $installer->getIdxName('user_address_entity_decimal', array('entity_type_id')),
    array('entity_type_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_decimal'),
    $installer->getIdxName('user_address_entity_decimal', array('attribute_id')),
    array('attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_decimal'),
    $installer->getIdxName('user_address_entity_decimal', array('entity_id')),
    array('entity_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_decimal'),
    $installer->getIdxName('user_address_entity_decimal', array('entity_id', 'attribute_id', 'value')),
    array('entity_id', 'attribute_id', 'value'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_int'),
    $installer->getIdxName(
        'user_address_entity_int',
        array('entity_id', 'attribute_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('entity_id', 'attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_int'),
    $installer->getIdxName('user_address_entity_int', array('entity_type_id')),
    array('entity_type_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_int'),
    $installer->getIdxName('user_address_entity_int', array('attribute_id')),
    array('attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_int'),
    $installer->getIdxName('user_address_entity_int', array('entity_id')),
    array('entity_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_int'),
    $installer->getIdxName('user_address_entity_int', array('entity_id', 'attribute_id', 'value')),
    array('entity_id', 'attribute_id', 'value'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_text'),
    $installer->getIdxName(
        'user_address_entity_text',
        array('entity_id', 'attribute_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('entity_id', 'attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_text'),
    $installer->getIdxName('user_address_entity_text', array('entity_type_id')),
    array('entity_type_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_text'),
    $installer->getIdxName('user_address_entity_text', array('attribute_id')),
    array('attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_text'),
    $installer->getIdxName('user_address_entity_text', array('entity_id')),
    array('entity_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_varchar'),
    $installer->getIdxName(
        'user_address_entity_varchar',
        array('entity_id', 'attribute_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('entity_id', 'attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_varchar'),
    $installer->getIdxName('user_address_entity_varchar', array('entity_type_id')),
    array('entity_type_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_varchar'),
    $installer->getIdxName('user_address_entity_varchar', array('attribute_id')),
    array('attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_varchar'),
    $installer->getIdxName('user_address_entity_varchar', array('entity_id')),
    array('entity_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_address_entity_varchar'),
    $installer->getIdxName('user_address_entity_varchar', array('entity_id', 'attribute_id', 'value')),
    array('entity_id', 'attribute_id', 'value'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_datetime'),
    $installer->getIdxName(
        'user_entity_datetime',
        array('entity_id', 'attribute_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('entity_id', 'attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_datetime'),
    $installer->getIdxName('user_entity_datetime', array('entity_type_id')),
    array('entity_type_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_datetime'),
    $installer->getIdxName('user_entity_datetime', array('attribute_id')),
    array('attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_datetime'),
    $installer->getIdxName('user_entity_datetime', array('entity_id')),
    array('entity_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_datetime'),
    $installer->getIdxName('user_entity_datetime', array('entity_id', 'attribute_id', 'value')),
    array('entity_id', 'attribute_id', 'value'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_decimal'),
    $installer->getIdxName(
        'user_entity_decimal',
        array('entity_id', 'attribute_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('entity_id', 'attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_decimal'),
    $installer->getIdxName('user_entity_decimal', array('entity_type_id')),
    array('entity_type_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_decimal'),
    $installer->getIdxName('user_entity_decimal', array('attribute_id')),
    array('attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_decimal'),
    $installer->getIdxName('user_entity_decimal', array('entity_id')),
    array('entity_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_decimal'),
    $installer->getIdxName('user_entity_decimal', array('entity_id', 'attribute_id', 'value')),
    array('entity_id', 'attribute_id', 'value'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_int'),
    $installer->getIdxName(
        'user_entity_int',
        array('entity_id', 'attribute_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('entity_id', 'attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_int'),
    $installer->getIdxName('user_entity_int', array('entity_type_id')),
    array('entity_type_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_int'),
    $installer->getIdxName('user_entity_int', array('attribute_id')),
    array('attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_int'),
    $installer->getIdxName('user_entity_int', array('entity_id')),
    array('entity_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_int'),
    $installer->getIdxName('user_entity_int', array('entity_id', 'attribute_id', 'value')),
    array('entity_id', 'attribute_id', 'value'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_text'),
    $installer->getIdxName(
        'user_entity_text',
        array('entity_id', 'attribute_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('entity_id', 'attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_text'),
    $installer->getIdxName('user_entity_text', array('entity_type_id')),
    array('entity_type_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_text'),
    $installer->getIdxName('user_entity_text', array('attribute_id')),
    array('attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_text'),
    $installer->getIdxName('user_entity_text', array('entity_id')),
    array('entity_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_varchar'),
    $installer->getIdxName(
        'user_entity_varchar',
        array('entity_id', 'attribute_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('entity_id', 'attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_varchar'),
    $installer->getIdxName('user_entity_varchar', array('entity_type_id')),
    array('entity_type_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_varchar'),
    $installer->getIdxName('user_entity_varchar', array('attribute_id')),
    array('attribute_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_varchar'),
    $installer->getIdxName('user_entity_varchar', array('entity_id')),
    array('entity_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection = $installer->getConnection()->addIndex(
    $installer->getTable('user_entity_varchar'),
    $installer->getIdxName('user_entity_varchar', array('entity_id', 'attribute_id', 'value')),
    array('entity_id', 'attribute_id', 'value'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);


/**
 * Add foreign keys
 */
$installer->getConnection()->addForeignKey(
    $installer->getFkName('user/eav_attribute', 'attribute_id', 'eav/attribute', 'attribute_id'),
    $installer->getTable('user/eav_attribute'),
    'attribute_id',
    $installer->getTable('eav/attribute'),
    'attribute_id'
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('user/address_entity', 'parent_id', 'user/entity', 'entity_id'),
    $installer->getTable('user/address_entity'),
    'parent_id',
    $installer->getTable('user/entity'),
    'entity_id'
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('user/eav_attribute_website', 'attribute_id', 'eav/attribute', 'attribute_id'),
    $installer->getTable('user/eav_attribute_website'),
    'attribute_id',
    $installer->getTable('eav/attribute'),
    'attribute_id'
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('user/eav_attribute_website', 'website_id', 'core/website', 'website_id'),
    $installer->getTable('user/eav_attribute_website'),
    'website_id',
    $installer->getTable('core/website'),
    'website_id'
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('user/entity', 'store_id', 'core/store', 'store_id'),
    $installer->getTable('user/entity'),
    'store_id',
    $installer->getTable('core/store'),
    'store_id',
    Varien_Db_Ddl_Table::ACTION_SET_NULL
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('user/entity', 'website_id', 'core/website', 'website_id'),
    $installer->getTable('user/entity'),
    'website_id',
    $installer->getTable('core/website'),
    'website_id',
    Varien_Db_Ddl_Table::ACTION_SET_NULL
);

$installer->getConnection()->addForeignKey(
    $installer->getFkName('user/form_attribute', 'attribute_id', 'eav/attribute', 'attribute_id'),
    $installer->getTable('user/form_attribute'),
    'attribute_id',
    $installer->getTable('eav/attribute'),
    'attribute_id'
);



$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_address_entity_datetime', 'attribute_id', 'eav/attribute', 'attribute_id'),
    $installer->getTable('user_address_entity_datetime'),
    'attribute_id',
    $installer->getTable('eav/attribute'),
    'attribute_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_address_entity_datetime', 'entity_id', 'user_address_entity', 'entity_id'),
    $installer->getTable('user_address_entity_datetime'),
    'entity_id',
    $installer->getTable('user_address_entity'),
    'entity_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_address_entity_datetime', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
    $installer->getTable('user_address_entity_datetime'),
    'entity_type_id',
    $installer->getTable('eav/entity_type'),
    'entity_type_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_address_entity_decimal', 'attribute_id', 'eav/attribute', 'attribute_id'),
    $installer->getTable('user_address_entity_decimal'),
    'attribute_id',
    $installer->getTable('eav/attribute'),
    'attribute_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_address_entity_decimal', 'entity_id', 'user_address_entity', 'entity_id'),
    $installer->getTable('user_address_entity_decimal'),
    'entity_id',
    $installer->getTable('user_address_entity'),
    'entity_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_address_entity_decimal', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
    $installer->getTable('user_address_entity_decimal'),
    'entity_type_id',
    $installer->getTable('eav/entity_type'),
    'entity_type_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_address_entity_int', 'attribute_id', 'eav/attribute', 'attribute_id'),
    $installer->getTable('user_address_entity_int'),
    'attribute_id',
    $installer->getTable('eav/attribute'),
    'attribute_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_address_entity_int', 'entity_id', 'user_address_entity', 'entity_id'),
    $installer->getTable('user_address_entity_int'),
    'entity_id',
    $installer->getTable('user_address_entity'),
    'entity_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_address_entity_int', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
    $installer->getTable('user_address_entity_int'),
    'entity_type_id',
    $installer->getTable('eav/entity_type'),
    'entity_type_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_address_entity_text', 'attribute_id', 'eav/attribute', 'attribute_id'),
    $installer->getTable('user_address_entity_text'),
    'attribute_id',
    $installer->getTable('eav/attribute'),
    'attribute_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_address_entity_text', 'entity_id', 'user_address_entity', 'entity_id'),
    $installer->getTable('user_address_entity_text'),
    'entity_id',
    $installer->getTable('user_address_entity'),
    'entity_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_address_entity_text', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
    $installer->getTable('user_address_entity_text'),
    'entity_type_id',
    $installer->getTable('eav/entity_type'),
    'entity_type_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_address_entity_varchar', 'attribute_id', 'eav/attribute', 'attribute_id'),
    $installer->getTable('user_address_entity_varchar'),
    'attribute_id',
    $installer->getTable('eav/attribute'),
    'attribute_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_address_entity_varchar', 'entity_id', 'user_address_entity', 'entity_id'),
    $installer->getTable('user_address_entity_varchar'),
    'entity_id',
    $installer->getTable('user_address_entity'),
    'entity_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_address_entity_varchar', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
    $installer->getTable('user_address_entity_varchar'),
    'entity_type_id',
    $installer->getTable('eav/entity_type'),
    'entity_type_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_entity_datetime', 'attribute_id', 'eav/attribute', 'attribute_id'),
    $installer->getTable('user_entity_datetime'),
    'attribute_id',
    $installer->getTable('eav/attribute'),
    'attribute_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_entity_datetime', 'entity_id', 'user/entity', 'entity_id'),
    $installer->getTable('user_entity_datetime'),
    'entity_id',
    $installer->getTable('user/entity'),
    'entity_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_entity_datetime', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
    $installer->getTable('user_entity_datetime'),
    'entity_type_id',
    $installer->getTable('eav/entity_type'),
    'entity_type_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_entity_decimal', 'attribute_id', 'eav/attribute', 'attribute_id'),
    $installer->getTable('user_entity_decimal'),
    'attribute_id',
    $installer->getTable('eav/attribute'),
    'attribute_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_entity_decimal', 'entity_id', 'user/entity', 'entity_id'),
    $installer->getTable('user_entity_decimal'),
    'entity_id',
    $installer->getTable('user/entity'),
    'entity_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_entity_decimal', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
    $installer->getTable('user_entity_decimal'),
    'entity_type_id',
    $installer->getTable('eav/entity_type'),
    'entity_type_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_entity_int', 'attribute_id', 'eav/attribute', 'attribute_id'),
    $installer->getTable('user_entity_int'),
    'attribute_id',
    $installer->getTable('eav/attribute'),
    'attribute_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_entity_int', 'entity_id', 'user/entity', 'entity_id'),
    $installer->getTable('user_entity_int'),
    'entity_id',
    $installer->getTable('user/entity'),
    'entity_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_entity_int', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
    $installer->getTable('user_entity_int'),
    'entity_type_id',
    $installer->getTable('eav/entity_type'),
    'entity_type_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_entity_text', 'attribute_id', 'eav/attribute', 'attribute_id'),
    $installer->getTable('user_entity_text'),
    'attribute_id',
    $installer->getTable('eav/attribute'),
    'attribute_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_entity_text', 'entity_id', 'user/entity', 'entity_id'),
    $installer->getTable('user_entity_text'),
    'entity_id',
    $installer->getTable('user/entity'),
    'entity_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_entity_text', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
    $installer->getTable('user_entity_text'),
    'entity_type_id',
    $installer->getTable('eav/entity_type'),
    'entity_type_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_entity_varchar', 'attribute_id', 'eav/attribute', 'attribute_id'),
    $installer->getTable('user_entity_varchar'),
    'attribute_id',
    $installer->getTable('eav/attribute'),
    'attribute_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_entity_varchar', 'entity_id', 'user/entity', 'entity_id'),
    $installer->getTable('user_entity_varchar'),
    'entity_id',
    $installer->getTable('user/entity'),
    'entity_id'
);

$connection = $installer->getConnection()->addForeignKey(
    $installer->getFkName('user_entity_varchar', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
    $installer->getTable('user_entity_varchar'),
    'entity_type_id',
    $installer->getTable('eav/entity_type'),
    'entity_type_id'
);

$installer->endSetup();
