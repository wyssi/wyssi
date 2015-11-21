<?php

namespace Models;

use \PDO;

class Database {

    protected $db;
    private $caller;
    private $caller_class;

    function __construct() {
        $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_TABLE, DB_USER, DB_PASS, array(
            PDO::ATTR_PERSISTENT => true
        ));
    }

    private function getCaller() {
        $this->caller = debug_backtrace()[0];
        $this->caller_class = get_class($this->caller['object']);
    }

    function getAll($limit = false) {
        $this->getCaller();
        $q = 'SELECT * FROM ' . $this->caller['object']->table . (($limit) ? ' LIMIT ' . $limit : '');
        $query = $this->db->prepare($q);
        $query->execute();
        
        return $query->fetchAll(\PDO::FETCH_CLASS, $this->caller_class);
    }

    function getByID($id = null) {
        $this->getCaller();
        if (isset($id)) {
            $q = 'SELECT * FROM ' . $this->caller['object']->table . ' WHERE id=' . $id;
            $query = $this->db->prepare($q);
            $query->execute();
            return $query->fetchAll(\PDO::FETCH_CLASS, $this->caller_class);
        } else {
            return Array('error' => 'Please, provide ID!');
        }
    }

    public function getCustom($what = '*', $from = null, $where = '') {
        $this->getCaller();

        if (!isset($from))
            $from = $this->caller['object']->table;
        if (!isset($what))
            $what = '*';
        $q = 'SELECT ' . $what . ' FROM ' . $from . (isset($where) ? ' WHERE ' . $where : '');

        $query = $this->db->prepare($q);
        $query->execute();
        
        return  $query->fetchAll(\PDO::FETCH_CLASS, $this->caller_class);
    }

}
