<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 23.11.2016
 * Time: 14:31
 */

namespace WebConsole;

/**
 * Class Client
 * @package WebConsole
 */
class Channel {
	private $socket = null;
	private $inputReader = null;
	private $outputPrinter = null;

	private $packetCount = 0;

	/**
	 * Channel constructor.
	 *
	 * @param \WebConsole\Address $address The address and the port of the WebConsole server.
	 */
    public function __construct(Address $address) {
		error_reporting(0);
		if(AF_INET == "AF_INET") {
			echo("<h1>You need to enable php_sockets in php.ini (on line 896)</h1>\n");
			echo("<h3>To do that you need to enter into the php directory");
			die("and modify <code>php.ini</code> (you might also need to restart apache).</h3>");
		}
		error_reporting(E_ALL);
		
		$this->socket = new ClientSocket($address, AF_INET, SOCK_STREAM, SOL_TCP);
		$this->inputReader = new InputDecoder($this->socket);
		$this->outputPrinter = new OutputEncoder($this->socket);
    }

	/**
	 * Connect to the WebConsole server.
	 *
	 * @throws \Exception If an error occurs when connecting to the socket.
	 */
    public function connect() {
    	$this->socket->connect();
    }

	/**
	 * Disconnect from the server.
	 *
	 * @param callable|null $callback A callback to call when the quit command finished executing.
	 *
	 * @throws \Exception If the QUIT-command could not be sent.
	 */
	public function disconnect(callable $callback = null) {
    	$quitPacket = $this->sendCommand(new ClientCommand(ClientCommand::QUIT));

		if ($quitPacket === false)
			throw new \Exception("Failed to send QUIT command!");

		// close connection to socket
		$this->socket->close();

		// if callback is set, invoke it with the quit packet as argument
		if ($callback != null)
			$callback($quitPacket);
	}

	/**
	 * <p>Ping-Pong!</p>
	 * <p>Is equivalent to sendCommand(ClientCommand::PING, $extra)</p>
	 *
	 * @param array $extra Extra arguments that will be returned from the server.
	 *
	 * @return bool|\WebConsole\ServerPacket Either false (if send fails) or the response packet.
	 */
	public function ping(array $extra = array()) {
		$command = new ClientCommand(ClientCommand::PING);
		$response = $this->sendCommand($command, $extra);

		return $response;
	}

	/**
	 * Send a command to the server.
	 *
	 * @param \WebConsole\ClientCommand $command The command to send to the WebConsole server.
	 * @param array                     $args    Optional array of string arguments.
	 *
	 * @return bool|\WebConsole\ServerPacket Either false (if sending fails) or the response packet of the server.
	 */
	public function sendCommand(ClientCommand $command, array $args = array()) {
    	$packet = new ClientPacket($this->packetCount++, $command, $args);

    	$success = $this->outputPrinter->writePacket($packet);

		if (!$success)
			return false;

		$responsePacket = $this->inputReader->readPacket();

		return $responsePacket;
	}
}
