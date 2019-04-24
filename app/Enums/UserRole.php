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
   /**
    * @description('Administrateur')
    */
    const Admin = 0;
    /**
    * @description('Utilisateur')
    */
    const User = 1;
    /**
     * @description('Propriétaire du métier')
     */
    const proprietaire = 2;
    /**
     * @description('Comité d'organisation)
    */
    const comite = 3;
    /**
    * @description('Comité d'étude et de développement')
    */
    const CED = 4;
    /**
    * @description('Développeur')
    */
    const Developpeur = 5; 
    /**
    * @description('Chef de division des cahiers des charges')
    */
    const ChefCD  = 7;
    /**
    * @description('Chef de division architecture et intégration')
    */
    const ChefArchitectureIntegration  = 8;
    /**
     * @description('Chef de division développement')
     */
    const dev_chef = 9;
    /**
     * @description('Chef de division de la qualité des applications')
    */
    const quality_chef = 10;
    /**
     * @description('Chef de division système')
     */
    const sys_chef = 11;
    /**
     * @description('Organisation informatique')
     */
    const info = 12;
    
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
