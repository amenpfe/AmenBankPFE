<?php
namespace App\Enums;
use Zul3s\EnumPhp\Enum;

/**
 * TypeRequest enum
 *
 * @method static Simpson::HOMER()
 * @method static Simpson::MARGE()
 */
class TypeRequest extends Enum
{
   /**
    * @description('Demande de modification')
    */
    const modification  = 0;
    /**
    * @description('Demande de corréction')
    */
    const correction  = 2;
    
    public static function getEnumDescriptionByKey($key) {
        try {
            return TypeRequest::byKey($key)->getDescription();
        } catch (\InvalidArgumentException $th) {
            return $key;
        }
    }

    public static function getEnumDescriptionByValue($value) {
        try {
            return TypeRequest::byValue($value)->getDescription();
        } catch (\InvalidArgumentException $th) {
            return TypeRequest::byValue($value)->getKey();
        }
    }

    public static function getEnumDescriptions(){
        foreach(TypeRequest::getValues() as $key => $value){
            $descs[$key] = TypeRequest::getEnumDescriptionByKey($key);
        }
        return $descs;
    }


}
