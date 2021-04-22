<?php
/**
 * phpLogin
 * 
 * Please report bugs on https://github.com/robertsaupe/phplogin/issues
 *
 * @author Robert Saupe <mail@robertsaupe.de>
 * @copyright Copyright (c) 2018, Robert Saupe. All rights reserved
 * @link https://github.com/robertsaupe/phplogin
 * @license MIT License
 */

namespace RobertSaupe\Login;

class Group {

    public const LIST = array(
        0 => array('Guest', 'g'),
        1 => array('User', 'u'),
        2 => array('Contributor', 'c'),
        3 => array('Writer', 'w'),
        4 => array('Editor', 'e'),
        5 => array('Admin', 'a'),
        6 => array('Superadmin', 's')
    );

    public static function Get_Name_by_ID(int $id):string|false {
        if (array_key_exists($id, self::LIST)) return self::LIST[$id][0];
        else return false;
    }

    public static function Get_Flag_by_ID(int $id):string|false {
        if (array_key_exists($id, self::LIST)) return self::LIST[$id][1];
        else return false;
    }

    public static function Get_ID_by_Name(string $name):int|false {
        foreach(self::LIST as $key=>$value) if ($value[0] == $name) return $key;
        return false;
    }

    public static function Get_ID_by_Flag(string $flag):int|false {
        foreach(self::LIST as $key=>$value) if ($value[1] == $flag) return $key;
        return false;
    }

    public static function Get_Name_by_Flag(string $flag):string|false {
        $id = self::Get_ID_by_Flag($flag);
        if ($id == false) return false;
        return self::Get_Name_by_ID($id);
    }

    public static function Get_Flag_by_Name(string $name):string|false {
        $id = self::Get_ID_by_Name($name);
        if ($id == false) return false;
        return self::Get_Flag_by_ID($id);
    }

    public static function Check_Access_by_ID(int $id, int $min_id_level):bool {
        if ( $id >= $min_id_level ) return true;
        else return false;
    }

    public static function Check_Access_by_Flag($flag, $min_flag_level):bool {
        $id = self::Get_ID_by_Flag($flag);
        $min_id_level = self::Get_ID_by_Flag($min_flag_level);
        if (!is_int($id) || !is_int($min_id_level)) return false;
        return self::Check_Access_by_ID($id, $min_id_level);
    }

    public static function Check_Access_by_Name($name, $min_name_level):bool {
        $id = self::Get_ID_by_Name($name);
        $min_id_level = self::Get_ID_by_Name($min_name_level);
        if (!is_int($id) || !is_int($min_id_level)) return false;
        return self::Check_Access_by_ID($id, $min_id_level);
    }

    public static function Get_Superadmin_ID():int {
        return 6;
    }

    public static function Get_Superadmin_Flag():string {
        return self::LIST[6][1];
    }

    public static function Get_Superadmin_Name():string {
        return self::LIST[6][0];
    }

    public static function Get_Admin_ID():int {
        return 5;
    }

    public static function Get_Admin_Flag():string {
        return self::LIST[5][1];
    }

    public static function Get_Admin_Name():string {
        return self::LIST[5][0];
    }

    public static function Get_Editor_ID():int {
        return 4;
    }

    public static function Get_Editor_Flag():string {
        return self::LIST[4][1];
    }

    public static function Get_Editor_Name():string {
        return self::LIST[4][0];
    }

    public static function Get_Writer_ID():int {
        return 3;
    }

    public static function Get_Writer_Flag():string {
        return self::LIST[3][1];
    }

    public static function Get_Writer_Name():string {
        return self::LIST[3][0];
    }

    public static function Get_Contributor_ID():int {
        return 2;
    }

    public static function Get_Contributor_Flag():string {
        return self::LIST[2][1];
    }

    public static function Get_Contributor_Name():string {
        return self::LIST[2][0];
    }

    public static function Get_User_ID():int {
        return 1;
    }

    public static function Get_User_Flag():string {
        return self::LIST[1][1];
    }

    public static function Get_User_Name():string {
        return self::LIST[1][0];
    }

    public static function Get_Guest_ID():int {
        return 0;
    }

    public static function Get_Guest_Flag():string {
        return self::LIST[0][1];
    }

    public static function Get_Guest_Name():string {
        return self::LIST[0][0];
    }
}
?>