<?php
$inc = '/myserver/iz-cfg/';
require($inc.'ltc_sanders.php');
require($inc.'iz-cfg.php');
require($inc.'iz-xml.php');

$xml = new xml('iz-cfg.xml');
$cfg = new cfg($xml->getXml());
$cfg->load('core');
$cfg->load('iz-db_');
$dbi = new dbi();
