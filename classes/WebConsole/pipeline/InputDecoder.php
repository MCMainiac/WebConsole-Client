<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 24.11.2016
 * Time: 00:56
 */

namespace WebConsole\pipeline;

use WebConsole\packet\ServerPacket;
use WebConsole\utils\ClientSocket;

/**
 * Class InputDecoder
 * @package WebConsole\pipeline
 */
class InputDecoder {
	private $socket;

	/**
	 * InputDecoder constructor.
	 *
	 * @param \WebConsole\Utils\ClientSocket $socket The socket to read from.
	 */
	public function __construct(ClientSocket $socket) {
		$this->socket = $socket;
	}

	/**
	 * Read a packet from the connected {@link ClientSocket}.
	 *
	 * @return bool|\WebConsole\packet\ServerPacket Either false (if reading fails) or the decoded packet.
	 */
	public function readPacket() {
		$binaryString = $this->socket->readBinaryString();

		if ($binaryString === false)
			return false;

		$packet = new ServerPacket($binaryString);

		# debug message
		#echo "Received: " . $packet;

		return $packet;
	}
}
