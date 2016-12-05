<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 05.12.2016
 * Time: 17:53
 */

set_time_limit(60);
ignore_user_abort(true);

session_start();

if (isset($_SESSION['background-script-running']) && $_SESSION['background-script-running'])
	return;

$_SESSION['background-script-running'] = true;
$_SESSION['exit'] = false;
$_SESSION['command'] = null;

while (!$_SESSION['exit']) {
	if ($_SESSION['command'] != null && $_SESSION['execute']) {
		// TODO: execute command
		echo "Executing " . $_SESSION['command'];

		$_SESSION['result'] = "success";

		$_SESSION['command'] = null;
		$_SESSION['execute'] = false;
	}

	sleep(1);
}

$_SESSION['background-script-running'] = false;

exit;