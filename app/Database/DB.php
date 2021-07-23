<?php

/** 
 *  The original code is taken from a codecourse video (https://youtu.be/c_hNNAdyfQk).
 *  and I changed somethings from it.  
 */

namespace App\Database;

use PDO;
use App\Classes\Config;

class DB
{
    private static $_instance;
    private $_pdo;
    private $_query;
    private $_results;
    private $_count = 0;
    private $_error = false;

    public function __construct()
    {
        try {
            $this->_pdo = new PDO("mysql:host=localhost;dbname=". Config::get('database.name'),
                    Config::get('database.username'),
                    Config::get('database.password'));
        } catch (PDOException $e) {
            die('Connection faild. ' . $e->getMessage());
        }
    } 

    public function __destruct()
    {
        $this->_pdo = null;
    }

    public function getInstance()
    {
        if(!isset(self::$_instance)) {
            self::$_instance = new DB;
        }
        return self::$_instance;
    }

    public function query($sql, $params = [])
    {
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $i = 1;
            foreach ($params as $param) {
                $this->_query->bindValue($i, $param);
                $i++;
            }
            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            }else {
                $this->_error = true;
            }
        }
        return $this;
    }

    public function action($action, $table, $where = [])
    {
        if (count($where) === 3) {
            $operators = ['=', '>', '<', '>=', '<='];
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if (!$this->query($sql, [$value])->error()) {
                    return $this;
                }
            }
        }
        return $this;
    }

    public function find($table, $where)
    {
        return $this->action('SELECT *', $table, $where);
    }

    public function delete($table, $where)
    {
        return $this->action('DELETE', $table, $where);
    }

    public function insert($table, $fields)
    {
        if (count($fields)) {
            $i = 1;
            $keys = array_keys($fields);
            $values = '';
            foreach ($fields as $field) {
                $values .= '?';
                if ($i < count($fields)) {
                    $values .= ', ';
                }
                $i++;
            }

            $sql = "INSERT INTO {$table}(`". implode('`, `', $keys) ."`) VALUES({$values});";
            
            if(!$this->query($sql, $fields)->error()){
                return true;
            }
        }
        return false;
    }

    public function update($table, $fields, $where)
    {
        $i = 1;
        $set = 'SET';
        $field = $where[0];
        $fieldValue = $where[1];
        foreach ($fields as $key => $value) {
            $set .= " $key=?";
            if($i < count($fields)) {
                $set .= ', ';
            }
            $i++;
        }

        $sql = "UPDATE {$table} $set WHERE {$field}=?;";
        array_push($fields, $fieldValue);
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }

    public function get($table, $where = [])
    {
        $fields = [1];
        
        $sql = "SELECT * FROM {$table} WHERE ?;";
        
        if (count($where) > 1 && count($where) <= 3) {
            $field = $where[0];
            $fieldValue = $where[1];
            $sql = "SELECT * FROM {$table} WHERE {$field}=?;";    
            $fields = ['id' => $fieldValue];
        }

        if (!$this->query($sql, $fields)->error()) {
            return $this;
        }
        return $this;
    }

    public function error()
    {
        return $this->_error;
    }

    public function results()
    {
        return $this->_results;
    }

    public function first()
    {
        return $this->results()[0];
    }

    public function count()
    {
        return $this->_count;
    }
}