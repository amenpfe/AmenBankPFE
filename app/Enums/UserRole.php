<?php
namespace App\Enums;
use Zul3s\EnumPhp\Enum;

/**
 * UserRole enum
 *
 * @method static Simpson::HOMER()
 * @method static Simpson::MARGE()
 */
class UserRole extends Enum
{
   
    const Admin  = 0;
    /**
    * @description('Chef de division')
    */
    const Chefdedivision  = 1;
    const developpeur = 2;  

    public static function getEnumDescriptionByKey($key) {
        try {
            return UserRole::byKey($key)->getDescription();
        } catch (\InvalidArgumentException $th) {
            return $key;
        }
    }

    public static function getEnumDescriptionByValue($value) {
        try {
            return UserRole::byValue($value)->getDescription();
        } catch (\InvalidArgumentException $th) {
            return UserRole::byValue($value)->getKey();
        }
    }

    public static function getEnumDescriptions(){
        foreach(UserRole::getValues() as $key => $value){
            $descs[$key] = UserRole::getEnumDescriptionByKey($key);
        }
        return $descs;
    }


}
