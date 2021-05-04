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

    public static function getNameByID(int $id):string|false {
        if (array_key_exists($id, self::LIST)) return self::LIST[$id][0];
        else return false;
    }

    public static function getFlagByID(int $id):string|false {
        if (array_key_exists($id, self::LIST)) return self::LIST[$id][1];
        else return false;
    }

    public static function getIDByName(string $name):int|false {
        foreach(self::LIST as $key=>$value) if ($value[0] == $name) return $key;
        return false;
    }

    public static function getIDByFlag(string $flag):int|false {
        foreach(self::LIST as $key=>$value) if ($value[1] == $flag) return $key;
        return false;
    }

    public static function getNameByFlag(string $flag):string|false {
        $id = self::getIDByFlag($flag);
        if ($id == false) return false;
        return self::getNameByID($id);
    }

    public static function getFlagByName(string $name):string|false {
        $id = self::getIDByName($name);
        if ($id == false) return false;
        return self::getFlagByID($id);
    }

    public static function checkAccessByID(int $id, int $min_id_level):bool {
        if ( $id >= $min_id_level ) return true;
        else return false;
    }

    public static function checkAccessByFlag($flag, $min_flag_level):bool {
        $id = self::getIDByFlag($flag);
        $min_id_level = self::getIDByFlag($min_flag_level);
        if (!is_int($id) || !is_int($min_id_level)) return false;
        return self::checkAccessByID($id, $min_id_level);
    }

    public static function checkAccessByName($name, $min_name_level):bool {
        $id = self::getIDByName($name);
        $min_id_level = self::getIDByName($min_name_level);
        if (!is_int($id) || !is_int($min_id_level)) return false;
        return self::checkAccessByID($id, $min_id_level);
    }

    public static function getSuperadminID():int {
        return 6;
    }

    public static function getSuperadminFlag():string {
        return self::LIST[6][1];
    }

    public static function getSuperadminName():string {
        return self::LIST[6][0];
    }

    public static function getAdminID():int {
        return 5;
    }

    public static function getAdminFlag():string {
        return self::LIST[5][1];
    }

    public static function getAdminName():string {
        return self::LIST[5][0];
    }

    public static function getEditorID():int {
        return 4;
    }

    public static function getEditorFlag():string {
        return self::LIST[4][1];
    }

    public static function getEditorName():string {
        return self::LIST[4][0];
    }

    public static function getWriterID():int {
        return 3;
    }

    public static function getWriterFlag():string {
        return self::LIST[3][1];
    }

    public static function getWriterName():string {
        return self::LIST[3][0];
    }

    public static function getContributorID():int {
        return 2;
    }

    public static function getContributorFlag():string {
        return self::LIST[2][1];
    }

    public static function getContributorName():string {
        return self::LIST[2][0];
    }

    public static function getUserID():int {
        return 1;
    }

    public static function getUserFlag():string {
        return self::LIST[1][1];
    }

    public static function getUserName():string {
        return self::LIST[1][0];
    }

    public static function getGuestID():int {
        return 0;
    }

    public static function getGuestFlag():string {
        return self::LIST[0][1];
    }

    public static function getGuestName():string {
        return self::LIST[0][0];
    }
}
?>