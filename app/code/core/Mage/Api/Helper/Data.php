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
 * @package     Mage_Api
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Web service api main helper
 *
 * @category   Mage
 * @package    Mage_Api
 * @author     Camilooframework Core Team <core@magentocommerce.com>
 */
class Mage_Api_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_API_WSI = 'api/config/compliance_wsi';

    /**
     * @return boolean
     */
    public function isComplianceWSI()
    {
        return Mage::getStoreConfig(self::XML_PATH_API_WSI);
    }

    /**
     * Go thru a WSI args array and turns it to correct state.
     *
     * @param Object $obj - Link to Object
     * @return Object
     */
    public function wsiArrayUnpacker(&$obj){
        if(is_object($obj)){

            $modifiedKeys = $this->clearWsiFootprints($obj);

            foreach( $obj as $key => $value ){
                if(is_object($value)){
                    $this->wsiArrayUnpacker($value);
                }
                if(is_array($value)){
                    foreach($value as &$val){
                        if(is_object($val)){
                            $this->wsiArrayUnpacker($val);
                        }
                    }
                }
            }
        }

        foreach($modifiedKeys as $arrKey){
            $this->assocativArrayUnpacker($obj->$arrKey);
        }
    }

    /**
     * Go thru mixed and turns it to a correct look.
     *
     * @param Mixed $mixed A link to variable that may contain associative array.
     */
    public function assocativArrayUnpacker(&$mixed){

            if(is_array($mixed)){
                $tmpArr = array();
                foreach($mixed as $key => $value){
                    if(is_object($value)){
                        $value = get_object_vars($value);
                        if(count($value) == 2 && isset($value['key']) && isset($value['value'])){
                            $tmpArr[$value['key']] = $value['value'];
                        }
                    }
                }
                if(count($tmpArr)){
                    $mixed = $tmpArr;
                }
            }

            if(is_object($mixed)){
                $numOfVals = count(get_object_vars($mixed));
                if($numOfVals == 2 && isset($mixed->key) && isset($mixed->value)){
                    $mixed = get_object_vars($mixed);
                    /*
                     * Processing an associative arrays.
                     * $mixed->key = '2'; $mixed->value = '3'; turns to array(2 => '3');
                     */
                    $mixed = array($mixed['key'] => $mixed['value']);
                }
            }
    }

    /**
     * Corrects data representation.
     *
     * @param Object $obj - Link to Object
     * @return Object
     */
    public function clearWsiFootprints(&$obj){
        $modifiedKeys = array();

        $objectKeys = array_keys(get_object_vars($obj));

        foreach( $objectKeys as $key ){
            if(is_object($obj->$key) && isset($obj->$key->complexObjectArray) ){
                $obj->$key = $obj->$key->complexObjectArray;
                $modifiedKeys[] = $key;
            }
        }
        return $modifiedKeys;
    }

    /**
     * For the WSI, generates an response object.
     *
     * @param Object $arr - Link to Object
     * @return Object
     */
    public function wsiArrayPacker($mixed){
        if(is_array($mixed)){
            $arrKeys = array_keys($mixed);
            $isDigit = false;
            $isString = false;
            foreach($arrKeys as $key){
                if(is_int($key)){
                    $isDigit = true;
                    break;
                }
            }
            if($isDigit){
                $mixed = $this->packArrayToObjec($mixed);
            } else {
                $mixed = (object)$mixed;
            }
        }
        if(is_object($mixed) && isset($mixed->complexObjectArray)){
            foreach($mixed->complexObjectArray as $k => $v){
                $mixed->complexObjectArray[$k] = $this->wsiArrayPacker($v);
            }
        }
        return $mixed;
    }

    /**
     * For response to the WSI, generates an object from array.
     *
     * @param Array $arr - Link to Object
     * @return Object
     */
    public function packArrayToObjec(Array $arr){
        $obj = new stdClass();
        $obj->complexObjectArray = $arr;
        return $obj;
    }
} // Class Mage_Api_Helper_Data End
