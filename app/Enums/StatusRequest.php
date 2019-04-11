<?php
namespace App\Enums;
use Zul3s\EnumPhp\Enum;

/**
 * UserRole enum
 *
 * @method static Simpson::HOMER()
 * @method static Simpson::MARGE()
 */
class StatusRequest extends Enum
{
   /**
    * @description('En attente')
    */
    const send  = 1;
    /**
    * @description('En cours de traitement')
    */
    const progressing  = 0;
    /**
    * @description('Acceptée')
    */
    const accept = 2; 
    /**
    * @description(' Refusée')
    */
    const refus =3;
    /**
    * @description('Traitée')
    */
    const done  = 4;

    public static function getEnumDescriptionByKey($key) {
        try {
            return StatusRequest::byKey($key)->getDescription();
        } catch (\InvalidArgumentException $th) {
            return $key;
        }
    }

    public static function getEnumDescriptionByValue($value) {
        try {
            return StatusRequest::byValue($value)->getDescription();
        } catch (\InvalidArgumentException $th) {
            return StatusRequest::byValue($value)->getKey();
        }
    }

    public static function getEnumDescriptions(){
        foreach(StatusRequest::getValues() as $key => $value){
            $descs[$key] = StatusRequest::getEnumDescriptionByKey($key);
        }
        return $descs;
    }


}
