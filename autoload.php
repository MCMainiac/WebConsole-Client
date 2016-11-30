<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 23.11.2016
 * Time: 15:37
 */

define("ROOT", __DIR__."/");
define("CLASSES", ROOT."classes/");

spl_autoload_register(function ($className): bool {
	$classFile = CLASSES . str_replace("\\", "/", $className) . ".php";

	#echo "including: " . $classFile . "<br>\r\n";

	if (file_exists($classFile)) {
		/** @noinspection PhpIncludeInspection */
		require($classFile);

		return true;
	} else
		return false;
}, true);
