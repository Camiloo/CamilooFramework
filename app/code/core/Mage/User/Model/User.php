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
 * User model
 *
 * @author      Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_User_Model_User extends Mage_Core_Model_Abstract
{
    const XML_PATH_REGISTER_EMAIL_TEMPLATE      = 'user/create_account/email_template';
    const XML_PATH_REGISTER_EMAIL_IDENTITY      = 'user/create_account/email_identity';
    const XML_PATH_FORGOT_EMAIL_TEMPLATE        = 'user/password/forgot_email_template';
    const XML_PATH_FORGOT_EMAIL_IDENTITY        = 'user/password/forgot_email_identity';
    const XML_PATH_DEFAULT_EMAIL_DOMAIN         = 'user/create_account/email_domain';
    const XML_PATH_IS_CONFIRM                   = 'user/create_account/confirm';
    const XML_PATH_CONFIRM_EMAIL_TEMPLATE       = 'user/create_account/email_confirmation_template';
    const XML_PATH_CONFIRMED_EMAIL_TEMPLATE     = 'user/create_account/email_confirmed_template';
    const XML_PATH_GENERATE_HUMAN_FRIENDLY_ID   = 'user/create_account/generate_human_friendly_id';

    const EXCEPTION_EMAIL_NOT_CONFIRMED       = 1;
    const EXCEPTION_INVALID_EMAIL_OR_PASSWORD = 2;
    const EXCEPTION_EMAIL_EXISTS              = 3;

    const SUBSCRIBED_YES = 'yes';
    const SUBSCRIBED_NO  = 'no';

    protected $_eventPrefix = 'user';
    protected $_eventObject = 'user';
    protected $_errors    = array();
    protected $_attributes;

    /**
     * User addresses array
     *
     * @var array
     * @deprecated after 1.4.0.0-rc1
     */
    protected $_addresses = null;

    /**
     * User addresses collection
     *
     * @var Mage_User_Model_Entity_Address_Collection
     */
    protected $_addressesCollection;

    /**
     * Is model deleteable
     *
     * @var boolean
     */
    protected $_isDeleteable = true;

    /**
     * Is model readonly
     *
     * @var boolean
     */
    protected $_isReadonly = false;

    private static $_isConfirmationRequired;

    function _construct()
    {
        $this->_init('user/user');
    }

    /**
     * Retrieve user sharing configuration model
     *
     * @return Mage_User_Model_Config_Share
     */
    public function getSharingConfig()
    {
        return Mage::getSingleton('user/config_share');
    }

    /**
     * Authenticate user
     *
     * @param  string $login
     * @param  string $password
     * @throws Mage_Core_Exception
     * @return true
     *
     */
    public function authenticate($login, $password)
    {
        $this->loadByEmail($login);
        if ($this->getConfirmation() && $this->isConfirmationRequired()) {
            throw Mage::exception('Mage_Core', Mage::helper('user')->__('This account is not confirmed.'),
                self::EXCEPTION_EMAIL_NOT_CONFIRMED
            );
        }
        if (!$this->validatePassword($password)) {
            throw Mage::exception('Mage_Core', Mage::helper('user')->__('Invalid login or password.'),
                self::EXCEPTION_INVALID_EMAIL_OR_PASSWORD
            );
        }
        Mage::dispatchEvent('user_user_authenticated', array(
           'model'    => $this,
           'password' => $password,
        ));

        return true;
    }

    /**
     * Load user by email
     *
     * @param   string $userEmail
     * @return  Mage_User_Model_User
     */
    public function loadByEmail($userEmail)
    {
        $this->_getResource()->loadByEmail($this, $userEmail);
        return $this;
    }


    /**
     * Processing object before save data
     *
     * @return Mage_User_Model_User
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();

        $storeId = $this->getStoreId();
        if ($storeId === null) {
            $this->setStoreId(Mage::app()->getStore()->getId());
        }

        $this->getGroupId();
        return $this;
    }

    /**
     * Change user password
     *
     * @param   string $newPassword
     * @return  Mage_User_Model_User
     */
    public function changePassword($newPassword)
    {
        $this->_getResource()->changePassword($this, $newPassword);
        return $this;
    }

    /**
     * Get full user name
     *
     * @return string
     */
    public function getName()
    {
        $name = '';
        $config = Mage::getSingleton('eav/config');
        if ($config->getAttribute('user', 'prefix')->getIsVisible() && $this->getPrefix()) {
            $name .= $this->getPrefix() . ' ';
        }
        $name .= $this->getFirstname();
        if ($config->getAttribute('user', 'middlename')->getIsVisible() && $this->getMiddlename()) {
            $name .= ' ' . $this->getMiddlename();
        }
        $name .=  ' ' . $this->getLastname();
        if ($config->getAttribute('user', 'suffix')->getIsVisible() && $this->getSuffix()) {
            $name .= ' ' . $this->getSuffix();
        }
        return $name;
    }

    /**
     * Add address to address collection
     *
     * @param   Mage_User_Model_Address $address
     * @return  Mage_User_Model_User
     */
    public function addAddress(Mage_User_Model_Address $address)
    {
        $this->getAddressesCollection()->addItem($address);
        $this->getAddresses();
        $this->_addresses[] = $address;
        return $this;
    }

    /**
     * Retrieve user address by address id
     *
     * @param   int $addressId
     * @return  Mage_User_Model_Address
     */
    public function getAddressById($addressId)
    {
        return Mage::getModel('user/address')
            ->load($addressId);
    }

    /**
     * Getting user address object from collection by identifier
     *
     * @param int $addressId
     * @return Mage_User_Model_Address
     */
    public function getAddressItemById($addressId)
    {
        return $this->getAddressesCollection()->getItemById($addressId);
    }

    /**
     * Retrieve not loaded address collection
     *
     * @return Mage_User_Model_Entity_Address_Collection
     */
    public function getAddressCollection()
    {
        return Mage::getResourceModel('user/address_collection');
    }

    /**
     * User addresses collection
     *
     * @return Mage_User_Model_Entity_Address_Collection
     */
    public function getAddressesCollection()
    {
        if ($this->_addressesCollection === null) {
            $this->_addressesCollection = $this->getAddressCollection()
                ->setUserFilter($this)
                ->addAttributeToSelect('*');
            foreach ($this->_addressesCollection as $address) {
                $address->setUser($this);
            }
        }

        return $this->_addressesCollection;
    }

    /**
     * Retrieve user address array
     *
     * @return array
     */
    public function getAddresses()
    {
        $this->_addresses = $this->getAddressesCollection()->getItems();
        return $this->_addresses;
    }

    /**
     * Retrieve all user attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        if ($this->_attributes === null) {
            $this->_attributes = $this->_getResource()
            ->loadAllAttributes($this)
            ->getSortedAttributes();
        }
        return $this->_attributes;
    }

    /**
     * Get user attribute model object
     *
     * @param   string $attributeCode
     * @return  Mage_User_Model_Entity_Attribute | null
     */
    public function getAttribute($attributeCode)
    {
        $this->getAttributes();
        if (isset($this->_attributes[$attributeCode])) {
            return $this->_attributes[$attributeCode];
        }
        return null;
    }

    /**
     * Set plain and hashed password
     *
     * @param string $password
     * @return Mage_User_Model_User
     */
    public function setPassword($password)
    {
        $this->setData('password', $password);
        $this->setPasswordHash($this->hashPassword($password));
        return $this;
    }

    /**
     * Hash user password
     *
     * @param   string $password
     * @param   int    $salt
     * @return  string
     */
    public function hashPassword($password, $salt = null)
    {
        return Mage::helper('core')->getHash($password, !is_null($salt) ? $salt : 2);
    }

    /**
     * Retrieve random password
     *
     * @param   int $length
     * @return  string
     */
    public function generatePassword($length = 6)
    {
        return Mage::helper('core')->getRandomString($length);
    }

    /**
     * Validate password with salted hash
     *
     * @param string $password
     * @return boolean
     */
    public function validatePassword($password)
    {
        $hash = $this->getPasswordHash();
        if (!$hash) {
            return false;
        }
        return Mage::helper('core')->validateHash($password, $hash);
    }


    /**
     * Encrypt password
     *
     * @param   string $password
     * @return  string
     */
    public function encryptPassword($password)
    {
        return Mage::helper('core')->encrypt($password);
    }

    /**
     * Decrypt password
     *
     * @param   string $password
     * @return  string
     */
    public function decryptPassword($password)
    {
        return Mage::helper('core')->decrypt($password);
    }

    /**
     * Retrieve default address by type(attribute)
     *
     * @param   string $attributeCode address type attribute code
     * @return  Mage_User_Model_Address
     */
    public function getPrimaryAddress($attributeCode)
    {
        $primaryAddress = $this->getAddressesCollection()->getItemById($this->getData($attributeCode));

        return $primaryAddress ? $primaryAddress : false;
    }

    /**
     * Get user default billing address
     *
     * @return Mage_User_Model_Address
     */
    public function getPrimaryBillingAddress()
    {
        return $this->getPrimaryAddress('default_billing');
    }

    /**
     * Get user default billing address
     *
     * @return Mage_User_Model_Address
     */
    public function getDefaultBillingAddress()
    {
        return $this->getPrimaryBillingAddress();
    }

    /**
     * Get default user shipping address
     *
     * @return Mage_User_Model_Address
     */
    public function getPrimaryShippingAddress()
    {
        return $this->getPrimaryAddress('default_shipping');
    }

    /**
     * Get default user shipping address
     *
     * @return Mage_User_Model_Address
     */
    public function getDefaultShippingAddress()
    {
        return $this->getPrimaryShippingAddress();
    }

    /**
     * Retrieve ids of default addresses
     *
     * @return array
     */
    public function getPrimaryAddressIds()
    {
        $ids = array();
        if ($this->getDefaultBilling()) {
            $ids[] = $this->getDefaultBilling();
        }
        if ($this->getDefaultShipping()) {
            $ids[] = $this->getDefaultShipping();
        }
        return $ids;
    }

    /**
     * Retrieve all user default addresses
     *
     * @return array
     */
    public function getPrimaryAddresses()
    {
        $addresses = array();
        $primaryBilling = $this->getPrimaryBillingAddress();
        if ($primaryBilling) {
            $addresses[] = $primaryBilling;
            $primaryBilling->setIsPrimaryBilling(true);
        }

        $primaryShipping = $this->getPrimaryShippingAddress();
        if ($primaryShipping) {
            if ($primaryBilling->getId() == $primaryShipping->getId()) {
                $primaryBilling->setIsPrimaryShipping(true);
            } else {
                $primaryShipping->setIsPrimaryShipping(true);
                $addresses[] = $primaryShipping;
            }
        }
        return $addresses;
    }

    /**
     * Retrieve not default addresses
     *
     * @return array
     */
    public function getAdditionalAddresses()
    {
        $addresses = array();
        $primatyIds = $this->getPrimaryAddressIds();
        foreach ($this->getAddressesCollection() as $address) {
            if (!in_array($address->getId(), $primatyIds)) {
                $addresses[] = $address;
            }
        }
        return $addresses;
    }

    public function isAddressPrimary(Mage_User_Model_Address $address)
    {
        if (!$address->getId()) {
            return false;
        }
        return ($address->getId() == $this->getDefaultBilling()) || ($address->getId() == $this->getDefaultShipping());
    }

    /**
     * Send email with new account specific information
     *
     * @throws Mage_Core_Exception
     * @return Mage_User_Model_User
     */
    public function sendNewAccountEmail($type = 'registered', $backUrl = '', $storeId = '0')
    {
        $types = array(
            'registered'   => self::XML_PATH_REGISTER_EMAIL_TEMPLATE,  // welcome email, when confirmation is disabled
            'confirmed'    => self::XML_PATH_CONFIRMED_EMAIL_TEMPLATE, // welcome email, when confirmation is enabled
            'confirmation' => self::XML_PATH_CONFIRM_EMAIL_TEMPLATE,   // email with confirmation link
        );
        if (!isset($types[$type])) {
            Mage::throwException(Mage::helper('user')->__('Wrong transactional account email type'));
        }

        $translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);

        if (!$storeId) {
            $storeId = $this->_getWebsiteStoreId($this->getSendemailStoreId());
        }

        Mage::getModel('core/email_template')
            ->setDesignConfig(array('area' => 'frontend', 'store' => $storeId))
            ->sendTransactional(
                Mage::getStoreConfig($types[$type], $storeId),
                Mage::getStoreConfig(self::XML_PATH_REGISTER_EMAIL_IDENTITY, $storeId),
                $this->getEmail(),
                $this->getName(),
                array('user' => $this, 'back_url' => $backUrl));

        $translate->setTranslateInline(true);

        return $this;
    }

    /**
     * Check if accounts confirmation is required in config
     *
     * @return bool
     */
    public function isConfirmationRequired()
    {
        if ($this->canSkipConfirmation()) {
            return false;
        }
        if (self::$_isConfirmationRequired === null) {
            $storeId = $this->getStoreId() ? $this->getStoreId() : null;
            self::$_isConfirmationRequired = (bool)Mage::getStoreConfig(self::XML_PATH_IS_CONFIRM, $storeId);
        }

        return self::$_isConfirmationRequired;
    }

    public function getRandomConfirmationKey()
    {
        return md5(uniqid());
    }

    /**
     * Send email with new user password
     *
     * @return Mage_User_Model_User
     */
    public function sendPasswordReminderEmail()
    {
        $translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);

        $storeId = $this->getStoreId();
        if (!$storeId) {
            $storeId = $this->_getWebsiteStoreId();
        }

        Mage::getModel('core/email_template')
            ->setDesignConfig(array('area' => 'frontend', 'store' => $storeId))
            ->sendTransactional(
                Mage::getStoreConfig(self::XML_PATH_FORGOT_EMAIL_TEMPLATE, $storeId),
                Mage::getStoreConfig(self::XML_PATH_FORGOT_EMAIL_IDENTITY, $storeId),
                $this->getEmail(),
                $this->getName(),
                array('user' => $this)
            );

        $translate->setTranslateInline(true);

        return $this;
    }

    /**
     * Retrieve user group identifier
     *
     * @return int
     */
    public function getGroupId()
    {
        if (!$this->hasData('group_id')) {
            $storeId = $this->getStoreId() ? $this->getStoreId() : Mage::app()->getStore()->getId();
            $groupId = Mage::getStoreConfig(Mage_User_Model_Group::XML_PATH_DEFAULT_ID, $storeId);
            $this->setData('group_id', $groupId);
        }
        return $this->getData('group_id');
    }

    /**
     * Retrieve user tax class identifier
     *
     * @return int
     */
    public function getTaxClassId()
    {
        if (!$this->getData('tax_class_id')) {
            $this->setTaxClassId(Mage::getModel('user/group')->getTaxClassId($this->getGroupId()));
        }
        return $this->getData('tax_class_id');
    }

    /**
     * Check store availability for user
     *
     * @param   Mage_Core_Model_Store | int $store
     * @return  bool
     */
    public function isInStore($store)
    {
        if ($store instanceof Mage_Core_Model_Store) {
            $storeId = $store->getId();
        } else {
            $storeId = $store;
        }

        $availableStores = $this->getSharedStoreIds();
        return in_array($storeId, $availableStores);
    }

    /**
     * Retrieve store where user was created
     *
     * @return Mage_Core_Model_Store
     */
    public function getStore()
    {
        return Mage::app()->getStore($this->getStoreId());
    }

    /**
     * Retrieve shared store ids
     *
     * @return array
     */
    public function getSharedStoreIds()
    {
        $ids = $this->_getData('shared_store_ids');
        if ($ids === null) {
            $ids = array();
            if ((bool)$this->getSharingConfig()->isWebsiteScope()) {
                $ids = Mage::app()->getWebsite($this->getWebsiteId())->getStoreIds();
            } else {
                foreach (Mage::app()->getStores() as $store) {
                    $ids[] = $store->getId();
                }
            }
            $this->setData('shared_store_ids', $ids);
        }

        return $ids;
    }

    /**
     * Retrive shared website ids
     *
     * @return array
     */
    public function getSharedWebsiteIds()
    {
        $ids = $this->_getData('shared_website_ids');
        if ($ids === null) {
            $ids = array();
            if ((bool)$this->getSharingConfig()->isWebsiteScope()) {
                $ids[] = $this->getWebsiteId();
            } else {
                foreach (Mage::app()->getWebsites() as $website) {
                    $ids[] = $website->getId();
                }
            }
            $this->setData('shared_website_ids', $ids);
        }
        return $ids;
    }

    /**
     * Set store to user
     *
     * @param Mage_Core_Model_Store $store
     * @return Mage_User_Model_User
     */
    public function setStore(Mage_Core_Model_Store $store)
    {
        $this->setStoreId($store->getId());
        $this->setWebsiteId($store->getWebsite()->getId());
        return $this;
    }

    /**
     * Validate user attribute values.
     * For existing user password + confirmation will be validated only when password is set (i.e. its change is requested)
     *
     * @return bool
     */
    public function validate()
    {
        $errors = array();
        $userHelper = Mage::helper('user');
        if (!Zend_Validate::is( trim($this->getFirstname()) , 'NotEmpty')) {
            $errors[] = $userHelper->__('The first name cannot be empty.');
        }

        if (!Zend_Validate::is( trim($this->getLastname()) , 'NotEmpty')) {
            $errors[] = $userHelper->__('The last name cannot be empty.');
        }

        if (!Zend_Validate::is($this->getEmail(), 'EmailAddress')) {
            $errors[] = $userHelper->__('Invalid email address "%s".', $this->getEmail());
        }

        $password = $this->getPassword();
        if (!$this->getId() && !Zend_Validate::is($password , 'NotEmpty')) {
            $errors[] = $userHelper->__('The password cannot be empty.');
        }
        if (strlen($password) && !Zend_Validate::is($password, 'StringLength', array(6))) {
            $errors[] = $userHelper->__('The minimum password length is %s', 6);
        }
        $confirmation = $this->getConfirmation();
        if ($password != $confirmation) {
            $errors[] = $userHelper->__('Please make sure your passwords match.');
        }

        $entityType = Mage::getSingleton('eav/config')->getEntityType('user');
        $attribute = Mage::getModel('user/attribute')->loadByCode($entityType, 'dob');
        if ($attribute->getIsRequired() && '' == trim($this->getDob())) {
            $errors[] = $userHelper->__('The Date of Birth is required.');
        }
        $attribute = Mage::getModel('user/attribute')->loadByCode($entityType, 'taxvat');
        if ($attribute->getIsRequired() && '' == trim($this->getTaxvat())) {
            $errors[] = $userHelper->__('The TAX/VAT number is required.');
        }
        $attribute = Mage::getModel('user/attribute')->loadByCode($entityType, 'gender');
        if ($attribute->getIsRequired() && '' == trim($this->getGender())) {
            $errors[] = $userHelper->__('Gender is required.');
        }

        if (empty($errors)) {
            return true;
        }
        return $errors;
    }

    /**
     * Importing user data from text array
     *
     * @param array $row
     * @return uMage_User_Model_User
     */
    public function importFromTextArray(array $row)
    {
        $this->resetErrors();
        $hlp = Mage::helper('user');
        $line = $row['i'];
        $row = $row['row'];

        $regions = Mage::getResourceModel('directory/region_collection');

        $website = Mage::getModel('core/website')->load($row['website_code'], 'code');

        if (!$website->getId()) {
            $this->addError($hlp->__('Invalid website, skipping the record, line: %s', $line));

        } else {
            $row['website_id'] = $website->getWebsiteId();
            $this->setWebsiteId($row['website_id']);
        }

        // Validate Email
        if (empty($row['email'])) {
            $this->addError($hlp->__('Missing email, skipping the record, line: %s', $line));
        } else {
            $this->loadByEmail($row['email']);
        }

        if (empty($row['entity_id'])) {
            if ($this->getData('entity_id')) {
                $this->addError($hlp->__('The user email (%s) already exists, skipping the record, line: %s', $row['email'], $line));
            }
        } else {
            if ($row['entity_id'] != $this->getData('entity_id')) {
                $this->addError($hlp->__('The user ID and email did not match, skipping the record, line: %s', $line));
            } else {
                $this->unsetData();
                $this->load($row['entity_id']);
                if (isset($row['store_view'])) {
                    $storeId = Mage::app()->getStore($row['store_view'])->getId();
                    if ($storeId) $this->setStoreId($storeId);
                }
            }
        }

        if (empty($row['website_code'])) {
            $this->addError($hlp->__('Missing website, skipping the record, line: %s', $line));
        }

        if (empty($row['group'])) {
            $row['group'] = 'General';
        }

        if (empty($row['firstname'])) {
            $this->addError($hlp->__('Missing first name, skipping the record, line: %s', $line));
        }
        if (empty($row['lastname'])) {
            $this->addError($hlp->__('Missing last name, skipping the record, line: %s', $line));
        }

        if (!empty($row['password_new'])) {
            $this->setPassword($row['password_new']);
            unset($row['password_new']);
            if (!empty($row['password_hash'])) unset($row['password_hash']);
        }

        if ($errors = $this->getErrors()) {
            $this->unsetData();
            $this->printError(implode('<br />', $errors));
            return;
        }

        foreach ($row as $field => $value) {
            $this->setData($field, $value);
        }

        if (!$this->validateAddress($row, 'billing')) {
            $this->printError($hlp->__('Invalid billing address for (%s)', $row['email']), $line);
        } else {
            // Handling billing address
            $billingAddress = $this->getPrimaryBillingAddress();
            if (!$billingAddress  instanceof Mage_User_Model_Address) {
                $billingAddress = Mage::getModel('user/address');
            }

            $regions->addRegionNameFilter($row['billing_region'])->load();
            if ($regions) foreach($regions as $region) {
                $regionId = $region->getId();
            }

            $billingAddress->setFirstname($row['firstname']);
            $billingAddress->setLastname($row['lastname']);
            $billingAddress->setCity($row['billing_city']);
            $billingAddress->setRegion($row['billing_region']);
            if (isset($regionId)) {
                $billingAddress->setRegionId($regionId);
            }
            $billingAddress->setCountryId($row['billing_country']);
            $billingAddress->setPostcode($row['billing_postcode']);
            if (isset($row['billing_street2'])) {
                $billingAddress->setStreet(array($row['billing_street1'], $row['billing_street2']));
            } else {
                $billingAddress->setStreet(array($row['billing_street1']));
            }
            if (isset($row['billing_telephone'])) {
                $billingAddress->setTelephone($row['billing_telephone']);
            }

            if (!$billingAddress->getId()) {
                $billingAddress->setIsDefaultBilling(true);
                if ($this->getDefaultBilling()) {
                    $this->setData('default_billing', '');
                }
                $this->addAddress($billingAddress);
            } // End handling billing address
        }

        if (!$this->validateAddress($row, 'shipping')) {
            $this->printError($hlp->__('Invalid shipping address for (%s)', $row['email']), $line);
        } else {
            // Handling shipping address
            $shippingAddress = $this->getPrimaryShippingAddress();
            if (!$shippingAddress instanceof Mage_User_Model_Address) {
                $shippingAddress = Mage::getModel('user/address');
            }

            $regions->addRegionNameFilter($row['shipping_region'])->load();

            if ($regions) foreach($regions as $region) {
               $regionId = $region->getId();
            }

            $shippingAddress->setFirstname($row['firstname']);
            $shippingAddress->setLastname($row['lastname']);
            $shippingAddress->setCity($row['shipping_city']);
            $shippingAddress->setRegion($row['shipping_region']);
            if (isset($regionId)) {
                $shippingAddress->setRegionId($regionId);
            }
            $shippingAddress->setCountryId($row['shipping_country']);
            $shippingAddress->setPostcode($row['shipping_postcode']);
            if (isset($row['shipping_street2'])) {
                $shippingAddress->setStreet(array($row['shipping_street1'], $row['shipping_street2']));
            } else {
                $shippingAddress->setStreet(array($row['shipping_street1']));
            }
            if (!empty($row['shipping_telephone'])) {
                $shippingAddress->setTelephone($row['shipping_telephone']);
            }

            if (!$shippingAddress->getId()) {
               $shippingAddress->setIsDefaultShipping(true);
               $this->addAddress($shippingAddress);
            }
            // End handling shipping address
        }
        if (!empty($row['is_subscribed'])) {
            $isSubscribed = (bool)strtolower($row['is_subscribed']) == self::SUBSCRIBED_YES;
            $this->setIsSubscribed($isSubscribed);
        }
        unset($row);
        return $this;
    }

    /**
     * Unset subscription
     *
     * @return Mage_User_Model_User
     */
    function unsetSubscription()
    {
        if (isset($this->_isSubscribed)) {
            unset($this->_isSubscribed);
        }
        return $this;
    }

    /**
     * Clean all addresses
     *
     * @return Mage_User_Model_User
     */
    function cleanAllAddresses() {
        $this->_addressesCollection = null;
        $this->_addresses           = null;
    }

    /**
     * Add error
     *
     * @return Mage_User_Model_User
     */
    function addError($error)
    {
        $this->_errors[] = $error;
        return $this;
    }

    /**
     * Retreive errors
     *
     * @return array
     */
    function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Reset errors array
     *
     * @return Mage_User_Model_User
     */
    function resetErrors()
    {
        $this->_errors = array();
        return $this;
    }

    /**
     * Print error
     *
     * @param $error
     * @param $line
     */
    function printError($error, $line = null)
    {
        if ($error == null) {
            return false;
        }
        $img     = 'error_msg_icon.gif';
        $liStyle = 'background-color:#FDD; ';
        echo '<li style="'.$liStyle.'">';
        echo '<img src="'.Mage::getDesign()->getSkinUrl('images/'.$img).'" class="v-middle"/>';
        echo $error;
        if ($line) {
            echo '<small>, Line: <b>'.$line.'</b></small>';
        }
        echo "</li>";
    }

    /**
     * Validate address
     *
     * @param array $data
     * @param string $type
     * @return bool
     */
    function validateAddress(array $data, $type = 'billing')
    {
        $fields = array('city', 'country', 'postcode', 'telephone', 'street1');
        $usca   = array('US', 'CA');
        $prefix = $type ? $type . '_' : '';

        if ($data) {
            foreach($fields as $field) {
                if (!isset($data[$prefix . $field])) {
                    return false;
                }
                if ($field == 'country'
                    && in_array(strtolower($data[$prefix . $field]), array('US', 'CA'))) {

                    if (!isset($data[$prefix . 'region'])) {
                        return false;
                    }

                    $region = Mage::getModel('directory/region')
                        ->loadByName($data[$prefix . 'region']);
                    if (!$region->getId()) {
                        return false;
                    }
                    unset($region);
                }
            }
            unset($data);
            return true;
        }
        return false;
    }

    /**
     * Prepare user for delete
     */
    protected function _beforeDelete()
    {
        $this->_protectFromNonAdmin();
        return parent::_beforeDelete();
    }

    /**
     * Get user created at date timestamp
     *
     * @return int|null
     */
    public function getCreatedAtTimestamp()
    {
        if ($date = $this->getCreatedAt()) {
            return Varien_Date::toTimestamp($date);
        }
        return null;
    }

    /**
     * Reset all model data
     *
     * @return Mage_User_Model_User
     */
    public function reset()
    {
        $this->setData(array());
        $this->setOrigData();
        $this->_attributes = null;

        return $this;
    }

    /**
     * Checks model is deleteable
     *
     * @return boolean
     */
    public function isDeleteable()
    {
        return $this->_isDeleteable;
    }

    /**
     * Set is deleteable flag
     *
     * @param boolean $value
     * @return Mage_User_Model_User
     */
    public function setIsDeleteable($value)
    {
        $this->_isDeleteable = (bool)$value;
        return $this;
    }

    /**
     * Checks model is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return $this->_isReadonly;
    }

    /**
     * Set is readonly flag
     *
     * @param boolean $value
     * @return Mage_User_Model_User
     */
    public function setIsReadonly($value)
    {
        $this->_isReadonly = (bool)$value;
        return $this;
    }

    /**
     * Check whether confirmation may be skipped when registering using certain email address
     *
     * @return bool
     */
    public function canSkipConfirmation()
    {
        return $this->getId() && $this->hasSkipConfirmationIfEmail()
            && strtolower($this->getSkipConfirmationIfEmail()) === strtolower($this->getEmail());
    }

    public function __clone()
    {
        $newAddressCollection = $this->getPrimaryAddresses();
        $newAddressCollection = array_merge($newAddressCollection, $this->getAdditionalAddresses());
        $this->setId(null);
        $this->cleanAllAddresses();
        foreach ($newAddressCollection as $address) {
            $this->addAddress(clone $address);
        }
    }

    /**
     * Return Entity Type instance
     *
     * @return Mage_Eav_Model_Entity_Type
     */
    public function getEntityType()
    {
        return $this->_getResource()->getEntityType();
    }

    /**
     * Return Entity Type ID
     *
     * @return int
     */
    public function getEntityTypeId()
    {
        $entityTypeId = $this->getData('entity_type_id');
        if (!$entityTypeId) {
            $entityTypeId = $this->getEntityType()->getId();
            $this->setData('entity_type_id', $entityTypeId);
        }
        return $entityTypeId;
    }

    /**
     * Get either first store ID from a set website or the provided as default
     *
     * @param int|string|null $storeId
     *
     * @return int
     */
    protected function _getWebsiteStoreId($defaultStoreId = null)
    {
        if ($this->getWebsiteId() != 0 && empty($defaultStoreId)) {
            $storeIds = Mage::app()->getWebsite($this->getWebsiteId())->getStoreIds();
            reset($storeIds);
            $defaultStoreId = current($storeIds);
        }
        return $defaultStoreId;
    }
}
