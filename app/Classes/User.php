<?php 

namespace App\Classes;

use App\Database\DB;
use App\Classes\Hash;
use App\Classes\Config;
use App\Classes\Cookie;
use App\Classes\Session;

class User
{
    private $_db;
    private $_data;
    private $_isLoggedIn = false;
    private $_cookieName;
    private $_sessionName;

    public function __construct($user = null)
    {
        $this->_sessionName = 'user';
        $this->_cookieName = 'hash';
        $this->_db = new DB;
        if (!$user) {
            if (Session::has($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                }
            }
        } else {
            $this->find($user);
        }
        $this->_db = DB::getInstance();
    }

    public function update($fields, $id = null)
    {
        if (!$id && $this->isLoggedIn()) {
            $id = $this->data()->id;
        }
        return $this->_db->update('users', $fields, ['id', $id]);
    }

    public function create($fields)
    {
        return $this->_db->insert('users', $fields);
    }

    public function register($fields)
    {
        if ($this->create($fields)) {
            $this->find($fields['username']);
            Session::put($this->_sessionName, $this->data()->id);
            return true;
        }
        return false;
    }

    public function find($user = null)
    {
        if ($user) {
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data = $this->_db->find('users', [$field, '=', $user]);
            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function login($username = null, $password = null, $remember = false)
    {
        if (!$username && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->id);
            $this->_isLoggedIn = true;
            return true;
        } else {
            $user = $this->find($username);
            if ($user) {
                if ($this->data()->password === Hash::make($password, $this->data()->salt)) {
                    Session::put($this->_sessionName, $this->data()->id);
                    if ($remember) {
                        $hash = Hash::unique();
                        $hashCheck = $this->_db->find('users_session', ['user_id', '=', $this->data()->id]);
                        if (!$hashCheck->count()) {
                            $this->_db->insert('users_session', [
                                'user_id' => $this->data()->id,
                                'hash' => $hash,
                            ]);
                        } else {
                            $hash = $hashCheck->first()->hash;
                        }
                        Cookie::put($this->_cookieName, $hash, Config::get('remember.cookie_expiry'));
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function exists()
    {
        return (!empty($this->_data)) ? true : false;
    }

    public function logout()
    {
        $this->_db->delete('users_session', ['user_id', '=', $this->data()->id]);
        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    public function data()
    {
        return $this->_data;
    }

    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }
}