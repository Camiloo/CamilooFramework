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
 * User groups api
 *
 * @category   Mage
 * @package    Mage_User
 * @author     Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Model_Group_Api extends Mage_Api_Model_Resource_Abstract
{
    /**
     * Retrieve groups
     *
     * @return array
     */
    public function items()
    {
        $collection = Mage::getModel('user/group')->getCollection();

        $result = array();
        foreach ($collection as $group) {
            /* @var $group Mage_User_Model_Group */
            $result[] = $group->toArray(array('user_group_id', 'user_group_code'));
        }

        return $result;
    }
}
