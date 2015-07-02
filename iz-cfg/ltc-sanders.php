<?php

class sanders {

        private static $_cfg = array();

        public function __construct() {
                
        }

        public function __destruct() {
                
        }

        public function get($item) {
                return self::$_cfg[$item];
        }

        public function set($item, $value) {
               self::$_cfg[$item] = $value;
        }
        
        public function load($m) {
          require($m);      
        }

}
