<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 25.11.2016
 * Time: 15:51
 */

namespace WebConsole\utils;

use WebConsole\commands\ClientCommand;
use WebConsole\packet\Packet;

/**
 * Class ClientSocket
 * @package WebConsole\utils
 */
class ClientSocket {
	const MAX_BYTES = Packet::MAX_PACKET_SIZE;
	const FLAGS = 0;

	const DOMAIN = AF_INET;
	const TYPE = SOCK_STREAM;
	const PROTOCOL = SOL_TCP;

	private $resource = null;
	private $address = null;

	/**
	 * ClientSocket constructor.
	 *
	 * @param Address $address  The address and port to connect to.
	 *
	 * @throws \Exception If there is an error creating the socket.
	 */
	public function __construct(Address $address) {
		$this->address = $address;
		$this->resource = socket_create(ClientSocket::DOMAIN, ClientSocket::TYPE, ClientSocket::PROTOCOL);

		if ($this->resource === false)
			throw new \Exception(socket_strerror(socket_last_error()));
	}

	/**
	 * Connect to the socket.
	 *
	 * @return bool True if the connection is established.
	 * @throws \Exception If there is an error connecting to the socket.
	 */
	public function connect(): bool {
		$success = @socket_connect(
			$this->resource,
			$this->address->getHost(),
			$this->address->getPort()
		);

		socket_setopt($this->resource, SOL_SOCKET, SO_KEEPALIVE, true);
		socket_setopt($this->resource, SOL_SOCKET, TCP_NODELAY, true);

		if ($success === false)
			throw new \Exception(socket_strerror(socket_last_error()));

		return true;
	}

	/**
	 * Send a binary string to the connected socket.
	 *
	 * @param string $string The binary string.
	 *
	 * @return bool True if the string was sent successfully.
	 */
	public function sendBinaryString(string $string): bool {
		$bytes = strlen($string);
		$bytesSent = socket_send(
			$this->resource,
			$string,
			$bytes,
			ClientSocket::FLAGS
		);

		$success = $bytesSent !== false && $bytesSent = $bytes;

		return $success;
	}

	/**
	 * @return bool|string Either false (if no data has been received) or the received data.
	 */
	public function readBinaryString() {
		$string = "";
		socket_recv(
			$this->resource,
			$string,
			ClientSocket::MAX_BYTES,
			ClientSocket::FLAGS
		);

		return $string != null ? $string : false;
	}

	/**
	 * Close the connection to the socket.
	 */
	public function close() {
		socket_shutdown($this->resource, 2);
		socket_close($this->resource);

		$this->resource = null;
	}
}
