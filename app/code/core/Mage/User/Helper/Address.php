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
 * User address helper
 *
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Helper_Address extends Mage_Core_Helper_Abstract
{
    /**
     * Array of User Address Attributes
     *
     * @var array
     */
    protected $_attributes;

    /**
     * User address config node per website
     *
     * @var array
     */
    protected $_config          = array();

    /**
     * User Number of Lines in a Street Address per website
     *
     * @var array
     */
    protected $_streetLines     = array();
    protected $_formatTemplate  = array();

    /**
     * Addresses url
     */
    public function getBookUrl()
    {

    }

    public function getEditUrl()
    {

    }

    public function getDeleteUrl()
    {

    }

    public function getCreateUrl()
    {

    }

    public function getRenderer($renderer)
    {
        if(is_string($renderer) && $className = Mage::getConfig()->getBlockClassName($renderer)) {
            return new $className();
        } else {
            return $renderer;
        }
    }

    /**
     * Return user address config value by key and store
     *
     * @param string $key
     * @param Mage_Core_Model_Store|int|string $store
     * @return string|null
     */
    public function getConfig($key, $store = null)
    {
        $websiteId = Mage::app()->getStore($store)->getWebsiteId();

        if (!isset($this->_config[$websiteId])) {
            $this->_config[$websiteId] = Mage::getStoreConfig('user/address', $store);
        }
        return isset($this->_config[$websiteId][$key]) ? (string)$this->_config[$websiteId][$key] : null;
    }

    /**
     * Return Number of Lines in a Street Address for store
     *
     * @param Mage_Core_Model_Store|int|string $store
     * @return int
     */
    public function getStreetLines($store = null)
    {
        $websiteId = Mage::app()->getStore($store)->getWebsiteId();
        if (!isset($this->_streetLines[$websiteId])) {
            $attribute = Mage::getSingleton('eav/config')->getAttribute('user_address', 'street');
            $lines = $attribute->getMultilineCount();
            $this->_streetLines[$websiteId] = min(4, max(1, (int)$lines));
        }

        return $this->_streetLines[$websiteId];
    }

    public function getFormat($code)
    {
        $format = Mage::getSingleton('user/address_config')->getFormatByCode($code);
        return $format->getRenderer() ? $format->getRenderer()->getFormat() : '';
    }

    /**
     * Determine if specified address config value can show
     *
     * @return bool
     */
    public function canShowConfig($key)
    {
        $value = $this->getConfig($key);
        if (empty($value)) {
            return false;
        }
        return true;
    }

    /**
     * Return array of User Address Attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        if (is_null($this->_attributes)) {
            $this->_attributes = array();
            /* @var $config Mage_Eav_Model_Config */
            $config = Mage::getSingleton('eav/config');
            foreach ($config->getEntityAttributeCodes('user_address') as $attributeCode) {
                $this->_attributes[$attributeCode] = $config->getAttribute('user_address', $attributeCode);
            }
        }
        return $this->_attributes;
    }

    /**
     * Convert streets array to new street lines count
     * Examples of use:
     *  $origStreets = array('street1', 'street2', 'street3', 'street4')
     *  $toCount = 3
     *  Result:
     *   array('street1 street2', 'street3', 'street4')
     *  $toCount = 2
     *  Result:
     *   array('street1 street2', 'street3 street4')
     *
     * @param array $origStreets
     * @param int   $toCount
     * @return array
     */
    public function convertStreetLines($origStreets, $toCount)
    {
        $lines = array();
        if (!empty($origStreets) && $toCount > 0) {
            $countArgs = (int)floor(count($origStreets)/$toCount);
            $modulo = count($origStreets) % $toCount;
            $offset = 0;
            $neededLinesCount = 0;
            for ($i = 0; $i < $toCount; $i++) {
                $offset += $neededLinesCount;
                $neededLinesCount = $countArgs;
                if ($modulo > 0) {
                    ++$neededLinesCount;
                    --$modulo;
                }
                $values = array_slice($origStreets, $offset, $neededLinesCount);
                if (is_array($values)) {
                    $lines[] = implode(' ', $values);
                }
            }
        }

        return $lines;
    }
}
