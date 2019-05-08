<?php
namespace App\Enums;
use Zul3s\EnumPhp\Enum;

/**
 * UserRole enum
 *
 * @method static Simpson::HOMER()
 * @method static Simpson::MARGE()
 */
class RequestTypes extends Enum
{
   /**
    * @description('Demande de modification')
    */
    const modification  = 0;
    /**
    * @description('Demande de corréction')
    */
    const correction  = 1;

    public static function getEnumDescriptionByKey($key) {
        try {
            return RequestTypes::byKey($key)->getDescription();
        } catch (\InvalidArgumentException $th) {
            return $key;
        }
    }

    public static function getEnumDescriptionByValue($value) {
        try {
            return RequestTypes::byValue($value)->getDescription();
        } catch (\InvalidArgumentException $th) {
            return RequestTypes::byValue($value)->getKey();
        }
    }

    public static function getEnumDescriptions(){
        foreach(RequestTypes::getValues() as $key => $value){
            $descs[$key] = RequestTypes::getEnumDescriptionByKey($key);
        }
        return $descs;
    }


}
