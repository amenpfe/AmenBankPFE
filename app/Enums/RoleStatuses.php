<?php
namespace App\Enums;
use Zul3s\EnumPhp\Enum;

/**
 * RoleStatuses enum
 *
 */
class RoleStatuses extends Enum
{
    /**
     * @description('l'utilisateur')
     */
    const user = [3,15,5,6];

    /**
     * @description('la comité d'étude et de développement')
     */
    const CED = [1];

    /**
     * @description('le propriétaire du métier')
     */
    const proprietaire = [0];
    
    /**
     * @description('la comité d'organisation')
     */
    const comite = [2];
    
    /**
    * @description('le chef de division des cahiers des charges')
    */
    const ChefCD  = [4];
    
    /**
     * @description('le chef de division développement')
     */
    const dev_chef = [7, 13];
    
    /**
    * @description('le développeur')
    */
    const Developpeur = [8, 14];

    /**
    * @description('le chef de division architecture et intégration')
    */
    const ChefArchitectureIntegration  = [9];
    
    /**
     * @description('le chef de division de la qualité des applications')
    */
    const quality_chef = [10];
    
    /**
     * @description('le chef de division système')
     */
    const sys_chef = [11];
    
    /**
     * @description('l'organisation informatique')
     */
    const info = [12];

    public static function getEnumDescriptionByKey($key) {
        try {
            return RoleStatuses::byKey($key)->getDescription();
        } catch (\InvalidArgumentException $th) {
            return $key;
        }
    }

    public static function getEnumDescriptionByValue($value) {
        try {
            return RoleStatuses::byValue($value)->getDescription();
        } catch (\InvalidArgumentException $th) {
            return RoleStatuses::byValue($value)->getKey();
        }
    }

    public static function getEnumDescriptions(){
        foreach(RoleStatuses::getValues() as $key => $value){
            $descs[$key] = RoleStatuses::getEnumDescriptionByKey($key);
        }
        return $descs;
    }

    public static function getEnumDescriptionByRequestStatus($status) {
        foreach(RoleStatuses::getValues() as $key => $value) {
            if(in_array($status, $value)) {
                return RoleStatuses::getEnumDescriptionByKey($key);
            }
        }
        return '';
    }
}