<?php

require('load.php');

if (isset($_POST)) {
        require('control/posthandler.php');
}
require('control/index_ctrl.php');

$title = 'jQuery-Bootstrap Skeleton Framework';
$csslink = '<link rel="stylesheet" href="css/default.css">';
$jslink = '<script type="text/javascript" src="js/core.js"></script>';
define(JQUERY, 1);


include($cfg->get('inc').'header.php');
?>
<div id="contentWrapper">

    
   
        <form name="formDefault" id="formDefault" method="post" action="index.php">
           
        </form>

  
        
</div>

<?php 
    include($cfg->get('inc').'footer.php');
