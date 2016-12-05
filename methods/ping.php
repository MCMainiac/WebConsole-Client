<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 05.12.2016
 * Time: 18:27
 */

require __DIR__."/autoload.php";
include __DIR__."/background.php";

$_SESSION['command'] = "ping";
$_SESSION['execute'] = true;
var_dump($_SESSION);

sleep(2);

$_SESSION['exit'] = true;

sleep(2);

var_dump($_SESSION);

exit;