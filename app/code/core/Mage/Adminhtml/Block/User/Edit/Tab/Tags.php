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
 * Adminhtml user orders grid block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_User_Edit_Tab_Tags extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('ordersGrid');
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('user/user_collection')
            ->addNameToSelect()
            ->addAttributeToSelect('email')
            ->addAttributeToSelect('created_at')
            ->joinAttribute('billing_postcode', 'user_address/postcode', 'default_billing')
            ->joinAttribute('billing_city', 'user_address/city', 'default_billing')
            ->joinAttribute('billing_telephone', 'user_address/telephone', 'default_billing')
            ->joinAttribute('billing_country_id', 'user_address/country_id', 'default_billing');

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('user')->__('ID'),
            'width'     =>5,
            'align'     =>'center',
            'sortable'  =>true,
            'index'     =>'entity_id'
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('user')->__('Name'),
            'index'     =>'name'
        ));
        $this->addColumn('email', array(
            'header'    => Mage::helper('user')->__('Email'),
            'width'     =>40,
            'align'     =>'center',
            'index'     =>'email'
        ));
        $this->addColumn('telephone', array(
            'header'    => Mage::helper('user')->__('Telephone'),
            'align'     =>'center',
            'index'     =>'billing_telephone'
        ));
        $this->addColumn('billing_postcode', array(
            'header'    => Mage::helper('user')->__('ZIP/Post Code'),
            'index'     =>'billing_postcode',
        ));
        $this->addColumn('billing_country_id', array(
            'header'    => Mage::helper('user')->__('Country'),
            'type'      => 'country',
            'index'     => 'billing_country_id',
        ));
        $this->addColumn('user_since', array(
            'header'    => Mage::helper('user')->__('User Since'),
            'type'      => 'date',
            'format'    => 'Y.m.d',
            'index'     =>'created_at',
        ));
        $this->addColumn('action', array(
            'header'    => Mage::helper('user')->__('Action'),
            'align'     =>'center',
            'format'    =>'<a href="'.$this->getUrl('*/sales/edit/id/$entity_id').'">'.Mage::helper('user')->__('Edit').'</a>',
            'filter'    =>false,
            'sortable'  =>false,
            'is_system' =>true
        ));

        $this->setColumnFilter('entity_id')
            ->setColumnFilter('email')
            ->setColumnFilter('name');

        $this->addExportType('*/*/exportCsv', Mage::helper('user')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('user')->__('Excel XML'));
        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/index', array('_current'=>true));
    }

}
