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


class Mage_User_Model_Convert_Parser_User
    extends Mage_Eav_Model_Convert_Parser_Abstract
{
    const MULTI_DELIMITER = ' , ';

    protected $_resource;

    /**
     * Product collections per store
     *
     * @var array
     */
    protected $_collections;

    protected $_userModel;
    protected $_userAddressModel;
    protected $_newsletterModel;
    protected $_store;
    protected $_storeId;

    protected $_stores;

    /**
     * Website collection array
     *
     * @var array
     */
    protected $_websites;
    protected $_attributes = array();

    protected $_fields;

    /**
     * Array to contain user groups
     * @var null|array
     */
    protected $_userGroups = null;

    public function getFields()
    {
        if (!$this->_fields) {
            $this->_fields = Mage::getConfig()->getFieldset('user_dataflow', 'admin');
        }
        return $this->_fields;
    }

    /**
     * Retrieve user model cache
     *
     * @return Mage_User_Model_User
     */
    public function getUserModel()
    {
        if (is_null($this->_userModel)) {
            $object = Mage::getModel('user/user');
            $this->_userModel = Mage::objects()->save($object);
        }
        return Mage::objects()->load($this->_userModel);
    }

    /**
     * Retrieve user address model cache
     *
     * @return Mage_User_Model_Address
     */
    public function getUserAddressModel()
    {
        if (is_null($this->_userAddressModel)) {
            $object = Mage::getModel('user/address');
            $this->_userAddressModel = Mage::objects()->save($object);
        }
        return Mage::objects()->load($this->_userAddressModel);
    }

    /**
     * Retrieve newsletter subscribers model cache
     *
     * @return Mage_Newsletter_Model_Subscriber
     */
    public function getNewsletterModel()
    {
        if (is_null($this->_newsletterModel)) {
            $object = Mage::getModel('newsletter/subscriber');
            $this->_newsletterModel = Mage::objects()->save($object);
        }
        return Mage::objects()->load($this->_newsletterModel);
    }

    /**
     * Retrieve current store model
     *
     * @return Mage_Core_Model_Store
     */
    public function getStore()
    {
        if (is_null($this->_store)) {
            try {
                $store = Mage::app()->getStore($this->getVar('store'));
            }
            catch (Exception $e) {
                $this->addException(Mage::helper('adminhtml')->__('An invalid store was specified.'), Varien_Convert_Exception::FATAL);
                throw $e;
            }
            $this->_store = $store;
        }
        return $this->_store;
    }

    /**
     * Retrieve store ID
     *
     * @return int
     */
    public function getStoreId()
    {
        if (is_null($this->_storeId)) {
            $this->_storeId = $this->getStore()->getId();
        }
        return $this->_storeId;
    }

    public function getStoreById($storeId)
    {
        if (is_null($this->_stores)) {
            $this->_stores = Mage::app()->getStores(true);
        }
        if (isset($this->_stores[$storeId])) {
            return $this->_stores[$storeId];
        }
        return false;
    }

    /**
     * Retrieve website model by id
     *
     * @param int $websiteId
     * @return Mage_Core_Model_Website
     */
    public function getWebsiteById($websiteId)
    {
        if (is_null($this->_websites)) {
            $this->_websites = Mage::app()->getWebsites(true);
        }
        if (isset($this->_websites[$websiteId])) {
            return $this->_websites[$websiteId];
        }
        return false;
    }

    /**
     * Retrieve eav entity attribute model
     *
     * @param string $code
     * @return Mage_Eav_Model_Entity_Attribute
     */
    public function getAttribute($code)
    {
        if (!isset($this->_attributes[$code])) {
            $this->_attributes[$code] = $this->getUserModel()->getResource()->getAttribute($code);
        }
        return $this->_attributes[$code];
    }

    /**
     * @return Mage_Catalog_Model_Mysql4_Convert
     */
    public function getResource()
    {
        if (!$this->_resource) {
            $this->_resource = Mage::getResourceSingleton('catalog_entity/convert');
                #->loadStores()
                #->loadProducts()
                #->loadAttributeSets()
                #->loadAttributeOptions();
        }
        return $this->_resource;
    }

    public function getCollection($storeId)
    {
        if (!isset($this->_collections[$storeId])) {
            $this->_collections[$storeId] = Mage::getResourceModel('user/user_collection');
            $this->_collections[$storeId]->getEntity()->setStore($storeId);
        }
        return $this->_collections[$storeId];
    }

    public function unparse()
    {
        $systemFields = array();
        foreach ($this->getFields() as $code=>$node) {
            if ($node->is('system')) {
                $systemFields[] = $code;
            }
        }

        $entityIds = $this->getData();

        foreach ($entityIds as $i => $entityId) {
            $user = $this->getUserModel()
                ->setData(array())
                ->load($entityId);
            /* @var $user Mage_User_Model_User */

            $position = Mage::helper('adminhtml')->__('Line %d, Email: %s', ($i+1), $user->getEmail());
            $this->setPosition($position);

            $row = array();

            foreach ($user->getData() as $field => $value) {
                if ($field == 'website_id') {
                    $website = $this->getWebsiteById($value);
                    if ($website === false) {
                        $website = $this->getWebsiteById(0);
                    }
                    $row['website'] = $website->getCode();
                    continue;
                }

                if (in_array($field, $systemFields) || is_object($value)) {
                    continue;
                }

                $attribute = $this->getAttribute($field);
                if (!$attribute) {
                    continue;
                }

                if ($attribute->usesSource()) {

                    $option = $attribute->getSource()->getOptionText($value);
                    if ($value && empty($option)) {
                        $message = Mage::helper('adminhtml')->__("An invalid option ID is specified for %s (%s), skipping the record.", $field, $value);
                        $this->addException($message, Mage_Dataflow_Model_Convert_Exception::ERROR);
                        continue;
                    }
                    if (is_array($option)) {
                        $value = join(self::MULTI_DELIMITER, $option);
                    } else {
                        $value = $option;
                    }
                    unset($option);
                }
                elseif (is_array($value)) {
                    continue;
                }
                $row[$field] = $value;
            }

            $defaultBillingId  = $user->getDefaultBilling();
            $defaultShippingId = $user->getDefaultShipping();

            $userAddress = $this->getUserAddressModel();

            if (!$defaultBillingId) {
                foreach ($this->getFields() as $code=>$node) {
                    if ($node->is('billing')) {
                        $row['billing_'.$code] = null;
                    }
                }
            }
            else {
                $userAddress->load($defaultBillingId);

                foreach ($this->getFields() as $code=>$node) {
                    if ($node->is('billing')) {
                        $row['billing_'.$code] = $userAddress->getDataUsingMethod($code);
                    }
                }
            }

            if (!$defaultShippingId) {
                foreach ($this->getFields() as $code=>$node) {
                    if ($node->is('shipping')) {
                        $row['shipping_'.$code] = null;
                    }
                }
            }
            else {
                if ($defaultShippingId != $defaultBillingId) {
                    $userAddress->load($defaultShippingId);
                }
                foreach ($this->getFields() as $code=>$node) {
                    if ($node->is('shipping')) {
                        $row['shipping_'.$code] = $userAddress->getDataUsingMethod($code);
                    }
                }
            }

            $store = $this->getStoreById($user->getStoreId());
            if ($store === false) {
                $store = $this->getStoreById(0);
            }
            $row['created_in'] = $store->getCode();

            $newsletter = $this->getNewsletterModel()
                ->setData(array())
                ->loadByUser($user);
            $row['is_subscribed'] = ($newsletter->getId()
                && $newsletter->getSubscriberStatus() == Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED)
                ? 1 : 0;

            if($user->getGroupId()){
                $groupCode = $this->_getUserGroupCode($user);
                if (is_null($groupCode)) {
                    $this->addException(
                        Mage::helper('adminhtml')->__("An invalid group ID is specified, skipping the record."),
                        Mage_Dataflow_Model_Convert_Exception::ERROR
                    );
                    continue;
                } else {
                    $row['group'] = $groupCode;
                }
            }

            $batchExport = $this->getBatchExportModel()
                ->setId(null)
                ->setBatchId($this->getBatchModel()->getId())
                ->setBatchData($row)
                ->setStatus(1)
                ->save();
        }

        return $this;
    }

    public function getExternalAttributes()
    {
        $internal = array(
            'store_id',
            'entity_id',
            'website_id',
            'group_id',
            'created_in',
            'default_billing',
            'default_shipping',
            'country_id'
        );

        $userAttributes = Mage::getResourceModel('user/attribute_collection')
            ->load()->getIterator();

        $addressAttributes = Mage::getResourceModel('user/address_attribute_collection')
            ->load()->getIterator();

        $attributes = array(
            'website'       => 'website',
            'email'         => 'email',
            'group'         => 'group',
            'create_in'     => 'create_in',
            'is_subscribed' => 'is_subscribed'
        );

        foreach ($userAttributes as $attr) {
            $code = $attr->getAttributeCode();
            if (in_array($code, $internal) || $attr->getFrontendInput()=='hidden') {
                continue;
            }
            $attributes[$code] = $code;
        }
        $attributes['password_hash'] = 'password_hash';

        foreach ($addressAttributes as $attr) {
            $code = $attr->getAttributeCode();
            if (in_array($code, $internal) || $attr->getFrontendInput()=='hidden') {
                continue;
            }

            if ($code == 'street') {
                $attributes['billing_'.$code.'_full'] = 'billing_'.$code;
            } else {
                $attributes['billing_'.$code] = 'billing_'.$code;
            }
        }
        $attributes['billing_country'] = 'billing_country';

        foreach ($addressAttributes as $attr) {
            $code = $attr->getAttributeCode();
            if (in_array($code, $internal) || $attr->getFrontendInput()=='hidden') {
                continue;
            }

            if ($code == 'street') {
                $attributes['shipping_'.$code.'_full'] = 'shipping_'.$code;
            } else {
                $attributes['shipping_'.$code] = 'shipping_'.$code;
            }
        }
        $attributes['shipping_country'] = 'shipping_country';

        return $attributes;
    }

    /**
     * Gets group code by user's groupId
     *
     * @param Mage_User_Model_User $user
     * @return string|null
     */
    protected function _getUserGroupCode($user)
    {
        if (is_null($this->_userGroups)) {
            $groups = Mage::getResourceModel('user/group_collection')
                    ->load();

            foreach ($groups as $group) {
                $this->_userGroups[$group->getId()] = $group->getData('user_group_code');
            }
        }

        if (isset($this->_userGroups[$user->getGroupId()])) {
            return $this->_userGroups[$user->getGroupId()];
        } else {
            return null;
        }
    }

   /* ########### THE CODE BELOW IS NOT USED ############# */

    public function unparse__OLD()
    {
        $collections = $this->getData();
//        if ($collections instanceof Mage_Eav_Model_Entity_Collection_Abstract) {
//            $collections = array($collections->getEntity()->getStoreId()=>$collections);
//        } elseif (!is_array($collections)) {
//            $this->addException(Mage::helper('user')->__("Array of Entity collections is expected."), Varien_Convert_Exception::FATAL);
//        }

//        foreach ($collections as $storeId=>$collection) {
//           if (!$collection instanceof Mage_Eav_Model_Entity_Collection_Abstract) {
//               $this->addException(Mage::helper('user')->__("Entity collection is expected."), Varien_Convert_Exception::FATAL);
//            }

            $data = array();

            foreach ($collections->getIterator() as $i=>$model) {
                $this->setPosition('Line: '.($i+1).', Email: '.$model->getEmail());



                // Will be removed after confirmation from Dima or Moshe
                $row = array(
                    'store_view'=>$this->getStoreCode($this->getVar('store') ? $this->getVar('store') : $storeId),
                ); // End

                foreach ($model->getData() as $field=>$value) {
                    // set website_id
                    if ($field == 'website_id') {
                      $row['website_code'] = Mage::getModel('core/website')->load($value)->getCode();
                      continue;
                    } // end

                    if (in_array($field, $systemFields)) {
                        continue;
                    }

                    $attribute = $model->getResource()->getAttribute($field);
                    if (!$attribute) {
                        continue;
                    }

                    if ($attribute->usesSource()) {
                        $option = $attribute->getSource()->getOptionText($value);

                        if (false===$option) {
                            $this->addException(Mage::helper('user')->__("An invalid option ID is specified for %s (%s), skipping the record.", $field, $value), Mage_Dataflow_Model_Convert_Exception::ERROR);
                            continue;
                        }
                        if (is_array($option)) {
                            $value = $option['label'];
                        } else {
                            $value = $option;
                        }
                    }
                    $row[$field] = $value;

                    $billingAddress = $model->getDefaultBillingAddress();
                    if($billingAddress instanceof Mage_User_Model_Address){
                        $billingAddress->explodeStreetAddress();
                        $row['billing_street1']     = $billingAddress->getStreet1();
                        $row['billing_street2']     = $billingAddress->getStreet2();
                        $row['billing_city']        = $billingAddress->getCity();
                        $row['billing_region']      = $billingAddress->getRegion();
                        $row['billing_country']     = $billingAddress->getCountry();
                        $row['billing_postcode']    = $billingAddress->getPostcode();
                        $row['billing_telephone']   = $billingAddress->getTelephone();
                    }

                    $shippingAddress = $model->getDefaultShippingAddress();
                    if($shippingAddress instanceof Mage_User_Model_Address){
                        $shippingAddress->explodeStreetAddress();
                        $row['shipping_street1']    = $shippingAddress->getStreet1();
                        $row['shipping_street2']    = $shippingAddress->getStreet2();
                        $row['shipping_city']       = $shippingAddress->getCity();
                        $row['shipping_region']     = $shippingAddress->getRegion();
                        $row['shipping_country']    = $shippingAddress->getCountry();
                        $row['shipping_postcode']   = $shippingAddress->getPostcode();
                        $row['shipping_telephone']  = $shippingAddress->getTelephone();
                    }

                    if($model->getGroupId()){
                        $group = Mage::getResourceModel('user/group_collection')
                        ->addFilter('user_group_id',$model->getGroupId())
                        ->load();
                        $row['group']=$group->getFirstItem()->getData('user_group_code');
                    }
                }
                $subscriber = Mage::getModel('newsletter/subscriber')->loadByUser($model);
                if ($subscriber->getId()) {
                    if ($subscriber->getSubscriberStatus() == Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED) {
                        $row['is_subscribed'] = Mage_User_Model_User::SUBSCRIBED_YES;
                    } else {
                        $row['is_subscribed'] = Mage_User_Model_User::SUBSCRIBED_NO;
                    }
                }
                if(!isset($row['created_in'])){
                    $row['created_in'] = 'Admin';
                }
                $data[] = $row;

            }
//       }
        $this->setData($data);
        return $this;
    }

    /**
     * @deprecated not used anymore
     */
    public function parse()
    {
        $data = $this->getData();

        $entityTypeId = Mage::getSingleton('eav/config')->getEntityType('user')->getId();
        $result = array();
        foreach ($data as $i=>$row) {
            $this->setPosition('Line: '.($i+1));
            try {

                // validate SKU
                if (empty($row['email'])) {
                    $this->addException(Mage::helper('user')->__('Missing email, skipping the record.'), Varien_Convert_Exception::ERROR);
                    continue;
                }
                $this->setPosition('Line: '.($i+1).', email: '.$row['email']);

                // try to get entity_id by sku if not set
                /*
                if (empty($row['entity_id'])) {
                    $row['entity_id'] = $this->getResource()->getProductIdBySku($row['email']);
                }
                */

                // if attribute_set not set use default
                if (empty($row['attribute_set'])) {
                    $row['attribute_set'] = 'Default';
                }

                // get attribute_set_id, if not throw error
                $row['attribute_set_id'] = $this->getAttributeSetId($entityTypeId, $row['attribute_set']);
                if (!$row['attribute_set_id']) {
                    $this->addException(Mage::helper('user')->__("Invalid attribute set specified, skipping the record."), Varien_Convert_Exception::ERROR);
                    continue;
                }

                if (empty($row['group'])) {
                    $row['group'] = 'General';
                }

                if (empty($row['firstname'])) {
                    $this->addException(Mage::helper('user')->__('Missing firstname, skipping the record.'), Varien_Convert_Exception::ERROR);
                    continue;
                }
                //$this->setPosition('Line: '.($i+1).', Firstname: '.$row['firstname']);

                if (empty($row['lastname'])) {
                    $this->addException(Mage::helper('user')->__('Missing lastname, skipping the record.'), Varien_Convert_Exception::ERROR);
                    continue;
                }
                //$this->setPosition('Line: '.($i+1).', Lastname: '.$row['lastname']);

                /*
                // get product type_id, if not throw error
                $row['type_id'] = $this->getProductTypeId($row['type']);
                if (!$row['type_id']) {
                    $this->addException(Mage::helper('adminhtml')->__("Invalid product type specified, skipping the record."), Varien_Convert_Exception::ERROR);
                    continue;
                }
                */

                // get store ids
                $storeIds = $this->getStoreIds(isset($row['store']) ? $row['store'] : $this->getVar('store'));
                if (!$storeIds) {
                    $this->addException(Mage::helper('user')->__("Invalid store specified, skipping the record."), Varien_Convert_Exception::ERROR);
                    continue;
                }

                // import data
                $rowError = false;
                foreach ($storeIds as $storeId) {
                    $collection = $this->getCollection($storeId);
                    //print_r($collection);
                    $entity = $collection->getEntity();

                    $model = Mage::getModel('user/user');
                    $model->setStoreId($storeId);
                    if (!empty($row['entity_id'])) {
                        $model->load($row['entity_id']);
                    }
                    foreach ($row as $field=>$value) {
                        $attribute = $entity->getAttribute($field);
                        if (!$attribute) {
                            continue;
                            #$this->addException(Mage::helper('adminhtml')->__("Unknown attribute: %s.", $field), Varien_Convert_Exception::ERROR);

                        }

                        if ($attribute->usesSource()) {
                            $source = $attribute->getSource();
                            $optionId = $this->getSourceOptionId($source, $value);
                            if (is_null($optionId)) {
                                $rowError = true;
                                $this->addException(Mage::helper('user')->__("Invalid attribute option specified for attribute %s (%s), skipping the record.", $field, $value), Varien_Convert_Exception::ERROR);
                                continue;
                            }
                            $value = $optionId;
                        }
                        $model->setData($field, $value);

                    }//foreach ($row as $field=>$value)


                    $billingAddress = $model->getPrimaryBillingAddress();
                    $user = Mage::getModel('user/user')->load($model->getId());


                    if (!$billingAddress  instanceof Mage_User_Model_Address) {
                        $billingAddress = Mage::getModel('user/address');
                        if ($user->getId() && $user->getDefaultBilling()) {
                            $billingAddress->setId($user->getDefaultBilling());
                        }
                    }

                    $regions = Mage::getResourceModel('directory/region_collection')
                        ->addRegionNameFilter($row['billing_region'])
                        ->load();
                    if ($regions) foreach($regions as $region) {
                       $regionId = $region->getId();
                    }

                    $billingAddress->setFirstname($row['firstname']);
                    $billingAddress->setLastname($row['lastname']);
                    $billingAddress->setCity($row['billing_city']);
                    $billingAddress->setRegion($row['billing_region']);
                    $billingAddress->setRegionId($regionId);
                    $billingAddress->setCountryId($row['billing_country']);
                    $billingAddress->setPostcode($row['billing_postcode']);
                    $billingAddress->setStreet(array($row['billing_street1'],$row['billing_street2']));
                    if (!empty($row['billing_telephone'])) {
                        $billingAddress->setTelephone($row['billing_telephone']);
                    }

                    if (!$model->getDefaultBilling()) {
                        $billingAddress->setUserId($model->getId());
                        $billingAddress->setIsDefaultBilling(true);
                        $billingAddress->save();
                        $model->setDefaultBilling($billingAddress->getId());
                        $model->addAddress($billingAddress);
                        if ($user->getDefaultBilling()) {
                            $model->setDefaultBilling($user->getDefaultBilling());
                        } else {
                            $shippingAddress->save();
                            $model->setDefaultShipping($billingAddress->getId());
                            $model->addAddress($billingAddress);

                        }
                    }

                    $shippingAddress = $model->getPrimaryShippingAddress();
                    if (!$shippingAddress instanceof Mage_User_Model_Address) {
                        $shippingAddress = Mage::getModel('user/address');
                        if ($user->getId() && $user->getDefaultShipping()) {
                            $shippingAddress->setId($user->getDefaultShipping());
                        }
                    }

                    $regions = Mage::getResourceModel('directory/region_collection')
                        ->addRegionNameFilter($row['shipping_region'])
                        ->load();
                    if ($regions) foreach($regions as $region) {
                       $regionId = $region->getId();
                    }

                    $shippingAddress->setFirstname($row['firstname']);
                    $shippingAddress->setLastname($row['lastname']);
                    $shippingAddress->setCity($row['shipping_city']);
                    $shippingAddress->setRegion($row['shipping_region']);
                    $shippingAddress->setRegionId($regionId);
                    $shippingAddress->setCountryId($row['shipping_country']);
                    $shippingAddress->setPostcode($row['shipping_postcode']);
                    $shippingAddress->setStreet(array($row['shipping_street1'], $row['shipping_street2']));
                    $shippingAddress->setUserId($model->getId());
                    if (!empty($row['shipping_telephone'])) {
                        $shippingAddress->setTelephone($row['shipping_telephone']);
                    }

                    if (!$model->getDefaultShipping()) {
                        if ($user->getDefaultShipping()) {
                            $model->setDefaultShipping($user->getDefaultShipping());
                        } else {
                            $shippingAddress->save();
                            $model->setDefaultShipping($shippingAddress->getId());
                            $model->addAddress($shippingAddress);

                        }
                        $shippingAddress->setIsDefaultShipping(true);
                    }

                    if (!$rowError) {
                        $collection->addItem($model);
                    }

                } //foreach ($storeIds as $storeId)

            } catch (Exception $e) {
                if (!$e instanceof Mage_Dataflow_Model_Convert_Exception) {
                    $this->addException(Mage::helper('user')->__('An error occurred while retrieving the option value: %s.', $e->getMessage()), Mage_Dataflow_Model_Convert_Exception::FATAL);
                }
            }
        }
        $this->setData($this->_collections);
        return $this;
    }
}
