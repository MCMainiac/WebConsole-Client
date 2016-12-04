<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 24.11.2016
 * Time: 00:55
 */

namespace WebConsole\packet;

use WebConsole\commands\ServerResponse;

/**
 * Class ServerPacket
 * @package WebConsole\packet
 */
class ServerPacket extends Packet {
	private $response;

	/**
	 * ServerPacket constructor.
	 *
	 * @param string $packet
	 *
	 * @throws \Exception If the packet has an invalid format.
	 */
	public function __construct(string $packet) {
		$length = strlen($packet);
		$minLength = Packet::MIN_PACKET_SIZE;

		if ($length < $minLength)
			throw new \Exception("Invalid packet size: " . $length . " bytes whereas minimal length is " . $minLength);

		$offset = 0;

		$idBytes = substr($packet, $offset, Packet::PACKET_ID_LENGTH);
		$this->id = $this->unpack_int32be($idBytes);
		$offset += Packet::PACKET_ID_LENGTH;

		$commandLengthBytes = substr($packet, $offset, Packet::PACKET_COMMAND_PAYLOAD_LENGTH);
		$responseLength = $this->unpack_int32be($commandLengthBytes);
		$offset += Packet::PACKET_COMMAND_PAYLOAD_LENGTH;

		$argumentsLengthBytes = substr($packet, $offset, Packet::PACKET_ARGUMENTS_PAYLOAD_LENGTH);
		$argumentsLength = $this->unpack_int32be($argumentsLengthBytes);
		$offset += Packet::PACKET_ARGUMENTS_PAYLOAD_LENGTH;

		$minLength += $responseLength;
		$minLength += $argumentsLength;
		if ($length < $minLength)
			throw new \Exception("Invalid packet size: " . $length . " bytes whereas minimal length is " . $minLength);

		$responseBytes = substr($packet, $offset, $responseLength);
		$offset += $responseLength;
		$this->commandString = $responseBytes;
		$this->response = ServerResponse::fromString($this->commandString);

		if ($argumentsLength > 0) {
			$argumentsBytes = substr($packet, $offset, $argumentsLength);
			$argumentsString = $argumentsBytes;
			$this->arguments = explode(Packet::ARGUMENTS_DELIMITER, $argumentsString);
		} else
			$this->arguments = array();
	}

	/**
	 * The unpack function for 32 bit integers in the big endian format.
	 *
	 * @param string $bytes The packed integer.
	 *
	 * @return int The unpacked integer.
	 */
	private function unpack_int32be(string $bytes): int {
		if (strlen($bytes) != 4)
			trigger_error("Invalid byte count for 32 bit integer: " . strlen($bytes), E_USER_ERROR);

		$bigEndian = pack("L", 1) == pack("N", 1);

		if (!$bigEndian)
			$bytes = strrev($bytes);

		$int = unpack("I", $bytes)[1];

		return $int;
	}

	/**
	 * @return \WebConsole\commands\ServerResponse The response included in the packet.
	 */
	public function getResponse(): ServerResponse {
		return $this->response;
	}

	/**
	 * @return string A string representing this server packet.
	 */
	public function __toString(): string {
		return "ServerPacket [#" . $this->id . "] response=" . $this->response . " arguments=[" . implode(", ", $this->arguments) ."]" . PHP_EOL;
	}
}
