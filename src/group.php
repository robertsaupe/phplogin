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

namespace robertsaupe\login;

class group {

    public const list = array(
        0 => array('Guest', 'g'),
        1 => array('User', 'u'),
        2 => array('Contributor', 'c'),
        3 => array('Writer', 'w'),
        4 => array('Editor', 'e'),
        5 => array('Admin', 'a'),
        6 => array('Superadmin', 's')
    );

    public static function get_name_by_id(int $id):string|false {
        if (array_key_exists($id, self::list)) return self::list[$id][0];
        else return false;
    }

    public static function get_flag_by_id(int $id):string|false {
        if (array_key_exists($id, self::list)) return self::list[$id][1];
        else return false;
    }

    public static function get_id_by_name(string $name):int|false {
        foreach(self::list as $key=>$value) if ($value[0] == $name) return $key;
        return false;
    }

    public static function get_id_by_flag(string $flag):int|false {
        foreach(self::list as $key=>$value) if ($value[1] == $flag) return $key;
        return false;
    }

    public static function get_name_by_flag(string $flag):string|false {
        $id = self::get_id_by_flag($flag);
        if ($id == false) return false;
        return self::get_name_by_id($id);
    }

    public static function get_flag_by_name(string $name):string|false {
        $id = self::get_id_by_name($name);
        if ($id == false) return false;
        return self::get_flag_by_id($id);
    }

    public static function check_access_by_id(int $id, int $min_id_level):bool {
        if ( $id >= $min_id_level ) return true;
        else return false;
    }

    public static function check_access_by_flag($flag, $min_flag_level):bool {
        $id = self::get_id_by_flag($flag);
        $min_id_level = self::get_id_by_flag($min_flag_level);
        if (!is_int($id) || !is_int($min_id_level)) return false;
        return self::check_access_by_id($id, $min_id_level);
    }

    public static function check_access_by_name($name, $min_name_level):bool {
        $id = self::get_id_by_name($name);
        $min_id_level = self::get_id_by_name($min_name_level);
        if (!is_int($id) || !is_int($min_id_level)) return false;
        return self::check_access_by_id($id, $min_id_level);
    }

    public static function get_superadmin_id():int {
        return 6;
    }

    public static function get_superadmin_flag():string {
        return self::list[6][1];
    }

    public static function get_superadmin_name():string {
        return self::list[6][0];
    }

    public static function get_admin_id():int {
        return 5;
    }

    public static function get_admin_flag():string {
        return self::list[5][1];
    }

    public static function get_admin_name():string {
        return self::list[5][0];
    }

    public static function get_editor_id():int {
        return 4;
    }

    public static function get_editor_flag():string {
        return self::list[4][1];
    }

    public static function get_editor_name():string {
        return self::list[4][0];
    }

    public static function get_writer_id():int {
        return 3;
    }

    public static function get_writer_flag():string {
        return self::list[3][1];
    }

    public static function get_writer_name():string {
        return self::list[3][0];
    }

    public static function get_contributor_id():int {
        return 2;
    }

    public static function get_contributor_flag():string {
        return self::list[2][1];
    }

    public static function get_contributor_name():string {
        return self::list[2][0];
    }

    public static function get_user_id():int {
        return 1;
    }

    public static function get_user_flag():string {
        return self::list[1][1];
    }

    public static function get_user_name():string {
        return self::list[1][0];
    }

    public static function get_guest_id():int {
        return 0;
    }

    public static function get_guest_flag():string {
        return self::list[0][1];
    }

    public static function get_guest_name():string {
        return self::list[0][0];
    }
}
?>