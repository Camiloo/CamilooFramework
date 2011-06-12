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
 * @package     Mage_Newsletter
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Newsletter problems collection
 *
 * @category    Mage
 * @package     Mage_Newsletter
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_Newsletter_Model_Resource_Problem_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * True when subscribers info joined
     *
     * @var bool
     */
    protected $_subscribersInfoJoinedFlag  = false;

    /**
     * True when grouped
     *
     * @var bool
     */
    protected $_problemGrouped             = false;

    /**
     * Define resource model and model
     *
     */
    protected function _construct()
    {
        $this->_init('newsletter/problem');
    }

    /**
     * Adds subscribers info
     *
     * @return Mage_Newsletter_Model_Resource_Problem_Collection
     */
    public function addSubscriberInfo()
    {
        $this->getSelect()->joinLeft(array('subscriber'=>$this->getTable('newsletter/subscriber')),
            'main_table.subscriber_id = subscriber.subscriber_id',
            array('subscriber_email','user_id','subscriber_status')
        );
        $this->_subscribersInfoJoinedFlag = true;

        return $this;
    }

    /**
     * Adds queue info
     *
     * @return Mage_Newsletter_Model_Resource_Problem_Collection
     */
    public function addQueueInfo()
    {
        $this->getSelect()->joinLeft(array('queue'=>$this->getTable('newsletter/queue')),
            'main_table.queue_id = queue.queue_id',
            array('queue_start_at', 'queue_finish_at')
        )
        ->joinLeft(array('template'=>$this->getTable('newsletter/template')), 'main_table.queue_id = queue.queue_id',
            array('template_subject','template_code','template_sender_name','template_sender_email')
        );
        return $this;
    }

    /**
     * Loads users info to collection
     *
     */
    protected function _addUsersData()
    {
        $usersIds = array();

        foreach ($this->getItems() as $item) {
            if ($item->getUserId()) {
                $usersIds[] = $item->getUserId();
            }
        }

        if (count($usersIds) == 0) {
            return;
        }

        $users = Mage::getResourceModel('user/user_collection')
            ->addNameToSelect()
            ->addAttributeToFilter('entity_id', array("in"=>$usersIds));

        $users->load();

        foreach ($users->getItems() as $user) {
            $problems = $this->getItemsByColumnValue('user_id', $user->getId());
            foreach ($problems as $problem) {
                $problem->setUserName($user->getName())
                    ->setUserFirstName($user->getFirstName())
                    ->setUserLastName($user->getLastName());
            }
        }
    }

    /**
     * Loads collecion and adds users info
     *
     * @param bool $printQuery
     * @param bool $logQuery
     * @return Mage_Newsletter_Model_Resource_Problem_Collection
     */
    public function load($printQuery = false, $logQuery = false)
    {
        parent::load($printQuery, $logQuery);
        if ($this->_subscribersInfoJoinedFlag && !$this->isLoaded()) {
            $this->_addUsersData();
        }
        return $this;
    }
}
