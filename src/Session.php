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

namespace RobertSaupe\Login;

class Session {

    public function __construct(private string $prefix) {
        $this->start();
    }

    private function start():void {
        if (!headers_sent()) {
            if (isset($_GET['PHPSESSID'])) session_id($_GET['PHPSESSID']);
            session_start();
        }
        if (empty($_SESSION[$this->prefix])) $_SESSION[$this->prefix] = array();
    }

    public function Logout():void {
        $_SESSION[$this->prefix] = array();
    }

    /**
     * sets a session property
     *
     * @param string $prop (beginning with __ is reserved)
     * @param mixed $content
     * @return boolean
     */
    public function Set_Property(string $prop, mixed $content):bool {
        if (str_starts_with($prop, '__')) return false;
        $_SESSION[$this->prefix][$prop] = $content;
        return true;
    }

    private function set_auth():void {
        $_SESSION[$this->prefix]['__auth'] = true;
    }

    public function Set_Loggedin(string $flag = 'u'):void {
        switch ($flag) {
            case Group::Get_Superadmin_Flag():
                $this->Set_Superadmin();
                break;
            case Group::Get_Admin_Flag():
                $this->Set_Admin();
                break;
            case Group::Get_Editor_Flag():
                $this->Set_Editor();
                break;
            case Group::Get_Writer_Flag():
                $this->Set_Writer();
                break;
            case Group::Get_Contributor_Flag():
                $this->Set_Contributor();
                break;
            case Group::Get_User_Flag():
            default:
                $this->Set_User();
                break;
            case Group::Get_Guest_Flag():
                $this->Set_Guest();
                break;
        }
    }

    public function Set_Superadmin():void {
        $this->set_auth();
        $_SESSION[$this->prefix]['__group_id'] = Group::Get_Superadmin_ID();
        $_SESSION[$this->prefix]['__group_flag'] = Group::Get_Superadmin_Flag();
        $_SESSION[$this->prefix]['__group_name'] = Group::Get_Superadmin_Name();
    }

    public function Set_Admin():void {
        $this->set_auth();
        $_SESSION[$this->prefix]['__group_id'] = Group::Get_Admin_ID();
        $_SESSION[$this->prefix]['__group_flag'] = Group::Get_Admin_Flag();
        $_SESSION[$this->prefix]['__group_name'] = Group::Get_Admin_Name();
    }

    public function Set_Editor():void {
        $this->set_auth();
        $_SESSION[$this->prefix]['__group_id'] = Group::Get_Editor_ID();
        $_SESSION[$this->prefix]['__group_flag'] = Group::Get_Editor_Flag();
        $_SESSION[$this->prefix]['__group_name'] = Group::Get_Editor_Name();
    }

    public function Set_Writer():void {
        $this->set_auth();
        $_SESSION[$this->prefix]['__group_id'] = Group::Get_Writer_ID();
        $_SESSION[$this->prefix]['__group_flag'] = Group::Get_Writer_Flag();
        $_SESSION[$this->prefix]['__group_name'] = Group::Get_Writer_Name();
    }

    public function Set_Contributor():void {
        $this->set_auth();
        $_SESSION[$this->prefix]['__group_id'] = Group::Get_Contributor_ID();
        $_SESSION[$this->prefix]['__group_flag'] = Group::Get_Contributor_Flag();
        $_SESSION[$this->prefix]['__group_name'] = Group::Get_Contributor_Name();
    }

    public function Set_User():void {
        $this->set_auth();
        $_SESSION[$this->prefix]['__group_id'] = Group::Get_User_ID();
        $_SESSION[$this->prefix]['__group_flag'] = Group::Get_User_Flag();
        $_SESSION[$this->prefix]['__group_name'] = Group::Get_User_Name();
    }

    public function Set_Guest():void {
        $this->set_auth();
        $_SESSION[$this->prefix]['__group_id'] = Group::Get_Guest_ID();
        $_SESSION[$this->prefix]['__group_flag'] = Group::Get_Guest_Flag();
        $_SESSION[$this->prefix]['__group_name'] = Group::Get_Guest_Name();
    }

    public function Get_Property(string $prop):mixed {
        return $_SESSION[$this->prefix][$prop];
    }

    public function Get_Group_ID():int|false {
        if (!$this->Is_Loggedin()) return false;
        return $_SESSION[$this->prefix]['__group_id'];
    }

    public function Get_Group_Flag():string|false {
        if (!$this->Is_Loggedin()) return false;
        return $_SESSION[$this->prefix]['__group_flag'];
    }

    public function Get_Group_Name():int|false {
        if (!$this->Is_Loggedin()) return false;
        return $_SESSION[$this->prefix]['__group_name'];
    }

    public function Is_Loggedin():bool {
        return (isset($_SESSION[$this->prefix]['__auth']) && $_SESSION[$this->prefix]['__auth'] == true && isset($_SESSION[$this->prefix]['__group_id']) && isset($_SESSION[$this->prefix]['__group_flag']) && isset($_SESSION[$this->prefix]['__group_name'])) ? true : false;
    }

    public function Min_Superadmin():bool {
        return ($this->Is_Loggedin() && Group::Check_Access_by_Flag($_SESSION[$this->prefix]['__group_flag'], Group::Get_Superadmin_Flag())) ? true : false;
    }

    public function Is_Superadmin():bool {
        return ($this->Is_Loggedin() && $_SESSION[$this->prefix]['__group_flag'] == Group::Get_Superadmin_Flag()) ? true : false;
    }

    public function Min_Admin():bool {
        return ($this->Is_Loggedin() && Group::Check_Access_by_Flag($_SESSION[$this->prefix]['__group_flag'], Group::Get_Admin_Flag())) ? true : false;
    }

    public function Is_Admin():bool {
        return ($this->Is_Loggedin() && $_SESSION[$this->prefix]['__group_flag'] == Group::Get_Admin_Flag()) ? true : false;
    }

    public function Min_Editor():bool {
        return ($this->Is_Loggedin() && Group::Check_Access_by_Flag($_SESSION[$this->prefix]['__group_flag'], Group::Get_Editor_Flag())) ? true : false;
    }

    public function Is_Editor():bool {
        return ($this->Is_Loggedin() && $_SESSION[$this->prefix]['__group_flag'] == Group::Get_Editor_Flag()) ? true : false;
    }

    public function Min_Writer():bool {
        return ($this->Is_Loggedin() && Group::Check_Access_by_Flag($_SESSION[$this->prefix]['__group_flag'], Group::Get_Writer_Flag())) ? true : false;
    }

    public function Is_Writer():bool {
        return ($this->Is_Loggedin() && $_SESSION[$this->prefix]['__group_flag'] == Group::Get_Writer_Flag()) ? true : false;
    }

    public function Min_Contributor():bool {
        return ($this->Is_Loggedin() && Group::Check_Access_by_Flag($_SESSION[$this->prefix]['__group_flag'], Group::Get_Contributor_Flag())) ? true : false;
    }

    public function Is_Contributor():bool {
        return ($this->Is_Loggedin() && $_SESSION[$this->prefix]['__group_flag'] == Group::Get_Contributor_Flag()) ? true : false;
    }

    public function Min_User():bool {
        return ($this->Is_Loggedin() && Group::Check_Access_by_Flag($_SESSION[$this->prefix]['__group_flag'], Group::Get_User_Flag())) ? true : false;
    }

    public function Is_User():bool {
        return ($this->Is_Loggedin() && $_SESSION[$this->prefix]['__group_flag'] == Group::Get_User_Flag()) ? true : false;
    }

    public function Min_Guest():bool {
        return ($this->Is_Loggedin() && Group::Check_Access_by_Flag($_SESSION[$this->prefix]['__group_flag'], Group::Get_Guest_Flag())) ? true : false;
    }

    public function Is_Guest():bool {
        return ($this->Is_Loggedin() && $_SESSION[$this->prefix]['__group_flag'] == Group::Get_Guest_Flag()) ? true : false;
    }

}
?>