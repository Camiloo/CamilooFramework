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
 * Users collection
 *
 * @category    Mage
 * @package     Mage_User
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Model_Resource_User_Collection extends Mage_Eav_Model_Entity_Collection_Abstract
{
    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('user/user');
    }

    /**
     * Group result by user email
     *
     * @return Mage_User_Model_Resource_User_Collection
     */
    public function groupByEmail()
    {
        $this->getSelect()
            ->from(
                array('email' => $this->getEntity()->getEntityTable()),
                array('email_count' => new Zend_Db_Expr('COUNT(email.entity_id)'))
            )
            ->where('email.entity_id = e.entity_id')
            ->group('email.email');

        return $this;
    }

    /**
     * Add Name to select
     *
     * @return Mage_User_Model_Resource_User_Collection
     */
    public function addNameToSelect()
    {
        $fields = array();
        $userAccount = Mage::getConfig()->getFieldset('user_account');
        foreach ($userAccount as $code => $node) {
            if ($node->is('name')) {
                $fields[$code] = $code;
            }
        }

        $adapter = $this->getConnection();
        $concatenate = array();
        if (isset($fields['prefix'])) {
            $concatenate[] = $adapter->getCheckSql(
                '{{prefix}} IS NOT NULL AND {{prefix}} != \'\'',
                'LTRIM(RTRIM({{prefix}}))',
                '\'\'');
        }
        $concatenate[] = 'LTRIM(RTRIM({{firstname}}))';
        if (isset($fields['middlename'])) {
            $concatenate[] = $adapter->getCheckSql(
                '{{middlename}} IS NOT NULL AND {{middlename}} != \'\'',
                'LTRIM(RTRIM({{middlename}}))',
                '\'\'');
        }
        $concatenate[] = 'LTRIM(RTRIM({{lastname}}))';
        if (isset($fields['suffix'])) {
            $concatenate[] = $adapter
                ->getCheckSql('{{suffix}} IS NOT NULL AND {{suffix}} != \'\'', "LTRIM(RTRIM({{suffix}}))", "''");
        }

        $nameExpr = $adapter->getConcatSql($concatenate, ' ');

        $this->addExpressionAttributeToSelect('name', $nameExpr, $fields);
        
        return $this;
    }

    /**
     * Get SQL for get record count
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $select = parent::getSelectCountSql();
        $select->resetJoinLeft();

        return $select;
    }

    /**
     * Reset left join
     *
     * @param int $limit
     * @param int $offset
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getAllIdsSelect($limit = null, $offset = null)
    {
        $idsSelect = parent::_getAllIdsSelect($limit, $offset);
        $idsSelect->resetJoinLeft();
        return $idsSelect;
    }
}
