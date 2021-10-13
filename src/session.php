<?php
/**
 * phpLogin
 * 
 * Please report bugs on https://github.com/robertsaupe/phplogin/issues
 *
 * @__author Robert Saupe <mail@robertsaupe.de>
 * @copyright Copyright (c) 2018, Robert Saupe. All rights reserved
 * @link https://github.com/robertsaupe/phplogin
 * @license MIT License
 */

namespace robertsaupe\login;

class session {

    public function __construct(private string $prefix, bool $overwrite = false, ?callable $callback = null) {
        $this->start($overwrite, $callback);
    }

    private function start(bool $overwrite, ?callable $callback):void {
        if (!headers_sent()) {
            if ($overwrite == true && isset($_GET['PHPSESSID'])) session_id($_GET['PHPSESSID']);
            if ($callback != null) call_user_func($callback);
            session_start();
        }
        if (empty($_SESSION[$this->prefix])) $_SESSION[$this->prefix] = array();
    }

    public function logout():void {
        $_SESSION[$this->prefix] = array();
    }

    /**
     * sets a session property
     *
     * @param string $prop (beginning with __ is reserved)
     * @param mixed $content
     * @return boolean
     */
    public function set_property(string $prop, mixed $content):bool {
        if (str_starts_with($prop, '__')) return false;
        $_SESSION[$this->prefix][$prop] = $content;
        return true;
    }

    private function set_auth():void {
        $_SESSION[$this->prefix]['__auth'] = true;
    }

    public function set_loggedin(string $flag = 'u'):void {
        switch ($flag) {
            case group::get_superadmin_flag():
                $this->set_superadmin();
                break;
            case group::get_admin_flag():
                $this->set_admin();
                break;
            case group::get_editor_flag():
                $this->set_editor();
                break;
            case group::get_writer_flag():
                $this->set_writer();
                break;
            case group::get_contributor_flag():
                $this->set_contributor();
                break;
            case group::get_user_flag():
            default:
                $this->set_user();
                break;
            case group::get_guest_flag():
                $this->set_guest();
                break;
        }
    }

    public function set_superadmin():void {
        $this->set_auth();
        $_SESSION[$this->prefix]['__group_id'] = group::get_superadmin_id();
        $_SESSION[$this->prefix]['__group_flag'] = group::get_superadmin_flag();
        $_SESSION[$this->prefix]['__group_name'] = group::get_superadmin_name();
    }

    public function set_admin():void {
        $this->set_auth();
        $_SESSION[$this->prefix]['__group_id'] = group::get_admin_id();
        $_SESSION[$this->prefix]['__group_flag'] = group::get_admin_flag();
        $_SESSION[$this->prefix]['__group_name'] = group::get_admin_name();
    }

    public function set_editor():void {
        $this->set_auth();
        $_SESSION[$this->prefix]['__group_id'] = group::get_editor_id();
        $_SESSION[$this->prefix]['__group_flag'] = group::get_editor_flag();
        $_SESSION[$this->prefix]['__group_name'] = group::get_editor_name();
    }

    public function set_writer():void {
        $this->set_auth();
        $_SESSION[$this->prefix]['__group_id'] = group::get_writer_id();
        $_SESSION[$this->prefix]['__group_flag'] = group::get_writer_flag();
        $_SESSION[$this->prefix]['__group_name'] = group::get_writer_name();
    }

    public function set_contributor():void {
        $this->set_auth();
        $_SESSION[$this->prefix]['__group_id'] = group::get_contributor_id();
        $_SESSION[$this->prefix]['__group_flag'] = group::get_contributor_flag();
        $_SESSION[$this->prefix]['__group_name'] = group::get_contributor_name();
    }

    public function set_user():void {
        $this->set_auth();
        $_SESSION[$this->prefix]['__group_id'] = group::get_user_id();
        $_SESSION[$this->prefix]['__group_flag'] = group::get_user_flag();
        $_SESSION[$this->prefix]['__group_name'] = group::get_user_name();
    }

    public function set_guest():void {
        $this->set_auth();
        $_SESSION[$this->prefix]['__group_id'] = group::get_guest_id();
        $_SESSION[$this->prefix]['__group_flag'] = group::get_guest_flag();
        $_SESSION[$this->prefix]['__group_name'] = group::get_guest_name();
    }

    public function get_property(string $prop):mixed {
        return $_SESSION[$this->prefix][$prop];
    }

    public function get_group_id():int|false {
        if (!$this->is_loggedin()) return false;
        return $_SESSION[$this->prefix]['__group_id'];
    }

    public function get_group_flag():string|false {
        if (!$this->is_loggedin()) return false;
        return $_SESSION[$this->prefix]['__group_flag'];
    }

    public function get_group_name():int|false {
        if (!$this->is_loggedin()) return false;
        return $_SESSION[$this->prefix]['__group_name'];
    }

    public function is_loggedin():bool {
        return (isset($_SESSION[$this->prefix]['__auth']) && $_SESSION[$this->prefix]['__auth'] == true && isset($_SESSION[$this->prefix]['__group_id']) && isset($_SESSION[$this->prefix]['__group_flag']) && isset($_SESSION[$this->prefix]['__group_name'])) ? true : false;
    }

    public function min_superadmin():bool {
        return ($this->is_loggedin() && group::check_access_by_flag($_SESSION[$this->prefix]['__group_flag'], group::get_superadmin_flag())) ? true : false;
    }

    public function is_superadmin():bool {
        return ($this->is_loggedin() && $_SESSION[$this->prefix]['__group_flag'] == group::get_superadmin_flag()) ? true : false;
    }

    public function min_admin():bool {
        return ($this->is_loggedin() && group::check_access_by_flag($_SESSION[$this->prefix]['__group_flag'], group::get_admin_flag())) ? true : false;
    }

    public function is_admin():bool {
        return ($this->is_loggedin() && $_SESSION[$this->prefix]['__group_flag'] == group::get_admin_flag()) ? true : false;
    }

    public function min_editor():bool {
        return ($this->is_loggedin() && group::check_access_by_flag($_SESSION[$this->prefix]['__group_flag'], group::get_editor_flag())) ? true : false;
    }

    public function is_editor():bool {
        return ($this->is_loggedin() && $_SESSION[$this->prefix]['__group_flag'] == group::get_editor_flag()) ? true : false;
    }

    public function min_writer():bool {
        return ($this->is_loggedin() && group::check_access_by_flag($_SESSION[$this->prefix]['__group_flag'], group::get_writer_flag())) ? true : false;
    }

    public function is_writer():bool {
        return ($this->is_loggedin() && $_SESSION[$this->prefix]['__group_flag'] == group::get_writer_flag()) ? true : false;
    }

    public function min_contributor():bool {
        return ($this->is_loggedin() && group::check_access_by_flag($_SESSION[$this->prefix]['__group_flag'], group::get_contributor_flag())) ? true : false;
    }

    public function is_contributor():bool {
        return ($this->is_loggedin() && $_SESSION[$this->prefix]['__group_flag'] == group::get_contributor_flag()) ? true : false;
    }

    public function min_user():bool {
        return ($this->is_loggedin() && group::check_access_by_flag($_SESSION[$this->prefix]['__group_flag'], group::get_user_flag())) ? true : false;
    }

    public function is_user():bool {
        return ($this->is_loggedin() && $_SESSION[$this->prefix]['__group_flag'] == group::get_user_flag()) ? true : false;
    }

    public function min_guest():bool {
        return ($this->is_loggedin() && group::check_access_by_flag($_SESSION[$this->prefix]['__group_flag'], group::get_guest_flag())) ? true : false;
    }

    public function is_guest():bool {
        return ($this->is_loggedin() && $_SESSION[$this->prefix]['__group_flag'] == group::get_guest_flag()) ? true : false;
    }

}
?>