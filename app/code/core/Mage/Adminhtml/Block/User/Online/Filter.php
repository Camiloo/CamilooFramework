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
 * Adminhtml users online filter
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */

class Mage_Adminhtml_Block_User_Online_Filter extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $form->addField('filter_value', 'select',
                array(
                    'name' => 'filter_value',
                    'onchange' => 'this.form.submit()',
                    'values' => array(
                        array(
                            'label' => Mage::helper('user')->__('All'),
                            'value' => '',
                        ),

                        array(
                            'label' => Mage::helper('user')->__('Users Only'),
                            'value' => 'filterUsers',
                        ),

                        array(
                            'label' => Mage::helper('user')->__('Visitors Only'),
                            'value' => 'filterGuests',
                        )
                    ),
                    'no_span' => true
                )
        );

        $form->setUseContainer(true);
        $form->setId('filter_form');
        $form->setMethod('post');

        $this->setForm($form);
        return parent::_prepareForm();
    }
}
