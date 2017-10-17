<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 25.11.2016
 * Time: 15:51
 */

namespace WebConsole;

/**
 * Class ClientSocket
 *
 * @package WebConsole
 */
class ClientSocket {
	const MAX_BYTES = Packet::MAX_PACKET_SIZE;
	const FLAGS = 0;

	private $resource = null;
	private $address = null;

	/**
	 * ClientSocket constructor.
	 *
	 * @param \WebConsole\Address $address  The address and port to connect to.
	 * @param int                 $domain   Specifies the protocol family to be used by the socket.
	 * @param int                 $type     Selects the type of communication to be used by the socket.
	 * @param int                 $protocol Sets the specific protocol within the specified domain to be used when communicating on the returned socket.
	 *
	 * @throws \Exception If there is an error creating the socket.
	 */
	public function __construct(Address $address, int $domain, int $type, int $protocol) {
		$this->address = $address;
		$this->resource = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

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
		$success = socket_connect(
			$this->resource,
			$this->address->getHost(),
			$this->address->getPort()
		);

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

		$success = $bytesSent !== false && $bytesSent == $bytes;

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
