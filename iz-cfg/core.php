<?php

//core system functions

function izrand($length = 32) {
        
                $random_string="";
                while(strlen($random_string)<$length && $length > 0) {
                        $randnum = mt_rand(0,61);
                        $random_string .= ($randnum < 10) ?
                                chr($randnum+48) : ($randnum < 36 ? 
                                        chr($randnum+55) : $randnum+61);
                 }
                return $random_string;
}
