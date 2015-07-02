<?php

class dbi extends sanders {

        public $_db;
        public $_config;
        public $MYSQLI_LINK = FALSE;
        public $rows = FALSE;
        public $last_error = FALSE;
        public $last_query = FALSE;
        public $result = FALSE;
        public $last_id = FALSE;
        public $paramTypeArray = Array();
        public $paramBindArray = Array();

        public function __construct() {
                parent::__construct();
             
                $this->connect();
        }

        public function __destruct() {
                $this->close();
                parent::__destruct();
        }

        public function connect() {
             
               $this->MYSQLI_LINK = mysqli_connect($this->get('dbihost'), $this->get('dbiuser'), $this->get('dbipass'), $this->get('dbiname'));
        }

        public function close() {
                return mysqli_close($this->MYSQLI_LINK);
        }

        public function get_hash($p) {
                return password_hash($p, PASSWORD_BCRYPT, array("cost" => 10));
        }

        public function check_password($p, $h) {
                return (password_verify($p, $h)) ? true : false;
        }

        public function get_rndkey($length = 32) {
                $random_string="";
                 while(strlen($random_string)<$length && $length > 0) {
                         $randnum = mt_rand(0,61);
                         $random_string .= ($randnum < 10) ?
                                 chr($randnum+48) : ($randnum < 36 ? 
                                         chr($randnum+55) : $randnum+61);
                  }
                 return $random_string;
        }

        public function escape($value) {
                return mysqli_real_escape_string($this->MYSQLI_LINK, $value);
        }

        public function get_lastid() {
                return $this->MYSQLI_LINK->insert_id;
        }

        public function new_query() {

                $this->paramTypeArray = Array();
                $this->paramBindArray = Array();
                $this->result = FALSE;
        }

        public function add_param($t, $d) {

                $this->paramTypeArray[] = $t;
                $this->paramBindArray[] = $d;
        }

        /**
         * 
         * public function select($t, $d, $c)
         * 
         * @param object $t (table)
         * @param object $d (data)
         * @param object $c (condition)
         * @return boolean (true,false)
         * 
         * Select with Prepared Statements
         * Returns True on Query Success
         * Returns False on Query Fail
         * 
         * On Success: Sets $this->result
         * On Complete: Sets $this->last_query
         * On Fail: Sets $this->last_error
         * 
         */
        public function select($t, $d, $c) {

                $a_params = array();

                $param_type = '';
                $n = count($this->paramTypeArray);
                if ($n > 0) {

                        for ($i = 0; $i < $n; $i++) {
                                $param_type .= $this->paramTypeArray[$i];
                        }

                        $a_params[] = & $param_type;

                        for ($i = 0; $i < $n; $i++) {
                                $a_bind_params[] = $this->paramBindArray[$i];
                        }

                        for ($i = 0; $i < $n; $i++) {
                                $a_params[] = & $a_bind_params[$i];
                        }


                        $q = 'SELECT ' . $d . ' FROM ' . $t . ' WHERE ' . $c;

                        $s = $this->MYSQLI_LINK->prepare($q);
                } else {

                        //if no parameters are given, the query
                        //will not default to a "WHERE" statement
                        //if you need to use WHERE, you will need 
                        //to add it to the conditional parameter
                        $q = 'SELECT ' . $d . ' FROM ' . $t . ' ' . $c;
                        $s = $this->MYSQLI_LINK->prepare($q);
                }

                if ($s === false) {
                        trigger_error('Wrong SQL: ' . $q . ' Error: ' . $this->MYSQLI_LINK->errno . ' ' . $this->MYSQLI_LINK->error, E_USER_ERROR);
                }

                if ($n > 0) {
                        call_user_func_array(array($s, 'bind_param'), $a_params);
                }

                $s->execute();

                if (!$meta = $s->result_metadata()) {
                        throw new Exception($s->error);
                }

                // The data array
                $data = array();

                // The references array
                $refs = array();

                // Iterate over the fields and set a reference
                while ($name = $meta->fetch_field()) {
                        $refs[] = & $data[$name->name];
                }

                // Free the metadata result
                $meta->free_result();

                // Throw an exception if the result cannot be bound
                if (!call_user_func_array(array($s, 'bind_result'), $refs)) {
                        throw new Exception($s->error);
                }

                $i = 0;

                while ($s->fetch()) {
                        $results[$i] = array();
                        foreach ($data as $k => $v)
                                $results[$i][$k] = $v;
                        $i++;
                }

                $s->close();

                $this->last_query = $q;


                if (count($results) > 0) {

                        $this->result = $results;
                        return TRUE;
                } else {


                        $this->last_error = mysqli_error($this->MYSQLI_LINK);
                        return FALSE;
                }

                return FALSE;
        }

        /**
         * 
         * public function delete($t, $c)
         * 
         * @param object $t (table)
         * @param object $c (condition)
         * @return boolean (true/false)
         * 
         * Delete with Prepared Statements
         * Returns True on Query Success
         * Returns False on Query Fail
         * 
         * On Success: Sets $this->rows
         * On Complete: Sets $this->last_query
         * On Fail: Sets $this->last_error
         * 
         */
        public function delete($t, $c) {

                $a_params = array();

                $param_type = '';
                $n = count($this->paramTypeArray);
                for ($i = 0; $i < $n; $i++) {
                        $param_type .= $this->paramTypeArray[$i];
                }

                $a_params[] = & $param_type;

                for ($i = 0; $i < $n; $i++) {
                        $a_bind_params[] = $this->paramBindArray[$i];
                }

                for ($i = 0; $i < $n; $i++) {
                        $a_params[] = & $a_bind_params[$i];
                }

                $q = "delete from " . $t . " where " . $c;

                $s = $this->MYSQLI_LINK->prepare($q);

                $this->last_query = $q;

                if ($s === false) {
                        trigger_error('Wrong SQL: ' . $q . ' Error: ' . $this->MYSQLI_LINK->errno . ' ' . $this->MYSQLI_LINK->error, E_USER_ERROR);
                }

                call_user_func_array(array($s, 'bind_param'), $a_params);

                $s->execute();

                $count = $s->affected_rows;

                $s->close();

                if ($count > 0) {

                        $this->rows = $count;
                        return TRUE;
                } else {

                        $this->last_error = mysqli_error($this->MYSQLI_LINK);
                        return FALSE;
                }
        }

        /**
         * 
         * public function insert($t, $d)
         * 
         * @param object $t (table)
         * @param object $d (data)
         * @return boolean (true/false)
         * 
         * Insert with Prepared Statements
         * Returns True on Query Success
         * Returns False on Query Fail
         * 
         * On Success: Sets $this->rows, $this->last_id 
         * On Complete: Sets $this->last_query
         * On Fail: Sets $this->last_error
         * 
         */
        public function insert($t, $d) {


                $a_params = array();

                $param_type = '';
                $n = count($this->paramTypeArray);
                for ($i = 0; $i < $n; $i++) {
                        $param_type .= $this->paramTypeArray[$i];
                }

                $a_params[] = & $param_type;

                for ($i = 0; $i < $n; $i++) {
                        $a_bind_params[] = $this->paramBindArray[$i];
                }

                for ($i = 0; $i < $n; $i++) {
                        $a_params[] = & $a_bind_params[$i];
                }


                $query_cols = 'insert into ' . $t . ' (';
                $query_vals = 'values (';

                while (list($key, $value) = each($d)) {

                        $query_cols .= '`' . $value . '`' . ', ';

                        $query_vals .= '?, ';
                }

                $query_cols = substr($query_cols, 0, strlen($query_cols) - 2);

                $query_vals = substr($query_vals, 0, strlen($query_vals) - 2);

                $q = $query_cols . ') ' . $query_vals . ')';

                $this->last_query = $q;

                $s = $this->MYSQLI_LINK->prepare($q);

                if ($s === false) {

                        trigger_error('Wrong SQL: ' . $q . ' Error: ' . $this->MYSQLI_LINK->errno . ' ' . $this->MYSQLI_LINK->error, E_USER_ERROR);
                }

                call_user_func_array(array($s, 'bind_param'), $a_params);

                $s->execute();

                $count = $s->affected_rows;
                $this->last_id = $s->insert_id;
                $s->close();

                if ($count > 0) {

                        $this->rows = $count;
                        return TRUE;
                } else {

                        $this->last_error = mysqli_error($this->MYSQLI_LINK);
                        return FALSE;
                }
        }

        /**
         * 
         * public function update($t, $d, $c)
         * 
         * @param object $t (table)
         * @param object $d (data)
         * @param object $c (condition)
         * @return boolean (true/false)
         * 
         * Update with Prepared Statements
         * Returns True on Query Success
         * Returns False on Query Fail
         * 
         * On Success: Sets $this->rows 
         * On Complete: Sets $this->last_query
         * On Fail: Sets $this->last_error
         * 
         */
        public function update($t, $d, $c, $r = FALSE) {

                $a_params = array();

                $param_type = '';
                $n = count($this->paramTypeArray);
                for ($i = 0; $i < $n; $i++) {
                        $param_type .= $this->paramTypeArray[$i];
                }

                $a_params[] = & $param_type;

                for ($i = 0; $i < $n; $i++) {
                        $a_bind_params[] = $this->paramBindArray[$i];
                }

                for ($i = 0; $i < $n; $i++) {
                        $a_params[] = & $a_bind_params[$i];
                }

                $q = 'update ' . $t . ' set ';

                if ($r === FALSE) {
                        while (list($key, $value) = each($d)) {
                                $q .= '`' . $value . '`' . ' = ?, ';
                        }
                } else {
                        while (list($key, $value) = each($d)) {
                                $q .= '`' . $value . '`' . ' = REPLACE(`' . $value . '`, ?, ?), ';
                        }
                }
                //strip comma off end of variable
                $q = substr($q, 0, strlen($q) - 2);

                $q .= ' where ' . $c;

                $this->last_query = $q;

                $s = $this->MYSQLI_LINK->prepare($q);

                if ($s === false) {
                        trigger_error('Wrong SQL: ' . $q . ' Error: ' . $this->MYSQLI_LINK->errno . ' ' . $this->MYSQLI_LINK->error, E_USER_ERROR);
                }

                call_user_func_array(array($s, 'bind_param'), $a_params);

                $s->execute();

                $count = $s->affected_rows;

                $s->close();

                if ($count > 0) {

                        $this->rows = $count;
                        return TRUE;
                } else {

                        $this->last_error = mysqli_error($this->MYSQLI_LINK);
                        return FALSE;
                }
        }

}
