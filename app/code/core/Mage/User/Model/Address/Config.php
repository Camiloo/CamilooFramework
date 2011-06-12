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
 * User address config
 *
 * @category   Mage
 * @package    Mage_User
 * @author     Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Model_Address_Config extends Mage_Core_Model_Config_Base
{
    const DEFAULT_ADDRESS_RENDERER  = 'user/address_renderer_default';
    const XML_PATH_ADDRESS_TEMPLATE = 'user/address_templates/';
    const DEFAULT_ADDRESS_FORMAT    = 'oneline';

    /**
     * User Address Templates per store
     *
     * @var array
     */
    protected $_types           = array();

    /**
     * Current store instance
     *
     * @var Mage_Core_Model_Store
     */
    protected $_store           = null;

    /**
     * Default types per store
     * Using for invalid code
     *
     * @var array
     */
    protected $_defaultTypes    = array();

    public function setStore($store)
    {
        $this->_store = Mage::app()->getStore($store);
        return $this;
    }

    /**
     * Retrieve store
     *
     * @return Mage_Core_Model_Store
     */
    public function getStore()
    {
        if (is_null($this->_store)) {
            $this->_store = Mage::app()->getStore();
        }
        return $this->_store;
    }

    /**
     * Define node
     *
     */
    public function __construct()
    {
        parent::__construct(Mage::getConfig()->getNode()->global->user->address);
    }

    /**
     * Retrieve address formats
     *
     * @return array
     */
    public function getFormats()
    {
        $store = $this->getStore();
        $storeId = $store->getId();
        if (!isset($this->_types[$storeId])) {
            $this->_types[$storeId] = array();
            foreach ($this->getNode('formats')->children() as $typeCode => $typeConfig) {
                $path = sprintf('%s%s', self::XML_PATH_ADDRESS_TEMPLATE, $typeCode);
                $type = new Varien_Object();
                $type->setCode($typeCode)
                    ->setTitle((string)$typeConfig->title)
                    ->setDefaultFormat(Mage::getStoreConfig($path, $store))
                    ->setHtmlEscape((bool)$typeConfig->htmlEscape);

                $renderer = (string)$typeConfig->renderer;
                if (!$renderer) {
                    $renderer = self::DEFAULT_ADDRESS_RENDERER;
                }

                $type->setRenderer(
                    Mage::helper('user/address')->getRenderer($renderer)->setType($type)
                );

                $this->_types[$storeId][] = $type;
            }
        }

        return $this->_types[$storeId];
    }

    /**
     * Retrieve default address format
     *
     * @return Varien_Object
     */
    protected function _getDefaultFormat()
    {
        $store = $this->getStore();
        $storeId = $store->getId();
        if(!isset($this->_defaultType[$storeId])) {
            $this->_defaultType[$storeId] = new Varien_Object();
            $this->_defaultType[$storeId]->setCode('default')
                ->setDefaultFormat('{{depend prefix}}{{var prefix}} {{/depend}}{{var firstname}} {{depend middlename}}{{var middlename}} {{/depend}}{{var lastname}}{{depend suffix}} {{var suffix}}{{/depend}}, {{var street}}, {{var city}}, {{var region}} {{var postcode}}, {{var country}}');

            $this->_defaultType[$storeId]->setRenderer(
                Mage::helper('user/address')
                    ->getRenderer(self::DEFAULT_ADDRESS_RENDERER)->setType($this->_defaultType[$storeId])
            );
        }
        return $this->_defaultType[$storeId];
    }

    /**
     * Retrieve address format by code
     *
     * @param string $typeCode
     * @return Varien_Object
     */
    public function getFormatByCode($typeCode)
    {
        foreach($this->getFormats() as $type) {
            if($type->getCode()==$typeCode) {
                return $type;
            }
        }
        return $this->_getDefaultFormat();
    }

}
