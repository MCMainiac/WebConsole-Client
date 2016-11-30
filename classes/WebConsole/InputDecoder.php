<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 24.11.2016
 * Time: 00:56
 */

namespace WebConsole;

/**
 * Class InputDecoder
 *
 * @package WebConsole
 */
class InputDecoder {
	private $socket;

	/**
	 * InputDecoder constructor.
	 *
	 * @param \WebConsole\ClientSocket $socket The socket to read from.
	 */
	public function __construct(ClientSocket $socket) {
		$this->socket = $socket;
	}

	/**
	 * Read a packet from the connected {@link ClientSocket}.
	 *
	 * @return bool|\WebConsole\ServerPacket Either false (if reading fails) or the decoded packet.
	 */
	public function readPacket() {
		$binaryString = $this->socket->readBinaryString();

		if ($binaryString === false)
			return false;

		$packet = new ServerPacket($binaryString);

		echo "Received: " . $packet;

		return $packet;
	}
}
