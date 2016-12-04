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
			if(isset($_POST['command'])) {
				$client->connect();

				#$client->ping(array($test));
				$client->sendCommand(new ClientCommand(ClientCommand::MC_COMMAND), array($_POST['command']));
				#$client->sendCommand(new ClientCommand(ClientCommand::MC_COMMAND), array("stop"));

				$client->disconnect();
				echo "<div id=\"success\">Command successfully executed on client!</div>";
			}
		?>
	</pre>
	<form action="./" method="post">
		Command: <input type="text" name="command" placeholder="enter a command to execute" /><br>
		<input type="submit" />
	</form>
</body>
</html>
