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
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml user grid block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author     Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_User_Online_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Initialize Grid block
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('onlineGrid');
        $this->setSaveParametersInSession(true);
        $this->setDefaultSort('last_activity');
        $this->setDefaultDir('DESC');
    }

    /**
     * Prepare collection for grid
     *
     * @return Mage_Adminhtml_Block_User_Online_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('log/visitor_online')
            ->prepare()
            ->getCollection();
        /* @var $collection Mage_Log_Model_Mysql4_Visitor_Online_Collection */
        $collection->addUserData();

        $this->setCollection($collection);
        parent::_prepareCollection();

        return $this;
    }

    /**
     * Prepare columns
     *
     * @return Mage_Adminhtml_Block_User_Online_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('user_id', array(
            'header'    => Mage::helper('user')->__('ID'),
            'width'     => '40px',
            'align'     => 'right',
            'type'      => 'number',
            'default'   => Mage::helper('user')->__('n/a'),
            'index'     => 'user_id'
        ));

        $this->addColumn('firstname', array(
            'header'    => Mage::helper('user')->__('First Name'),
            'default'   => Mage::helper('user')->__('Guest'),
            'index'     => 'user_firstname'
        ));

        $this->addColumn('lastname', array(
            'header'    => Mage::helper('user')->__('Last Name'),
            'default'   => Mage::helper('user')->__('n/a'),
            'index'     => 'user_lastname'
        ));

        $this->addColumn('email', array(
            'header'    => Mage::helper('user')->__('Email'),
            'default'   => Mage::helper('user')->__('n/a'),
            'index'     => 'user_email'
        ));

        $this->addColumn('ip_address', array(
            'header'    => Mage::helper('user')->__('IP Address'),
            'default'   => Mage::helper('user')->__('n/a'),
            'index'     => 'remote_addr',
            'renderer'  => 'adminhtml/user_online_grid_renderer_ip',
            'filter'    => false,
            'sort'      => false
        ));

        $this->addColumn('session_start_time', array(
            'header'    => Mage::helper('user')->__('Session Start Time'),
            'align'     => 'left',
            'width'     => '200px',
            'type'      => 'datetime',
            'default'   => Mage::helper('user')->__('n/a'),
            'index'     =>'first_visit_at'
        ));

        $this->addColumn('last_activity', array(
            'header'    => Mage::helper('user')->__('Last Activity'),
            'align'     => 'left',
            'width'     => '200px',
            'type'      => 'datetime',
            'default'   => Mage::helper('user')->__('n/a'),
            'index'     => 'last_visit_at'
        ));

        $typeOptions = array(
            Mage_Log_Model_Visitor::VISITOR_TYPE_CUSTOMER => Mage::helper('user')->__('User'),
            Mage_Log_Model_Visitor::VISITOR_TYPE_VISITOR  => Mage::helper('user')->__('Visitor'),
        );

        $this->addColumn('type', array(
            'header'    => Mage::helper('user')->__('Type'),
            'index'     => 'type',
            'type'      => 'options',
            'options'   => $typeOptions,
//            'renderer'  => 'adminhtml/user_online_grid_renderer_type',
            'index'     => 'visitor_type'
        ));

        $this->addColumn('last_url', array(
            'header'    => Mage::helper('user')->__('Last URL'),
            'type'      => 'wrapline',
            'lineLength' => '60',
            'default'   => Mage::helper('user')->__('n/a'),
            'renderer'  => 'adminhtml/user_online_grid_renderer_url',
            'index'     => 'last_url'
        ));

        return parent::_prepareColumns();
    }

    /**
     * Retrieve Row URL
     *
     * @param Mage_Core_Model_Abstract
     * @return string
     */
    public function getRowUrl($row)
    {
        return (Mage::getSingleton('admin/session')->isAllowed('user/manage') && $row->getUserId())
            ? $this->getUrl('*/user/edit', array('id' => $row->getUserId())) : '';
    }
}
