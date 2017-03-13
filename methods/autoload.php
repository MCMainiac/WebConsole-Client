<?php if (defined("AUTOLOADED") && AUTOLOADED) return;

# Define constants
define("AUTOLOADED", true);
define("ROOT", dirname(__DIR__)."/");
define("CLASSES", ROOT."classes/");
define("METHODS", ROOT."methods/");

# create autoload function
spl_autoload_register(function ($className): bool {
	$classFile = CLASSES . str_replace("\\", "/", $className) . ".php";

	if (file_exists($classFile)) {
		/** @noinspection PhpIncludeInspection */
		require($classFile);

		return true;
	} else
		return false;
}, true);

/**
 * Decode a URL parameter.
 *
 * @param string $key     The key of the parameter
 * @param null   $default (optional) The default parameter, if the key does not exist
 *
 * @return mixed Default value (null) if the key doesn't exist in the params or the decoded parameter
 */
function decodeUrlParam(string $key, $default = null) {
	if (!in_array($key, $_GET))
		return $default;

	$value = $_GET[$key];

	return urldecode($value);
}

/**
 * This will execute $cmd in the background (no cmd window) without PHP waiting for it to finish, on both Windows and
 * Unix.
 *
 * @author Arno van den Brink
 *
 * @param string $cmd The command to be executed
 */
function execInBackground(string $cmd) {
	if (substr(php_uname(), 0, 7) == "Windows"){
		pclose(popen("start /B ". $cmd, "r"));
	} else {
		exec($cmd . " > /dev/null &");
	}
}