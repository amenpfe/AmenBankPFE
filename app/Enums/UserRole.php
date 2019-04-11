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
    * @description('Chef de division')
    */
    const Chef = 2;
    /**
    * @description('Développeur')
    */
    const Developpeur = 3; 
    /**
    * @description('Propriétaire métier')
    */
    const ProprietaireMetier = 4;
    /**
    * @description('Chef de division des cahiers des charges')
    */
    const ChefCD  = 5;
    /**
    * @description('Chef de division architecture et intégration')
    */
    const ChefArchitectureIntegration  = 6;
    const CED  = 7;
    
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
