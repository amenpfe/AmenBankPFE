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
    * @description('En attente d'acceptation par le propriétaire du métier)
    */
    const waiting  = 0;
    /**
    * @description('En cours de traitement par C.E.D')
    */
    const progressing_CED  = 1;
    /**
    * @description('En cours de traitement par la comité d'organisation d'étude)
    */
    const progressing_comite  = 2;
    /**
    * @description('En attente')
    */
    const send  = 3;
    /**
    * @description('En cours de traitement par la division des cahiers des charges')
    */
    const progressing_chd  = 4;
    /**
    * @description('Acceptée')
    */
    const accept = 4; 
    /**
    * @description('Refusée')
    */
    const refus =5;
    /**
    * @description('Traitée')
    */
    const done  = 6;
    /**
    * @description('En cours de traitement par le chef de division développement')
    */
    const progressing_div  = 7;
    /**
    * @description('En cours de traitement par les développeurs')
    */
    const progressing_devlop  = 8;
    /**
    * @description('En cours de traitement par la division architecture et intégration')
    */
    const progressing_archi  = 9;
    /**
    * @description('En cours de traitement par la division de la qualié des application')
    */
    const progressing_recette  = 10;
    /**
    * @description('En cours de mise en place par la division système')
    */
    const progressing_systeme  = 11;
    /**
    * @description('En cours de rédaction de circulaire par l'organisation informatique)
    */
    const progressing_circulaire  = 12;

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
