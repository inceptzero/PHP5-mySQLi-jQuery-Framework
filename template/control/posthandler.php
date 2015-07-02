<?php
/*
$args = array(
    'inputBase'  => 
          array('filter'    => 
               FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                               'flags'     => FILTER_FLAG_NO_ENCODE_QUOTES, 
        ),
    'inputFile'   => 
        array('filter'    => 
               FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                               'flags'     => FILTER_FLAG_NO_ENCODE_QUOTES, 
       ),
    'inputSchema'   => 
        array('filter'    => 
               FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                               'flags'     => FILTER_FLAG_NO_ENCODE_QUOTES, 
        ),
    'inputStart'   => 
        array('filter'    => 
               FILTER_VALIDATE_INT
        ),
    'inputStop'   => 
        array('filter'    => 
               FILTER_VALIDATE_INT
        ),
);

$post = filter_input_array(INPUT_POST, $args);

if ($post) {


}

