<?php

class xml extends sanders {

        protected $_xml;

        function __construct($f = 'iz-cfg.xml') {
                parent::__construct();
                $this->set('defaultXmlFilename', $f);
                $this->set('configPath', '/myserver/iz-cfg/');
        }

        function __destruct() {
                parent::__destruct();
        }

        public function getXml($f = null) {
             
                $f = $f === null ? $this->get('defaultXmlFilename') : $f;
                $tf = $this->get('configPath').$f;
                if (file_exists($tf)) { 
                        $r = simplexml_load_file($tf);
                        return $r;
                } 
        }
}
