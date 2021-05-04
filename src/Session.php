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
    public function setProperty(string $prop, mixed $content):bool {
        if (str_starts_with($prop, '__')) return false;
        $_SESSION[$this->prefix][$prop] = $content;
        return true;
    }

    private function setAuth():void {
        $_SESSION[$this->prefix]['__auth'] = true;
    }

    public function setLoggedin(string $flag = 'u'):void {
        switch ($flag) {
            case Group::getSuperadminFlag():
                $this->setSuperadmin();
                break;
            case Group::getAdminFlag():
                $this->setAdmin();
                break;
            case Group::getEditorFlag():
                $this->setEditor();
                break;
            case Group::getWriterFlag():
                $this->setWriter();
                break;
            case Group::getContributorFlag():
                $this->setContributor();
                break;
            case Group::getUserFlag():
            default:
                $this->setUser();
                break;
            case Group::getGuestFlag():
                $this->setGuest();
                break;
        }
    }

    public function setSuperadmin():void {
        $this->setAuth();
        $_SESSION[$this->prefix]['__group_id'] = Group::getSuperadminID();
        $_SESSION[$this->prefix]['__group_flag'] = Group::getSuperadminFlag();
        $_SESSION[$this->prefix]['__group_name'] = Group::getSuperadminName();
    }

    public function setAdmin():void {
        $this->setAuth();
        $_SESSION[$this->prefix]['__group_id'] = Group::getAdminID();
        $_SESSION[$this->prefix]['__group_flag'] = Group::getAdminFlag();
        $_SESSION[$this->prefix]['__group_name'] = Group::getAdminName();
    }

    public function setEditor():void {
        $this->setAuth();
        $_SESSION[$this->prefix]['__group_id'] = Group::getEditorID();
        $_SESSION[$this->prefix]['__group_flag'] = Group::getEditorFlag();
        $_SESSION[$this->prefix]['__group_name'] = Group::getEditorName();
    }

    public function setWriter():void {
        $this->setAuth();
        $_SESSION[$this->prefix]['__group_id'] = Group::getWriterID();
        $_SESSION[$this->prefix]['__group_flag'] = Group::getWriterFlag();
        $_SESSION[$this->prefix]['__group_name'] = Group::getWriterName();
    }

    public function setContributor():void {
        $this->setAuth();
        $_SESSION[$this->prefix]['__group_id'] = Group::getContributorID();
        $_SESSION[$this->prefix]['__group_flag'] = Group::getContributorFlag();
        $_SESSION[$this->prefix]['__group_name'] = Group::getContributorName();
    }

    public function setUser():void {
        $this->setAuth();
        $_SESSION[$this->prefix]['__group_id'] = Group::getUserID();
        $_SESSION[$this->prefix]['__group_flag'] = Group::getUserFlag();
        $_SESSION[$this->prefix]['__group_name'] = Group::getUserName();
    }

    public function setGuest():void {
        $this->setAuth();
        $_SESSION[$this->prefix]['__group_id'] = Group::getGuestID();
        $_SESSION[$this->prefix]['__group_flag'] = Group::getGuestFlag();
        $_SESSION[$this->prefix]['__group_name'] = Group::getGuestName();
    }

    public function getProperty(string $prop):mixed {
        return $_SESSION[$this->prefix][$prop];
    }

    public function getGroupID():int|false {
        if (!$this->isLoggedin()) return false;
        return $_SESSION[$this->prefix]['__group_id'];
    }

    public function getGroupFlag():string|false {
        if (!$this->isLoggedin()) return false;
        return $_SESSION[$this->prefix]['__group_flag'];
    }

    public function getGroupName():int|false {
        if (!$this->isLoggedin()) return false;
        return $_SESSION[$this->prefix]['__group_name'];
    }

    public function isLoggedin():bool {
        return (isset($_SESSION[$this->prefix]['__auth']) && $_SESSION[$this->prefix]['__auth'] == true && isset($_SESSION[$this->prefix]['__group_id']) && isset($_SESSION[$this->prefix]['__group_flag']) && isset($_SESSION[$this->prefix]['__group_name'])) ? true : false;
    }

    public function minSuperadmin():bool {
        return ($this->isLoggedin() && Group::checkAccessByFlag($_SESSION[$this->prefix]['__group_flag'], Group::getSuperadminFlag())) ? true : false;
    }

    public function isSuperadmin():bool {
        return ($this->isLoggedin() && $_SESSION[$this->prefix]['__group_flag'] == Group::getSuperadminFlag()) ? true : false;
    }

    public function minAdmin():bool {
        return ($this->isLoggedin() && Group::checkAccessByFlag($_SESSION[$this->prefix]['__group_flag'], Group::getAdminFlag())) ? true : false;
    }

    public function isAdmin():bool {
        return ($this->isLoggedin() && $_SESSION[$this->prefix]['__group_flag'] == Group::getAdminFlag()) ? true : false;
    }

    public function minEditor():bool {
        return ($this->isLoggedin() && Group::checkAccessByFlag($_SESSION[$this->prefix]['__group_flag'], Group::getEditorFlag())) ? true : false;
    }

    public function isEditor():bool {
        return ($this->isLoggedin() && $_SESSION[$this->prefix]['__group_flag'] == Group::getEditorFlag()) ? true : false;
    }

    public function minWriter():bool {
        return ($this->isLoggedin() && Group::checkAccessByFlag($_SESSION[$this->prefix]['__group_flag'], Group::getWriterFlag())) ? true : false;
    }

    public function isWriter():bool {
        return ($this->isLoggedin() && $_SESSION[$this->prefix]['__group_flag'] == Group::getWriterFlag()) ? true : false;
    }

    public function minContributor():bool {
        return ($this->isLoggedin() && Group::checkAccessByFlag($_SESSION[$this->prefix]['__group_flag'], Group::getContributorFlag())) ? true : false;
    }

    public function isContributor():bool {
        return ($this->isLoggedin() && $_SESSION[$this->prefix]['__group_flag'] == Group::getContributorFlag()) ? true : false;
    }

    public function minUser():bool {
        return ($this->isLoggedin() && Group::checkAccessByFlag($_SESSION[$this->prefix]['__group_flag'], Group::getUserFlag())) ? true : false;
    }

    public function isUser():bool {
        return ($this->isLoggedin() && $_SESSION[$this->prefix]['__group_flag'] == Group::getUserFlag()) ? true : false;
    }

    public function minGuest():bool {
        return ($this->isLoggedin() && Group::checkAccessByFlag($_SESSION[$this->prefix]['__group_flag'], Group::getGuestFlag())) ? true : false;
    }

    public function isGuest():bool {
        return ($this->isLoggedin() && $_SESSION[$this->prefix]['__group_flag'] == Group::getGuestFlag()) ? true : false;
    }

}
?>