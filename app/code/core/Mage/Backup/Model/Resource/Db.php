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
 * @package     Mage_Backup
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Database backup resource model
 *
 * @category    Mage
 * @package     Mage_Backup
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_Backup_Model_Resource_Db
{
    /**
     * Read connection
     *
     * @var Varien_Db_Adapter_Pdo_Mysql
     */
    protected $_read;

    /**
     * tables Foreign key data array
     * [tbl_name] = array(create foreign key strings)
     *
     * @var array
     */
    protected $_foreignKeys    = array();

    /**
     * Initialize Backup DB resource model
     *
     */
    public function __construct()
    {
        $this->_read = Mage::getSingleton('core/resource')->getConnection('backup_read');
    }

    /**
     * Enter description here ...
     *
     * @deprecated after 1.4.0.0-alpha2
     *
     */
    public function crear()
    {
        $this->clear();
    }

    /**
     * Clear data
     *
     */
    public function clear()
    {
        $this->_foreignKeys = array();
    }

    /**
     * Retrieve table list
     *
     * @return array
     */
    public function getTables()
    {
        return $this->_read->listTables();
    }

    /**
     * Retrieve SQL fragment for drop table
     *
     * @param string $tableName
     * @return string
     */
    public function getTableDropSql($tableName)
    {
        return Mage::getResourceHelper('backup')->getTableDropSql($tableName);
    }

    /**
     * Retrieve SQL fragment for create table
     *
     * @param string $tableName
     * @param bool $withForeignKeys
     * @return string
     */
    public function getTableCreateSql($tableName, $withForeignKeys = false)
    {
        return Mage::getResourceHelper('backup')->getTableCreateSql($tableName, $withForeignKeys = false);
    }

    /**
     * Retrieve foreign keys for table(s)
     *
     * @param string|null $tableName
     * @return string
     */
    public function getTableForeignKeysSql($tableName = null)
    {
        $fkScript = '';
        if (!$tableName) {
            $tables = $this->getTables();
            foreach($tables as $table) {
                $fkScript = $fkScript . Mage::getResourceHelper('backup')->getTableForeignKeysSql($table);
            }
        } else {
            $fkScript = $this->getTableForeignKeysSql($tableName);
        }
        return $fkScript;
    }

    /**
     * Retrieve table status
     *
     * @param string $tableName
     * @return Varien_Object
     */
    public function getTableStatus($tableName)
    {
        $row = $this->_read->showTableStatus($tableName);

        if ($row) {
            $statusObject = new Varien_Object();
            $statusObject->setIdFieldName('name');
            foreach ($row as $field => $value) {
                $statusObject->setData(strtolower($field), $value);
            }

            $cntRow = $this->_read->fetchRow(
                    $this->_read->select()->from($tableName, 'COUNT(1) as rows'));
            $statusObject->setRows($cntRow['rows']);

            return $statusObject;
        }

        return false;
    }

    /**
     * Quote Table Row
     *
     * @deprecated 
     *
     * @param string $tableName
     * @param array $row
     * @return string
     */
    protected function _quoteRow($tableName, array $row)
    {
        return $row;    
    }

    /**
     * Retrive table partical data SQL insert
     *
     * @param string $tableName
     * @param int $count
     * @param int $offset
     * @return string
     */
    public function getTableDataSql($tableName, $count = null, $offset = null)
    {
        return Mage::getResourceHelper('backup')->getInsertSql($tableName);

    }

    /**
     * Enter description here...
     *
     * @param unknown_type $tableName
     * @param unknown_type $addDropIfExists
     * @return unknown
     */
    public function getTableCreateScript($tableName, $addDropIfExists = false)
    {
        return Mage::getResourceHelper('backup')->getTableCreateScript($tableName, $addDropIfExists);;
    }

    /**
     * Retrieve table header comment
     *
     * @param unknown_type $tableName
     * @return string
     */
    public function getTableHeader($tableName)
    {
        $quotedTableName = $this->_read->quoteIdentifier($tableName);
        return "\n--\n"
            . "-- Table structure for table {$quotedTableName}\n"
            . "--\n\n";
    }

    /**
     * Return table data dump
     *
     * @param string $tableName
     * @param bool $step
     * @return string
     */
    public function getTableDataDump($tableName, $step = false)
    {
        return $this->getTableDataSql($tableName);
    }

    /**
     * Returns SQL header data
     *
     * @return string
     */
    public function getHeader()
    {
        return Mage::getResourceHelper('backup')->getHeader();
    }

    /**
     * Returns SQL footer data
     *
     * @return string
     */
    public function getFooter()
    {
        return Mage::getResourceHelper('backup')->getFooter();        
    }

    /**
     * Retrieve before insert data SQL fragment
     *
     * @param string $tableName
     * @return string
     */
    public function getTableDataBeforeSql($tableName)
    {
        return Mage::getResourceHelper('backup')->getTableDataBeforeSql($tableName);
    }

    /**
     * Retrieve after insert data SQL fragment
     *
     * @param string $tableName
     * @return string
     */
    public function getTableDataAfterSql($tableName)
    {
        return Mage::getResourceHelper('backup')->getTableDataAfterSql($tableName);
    }

    /**
     * Start transaction mode
     *
     * @return Mage_Backup_Model_Resource_Db
     */
    public function beginTransaction()
    {
        Mage::getResourceHelper('backup')->turnOnSerializableMode();
        $this->_read->beginTransaction();
        return $this;
    }

    /**
     * Commit transaction
     *
     * @return Mage_Backup_Model_Resource_Db
     */
    public function commitTransaction()
    {
        $this->_read->commit();
        Mage::getResourceHelper('backup')->turnOnReadCommittedMode();
        return $this;
    }

    /**
     * Rollback transaction
     *
     * @return Mage_Backup_Model_Resource_Db
     */
    public function rollBackTransaction()
    {
        $this->_read->rollBack();
        return $this;
    }
}
