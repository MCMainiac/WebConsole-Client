<?php
require __DIR__."/methods/autoload.php";

use WebConsole\utils\Address;
use WebConsole\Channel;
use WebConsole\commands\ClientCommand;

$address = new Address("localhost", 1424);
$client = new Channel($address);
?>
<!DOCTYPE html>
<html>
<head>
	<title>WebConsole by MCMainiac</title>
</head>
<body>
<h1>WebConsole <small>by MCMainiac</small></h1>
	<pre><?php

        echo PHP_EOL;

        try {
	        $client->connect();

	        echo "Connected to server!" . PHP_EOL;
        } catch (Exception $e) {
            echo "There was an error connecting to the server (perhaps the server isn't running?):".PHP_EOL;
            echo $e->getMessage().PHP_EOL;
            echo "[Debug]: Thrown at " . $e->getFile() . ":" . $e->getLine();

            goto end;
        }

        if(isset($_POST['command'])) {
            $packet = $client->sendCommand(
                    new ClientCommand(ClientCommand::MC_COMMAND),
                array($_POST['command'])
            );

            if ($packet === false)
                $success = false;
            else
                $success = true;

            if ($success) {
                echo "<strong><u>Response:</u></strong>" . PHP_EOL;
                echo "<strong>Packet id:</strong> " . $packet->getId() . PHP_EOL;
                echo "<strong>Response string:</strong> " . $packet->getCommandString() . PHP_EOL;
                echo "<strong>Arguments (concatenated):</strong>" . PHP_EOL;
                echo implode(
                        \WebConsole\packet\Packet::ARGUMENTS_DELIMITER,
                    $packet->getArguments()
                );
            } else {
                echo "<strong>Failed to send packet!</strong>" . PHP_EOL;
            }
        }

        $client->disconnect();

        end:

        echo PHP_EOL;
    ?></pre>
    <br>
	<form action="./" method="post">
		Command: <input type="text" name="command" placeholder="enter a command to execute" /><br>
		<input type="submit" />
	</form>
</body>
</html>
