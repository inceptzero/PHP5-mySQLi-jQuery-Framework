<?php

define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
if (!IS_AJAX) {
        die('Restricted access');
}
$pos = strpos($_SERVER['HTTP_REFERER'], getenv('HTTP_HOST'));
if ($pos === false) {
        die('Restricted access');
}

$cfgload = substr($_SERVER['DOCUMENT_ROOT'], 0, strrpos($_SERVER['DOCUMENT_ROOT'], '/')).'load.php';
include($cfgload);

$args = array(
    'rval' => array(
        'filter' => FILTER_SANITIZE_STRING,
        'flags' => FILTER_REQUIRE_SCALAR
    ),
    'eid' => array(
        'filter' => FILTER_VALIDATE_INT,
        'flags' => FILTER_REQUIRE_SCALAR
    ),
    'sid' => array(
        'filter' => FILTER_VALIDATE_INT,
        'flags' => FILTER_REQUIRE_SCALAR
    )
);

$post = filter_input_array(INPUT_POST, $args);

if ($post) {
        
        
        $response = array('status' => 'success');
        echo json_encode($response); exit;
}

$response = array('status' => 'error: failed validation');
echo json_encode($response); exit;
