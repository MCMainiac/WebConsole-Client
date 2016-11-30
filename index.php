<?php
set_time_limit(30);

require_once __DIR__."/autoload.php";

use WebConsole\Address;
use WebConsole\Channel;
use WebConsole\ClientCommand;

$address = new Address("localhost", 1424);
$client = new Channel($address);
$test = "Dies ist ein Test!";
?>
<!DOCTYPE html>
<html>
<head>
	<title>WebConsole by MCMainiac</title>
</head>
<body>
<h1>WebConsole <small>by MCMainiac</small></h1>
<pre>
<?php
	$client->connect();

	#$client->ping(array($test));
	$client->sendCommand(new ClientCommand(ClientCommand::MC_COMMAND), array("help"));
	#$client->sendCommand(new ClientCommand(ClientCommand::MC_COMMAND), array("stop"));

	$client->disconnect();
?>
</pre>
</body>
</html>
