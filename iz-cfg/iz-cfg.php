<?php

class cfg extends sanders {

        function __construct($x = null) {
                parent::__construct();
                if ($x !== null) { $this->load_cfg($x); }
        }

        function __destruct() {
                parent::__destruct();
        }
     
        private function load_cfg($x) {
          
                foreach($x as $k => $v) {
                    
                        $this->set($k, $v);
                }    
         
        }
        
        public function load($m) {
                $r = $this->get('inc').$m.'.php';
                parent::load($r);
        }

}
