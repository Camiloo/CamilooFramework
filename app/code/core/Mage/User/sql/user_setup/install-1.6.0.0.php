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
 * Create table 'user/entity'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user/entity'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Entity Id')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Type Id')
    ->addColumn('attribute_set_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Attribute Set Id')
    ->addColumn('website_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        ), 'Website Id')
    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Email')
    ->addColumn('group_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Group Id')
    ->addColumn('increment_id', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
        ), 'Increment Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'default'   => '0',
        ), 'Store Id')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Updated At')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
        ), 'Is Active')
    ->addIndex($installer->getIdxName('user/entity', array('store_id')),
        array('store_id'))
    ->addIndex($installer->getIdxName('user/entity', array('entity_type_id')),
        array('entity_type_id'))
    ->addIndex($installer->getIdxName('user/entity', array('email', 'website_id')),
        array('email', 'website_id'))
    ->addIndex($installer->getIdxName('user/entity', array('website_id')),
        array('website_id'))
    ->addForeignKey($installer->getFkName('user/entity', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user/entity', 'website_id', 'core/website', 'website_id'),
        'website_id', $installer->getTable('core/website'), 'website_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('User Entity');
$installer->getConnection()->createTable($table);

/**
 * Create table 'user/address_entity'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user/address_entity'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Entity Id')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Type Id')
    ->addColumn('attribute_set_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Attribute Set Id')
    ->addColumn('increment_id', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
        ), 'Increment Id')
    ->addColumn('parent_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => true,
        ), 'Parent Id')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Updated At')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
        ), 'Is Active')
    ->addIndex($installer->getIdxName('user/address_entity', array('parent_id')),
        array('parent_id'))
    ->addForeignKey($installer->getFkName('user/address_entity', 'parent_id', 'user/entity', 'entity_id'),
        'parent_id', $installer->getTable('user/entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('User Address Entity');
$installer->getConnection()->createTable($table);

/**
 * Create table 'user_address_entity_datetime'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user_address_entity_datetime'))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Value Id')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Type Id')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Attribute Id')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Id')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Value')
    ->addIndex($installer->getIdxName('user_address_entity_datetime', array('entity_id', 'attribute_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('entity_id', 'attribute_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName('user_address_entity_datetime', array('entity_type_id')),
        array('entity_type_id'))
    ->addIndex($installer->getIdxName('user_address_entity_datetime', array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName('user_address_entity_datetime', array('entity_id')),
        array('entity_id'))
    ->addIndex($installer->getIdxName('user_address_entity_datetime', array('entity_id', 'attribute_id', 'value')),
        array('entity_id', 'attribute_id', 'value'))
    ->addForeignKey($installer->getFkName('user_address_entity_datetime', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_address_entity_datetime', 'entity_id', 'user/address_entity', 'entity_id'),
        'entity_id', $installer->getTable('user/address_entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_address_entity_datetime', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
        'entity_type_id', $installer->getTable('eav/entity_type'), 'entity_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('User Address Entity Datetime');
$installer->getConnection()->createTable($table);

/**
 * Create table 'user_address_entity_decimal'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user_address_entity_decimal'))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Value Id')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Type Id')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Attribute Id')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Id')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable'  => false,
        'default'   => '0.0000',
        ), 'Value')
    ->addIndex($installer->getIdxName('user_address_entity_decimal', array('entity_id', 'attribute_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('entity_id', 'attribute_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName('user_address_entity_decimal', array('entity_type_id')),
        array('entity_type_id'))
    ->addIndex($installer->getIdxName('user_address_entity_decimal', array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName('user_address_entity_decimal', array('entity_id')),
        array('entity_id'))
    ->addIndex($installer->getIdxName('user_address_entity_decimal', array('entity_id', 'attribute_id', 'value')),
        array('entity_id', 'attribute_id', 'value'))
    ->addForeignKey($installer->getFkName('user_address_entity_decimal', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_address_entity_decimal', 'entity_id', 'user/address_entity', 'entity_id'),
        'entity_id', $installer->getTable('user/address_entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_address_entity_decimal', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
        'entity_type_id', $installer->getTable('eav/entity_type'), 'entity_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('User Address Entity Decimal');
$installer->getConnection()->createTable($table);

/**
 * Create table 'user_address_entity_int'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user_address_entity_int'))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Value Id')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Type Id')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Attribute Id')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Id')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'default'   => '0',
        ), 'Value')
    ->addIndex($installer->getIdxName('user_address_entity_int', array('entity_id', 'attribute_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('entity_id', 'attribute_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName('user_address_entity_int', array('entity_type_id')),
        array('entity_type_id'))
    ->addIndex($installer->getIdxName('user_address_entity_int', array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName('user_address_entity_int', array('entity_id')),
        array('entity_id'))
    ->addIndex($installer->getIdxName('user_address_entity_int', array('entity_id', 'attribute_id', 'value')),
        array('entity_id', 'attribute_id', 'value'))
    ->addForeignKey($installer->getFkName('user_address_entity_int', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_address_entity_int', 'entity_id', 'user/address_entity', 'entity_id'),
        'entity_id', $installer->getTable('user/address_entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_address_entity_int', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
        'entity_type_id', $installer->getTable('eav/entity_type'), 'entity_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('User Address Entity Int');
$installer->getConnection()->createTable($table);

/**
 * Create table 'user_address_entity_text'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user_address_entity_text'))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Value Id')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Type Id')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Attribute Id')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Id')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        'nullable'  => false,
        ), 'Value')
    ->addIndex($installer->getIdxName('user_address_entity_text', array('entity_id', 'attribute_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('entity_id', 'attribute_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName('user_address_entity_text', array('entity_type_id')),
        array('entity_type_id'))
    ->addIndex($installer->getIdxName('user_address_entity_text', array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName('user_address_entity_text', array('entity_id')),
        array('entity_id'))
    ->addForeignKey($installer->getFkName('user_address_entity_text', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_address_entity_text', 'entity_id', 'user/address_entity', 'entity_id'),
        'entity_id', $installer->getTable('user/address_entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_address_entity_text', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
        'entity_type_id', $installer->getTable('eav/entity_type'), 'entity_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('User Address Entity Text');
$installer->getConnection()->createTable($table);

/**
 * Create table 'user_address_entity_varchar'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user_address_entity_varchar'))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Value Id')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Type Id')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Attribute Id')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Id')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Value')
    ->addIndex($installer->getIdxName('user_address_entity_varchar', array('entity_id', 'attribute_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('entity_id', 'attribute_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName('user_address_entity_varchar', array('entity_type_id')),
        array('entity_type_id'))
    ->addIndex($installer->getIdxName('user_address_entity_varchar', array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName('user_address_entity_varchar', array('entity_id')),
        array('entity_id'))
    ->addIndex($installer->getIdxName('user_address_entity_varchar', array('entity_id', 'attribute_id', 'value')),
        array('entity_id', 'attribute_id', 'value'))
    ->addForeignKey($installer->getFkName('user_address_entity_varchar', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_address_entity_varchar', 'entity_id', 'user/address_entity', 'entity_id'),
        'entity_id', $installer->getTable('user/address_entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_address_entity_varchar', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
        'entity_type_id', $installer->getTable('eav/entity_type'), 'entity_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('User Address Entity Varchar');
$installer->getConnection()->createTable($table);

/**
 * Create table 'user_entity_datetime'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user_entity_datetime'))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Value Id')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Type Id')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Attribute Id')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Id')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Value')
    ->addIndex($installer->getIdxName('user_entity_datetime', array('entity_id', 'attribute_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('entity_id', 'attribute_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName('user_entity_datetime', array('entity_type_id')),
        array('entity_type_id'))
    ->addIndex($installer->getIdxName('user_entity_datetime', array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName('user_entity_datetime', array('entity_id')),
        array('entity_id'))
    ->addIndex($installer->getIdxName('user_entity_datetime', array('entity_id', 'attribute_id', 'value')),
        array('entity_id', 'attribute_id', 'value'))
    ->addForeignKey($installer->getFkName('user_entity_datetime', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_entity_datetime', 'entity_id', 'user/entity', 'entity_id'),
        'entity_id', $installer->getTable('user/entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_entity_datetime', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
        'entity_type_id', $installer->getTable('eav/entity_type'), 'entity_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('User Entity Datetime');
$installer->getConnection()->createTable($table);

/**
 * Create table 'user_entity_decimal'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user_entity_decimal'))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Value Id')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Type Id')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Attribute Id')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Id')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable'  => false,
        'default'   => '0.0000',
        ), 'Value')
    ->addIndex($installer->getIdxName('user_entity_decimal', array('entity_id', 'attribute_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('entity_id', 'attribute_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName('user_entity_decimal', array('entity_type_id')),
        array('entity_type_id'))
    ->addIndex($installer->getIdxName('user_entity_decimal', array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName('user_entity_decimal', array('entity_id')),
        array('entity_id'))
    ->addIndex($installer->getIdxName('user_entity_decimal', array('entity_id', 'attribute_id', 'value')),
        array('entity_id', 'attribute_id', 'value'))
    ->addForeignKey($installer->getFkName('user_entity_decimal', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_entity_decimal', 'entity_id', 'user/entity', 'entity_id'),
        'entity_id', $installer->getTable('user/entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_entity_decimal', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
        'entity_type_id', $installer->getTable('eav/entity_type'), 'entity_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('User Entity Decimal');
$installer->getConnection()->createTable($table);

/**
 * Create table 'user_entity_int'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user_entity_int'))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Value Id')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Type Id')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Attribute Id')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Id')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'default'   => '0',
        ), 'Value')
    ->addIndex($installer->getIdxName('user_entity_int', array('entity_id', 'attribute_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('entity_id', 'attribute_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName('user_entity_int', array('entity_type_id')),
        array('entity_type_id'))
    ->addIndex($installer->getIdxName('user_entity_int', array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName('user_entity_int', array('entity_id')),
        array('entity_id'))
    ->addIndex($installer->getIdxName('user_entity_int', array('entity_id', 'attribute_id', 'value')),
        array('entity_id', 'attribute_id', 'value'))
    ->addForeignKey($installer->getFkName('user_entity_int', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_entity_int', 'entity_id', 'user/entity', 'entity_id'),
        'entity_id', $installer->getTable('user/entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_entity_int', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
        'entity_type_id', $installer->getTable('eav/entity_type'), 'entity_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('User Entity Int');
$installer->getConnection()->createTable($table);

/**
 * Create table 'user_entity_text'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user_entity_text'))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Value Id')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Type Id')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Attribute Id')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Id')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        'nullable'  => false,
        ), 'Value')
    ->addIndex($installer->getIdxName('user_entity_text', array('entity_id', 'attribute_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('entity_id', 'attribute_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName('user_entity_text', array('entity_type_id')),
        array('entity_type_id'))
    ->addIndex($installer->getIdxName('user_entity_text', array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName('user_entity_text', array('entity_id')),
        array('entity_id'))
    ->addForeignKey($installer->getFkName('user_entity_text', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_entity_text', 'entity_id', 'user/entity', 'entity_id'),
        'entity_id', $installer->getTable('user/entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_entity_text', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
        'entity_type_id', $installer->getTable('eav/entity_type'), 'entity_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('User Entity Text');
$installer->getConnection()->createTable($table);

/**
 * Create table 'user_entity_varchar'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user_entity_varchar'))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Value Id')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Type Id')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Attribute Id')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Id')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Value')
    ->addIndex($installer->getIdxName('user_entity_varchar', array('entity_id', 'attribute_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('entity_id', 'attribute_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName('user_entity_varchar', array('entity_type_id')),
        array('entity_type_id'))
    ->addIndex($installer->getIdxName('user_entity_varchar', array('attribute_id')),
        array('attribute_id'))
    ->addIndex($installer->getIdxName('user_entity_varchar', array('entity_id')),
        array('entity_id'))
    ->addIndex($installer->getIdxName('user_entity_varchar', array('entity_id', 'attribute_id', 'value')),
        array('entity_id', 'attribute_id', 'value'))
    ->addForeignKey($installer->getFkName('user_entity_varchar', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_entity_varchar', 'entity_id', 'user/entity', 'entity_id'),
        'entity_id', $installer->getTable('user/entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user_entity_varchar', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
        'entity_type_id', $installer->getTable('eav/entity_type'), 'entity_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('User Entity Varchar');
$installer->getConnection()->createTable($table);

/**
 * Create table 'user_group'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user/user_group'))
    ->addColumn('user_group_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'User Group Id')
    ->addColumn('user_group_code', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable'  => false,
        ), 'User Group Code')
    ->addColumn('tax_class_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Tax Class Id')
    ->setComment('User Group');
$installer->getConnection()->createTable($table);

/**
 * Create table 'user/eav_attribute'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user/eav_attribute'))
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity'  => false,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Attribute Id')
    ->addColumn('is_visible', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
        ), 'Is Visible')
    ->addColumn('input_filter', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Input Filter')
    ->addColumn('multiline_count', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
        ), 'Multiline Count')
    ->addColumn('validate_rules', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        ), 'Validate Rules')
    ->addColumn('is_system', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Is System')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Sort Order')
    ->addColumn('data_model', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Data Model')
    ->addForeignKey($installer->getFkName('user/eav_attribute', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('User Eav Attribute');
$installer->getConnection()->createTable($table);

/**
 * Create table 'user/form_attribute'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user/form_attribute'))
    ->addColumn('form_code', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable'  => false,
        'primary'   => true,
        ), 'Form Code')
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Attribute Id')
    ->addIndex($installer->getIdxName('user/form_attribute', array('attribute_id')),
        array('attribute_id'))
    ->addForeignKey($installer->getFkName('user/form_attribute', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('User Form Attribute');
$installer->getConnection()->createTable($table);

/**
 * Create table 'user/eav_attribute_website'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('user/eav_attribute_website'))
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Attribute Id')
    ->addColumn('website_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Website Id')
    ->addColumn('is_visible', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        ), 'Is Visible')
    ->addColumn('is_required', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        ), 'Is Required')
    ->addColumn('default_value', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        ), 'Default Value')
    ->addColumn('multiline_count', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        ), 'Multiline Count')
    ->addIndex($installer->getIdxName('user/eav_attribute_website', array('website_id')),
        array('website_id'))
    ->addForeignKey($installer->getFkName('user/eav_attribute_website', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('user/eav_attribute_website', 'website_id', 'core/website', 'website_id'),
        'website_id', $installer->getTable('core/website'), 'website_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('User Eav Attribute Website');
$installer->getConnection()->createTable($table);

$installer->endSetup();

// insert default user groups
$installer->getConnection()->insertForce($installer->getTable('user/user_group'), array(
    'user_group_id'     => 0,
    'user_group_code'   => 'NOT LOGGED IN',
    'tax_class_id'          => 3
));
$installer->getConnection()->insertForce($installer->getTable('user/user_group'), array(
    'user_group_id'     => 1,
    'user_group_code'   => 'General',
    'tax_class_id'          => 3
));
$installer->getConnection()->insertForce($installer->getTable('user/user_group'), array(
    'user_group_id'     => 2,
    'user_group_code'   => 'Wholesale',
    'tax_class_id'          => 3
));
$installer->getConnection()->insertForce($installer->getTable('user/user_group'), array(
    'user_group_id'     => 3,
    'user_group_code'   => 'Retailer',
    'tax_class_id'          => 3
));

$installer->installEntities();

$installer->installUserForms();

