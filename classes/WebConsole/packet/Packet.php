<?php

namespace WebConsole\packet;

/**
 * Class Packet
 * @package WebConsole\packet
 */
abstract class Packet {
	const ARGUMENTS_DELIMITER = " ";
	const PACKET_ID_LENGTH = 4;
	const PACKET_COMMAND_PAYLOAD_LENGTH = 4;
	const PACKET_ARGUMENTS_PAYLOAD_LENGTH = 4;
	const MIN_PACKET_SIZE =
		Packet::PACKET_ID_LENGTH +
		Packet::PACKET_COMMAND_PAYLOAD_LENGTH +
		Packet::PACKET_ARGUMENTS_PAYLOAD_LENGTH;
	const MAX_PACKET_SIZE =
		Packet::MIN_PACKET_SIZE +
		65536 + // 16^PACKET_COMMAND_PAYLOAD_LENGTH
		65536; // 16^PACKET_ARGUMENTS_PAYLOAD_LENGTH

	protected $id = -1;
	protected $commandString = "";
	protected $arguments = array();

	public static function getPacketEnd(): string {
		return mb_convert_encoding('&#x7f;', "ASCII", 'HTML-ENTITIES');
	}

	/**
	 * @return int The unique id of this packet.
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @return string The raw command string from the packet.
	 */
	public function getCommandString(): string {
		return $this->commandString;
	}

	/**
	 * @return array An array containing the arguments.
	 */
	public function getArguments(): array {
		return $this->arguments;
	}

	/**
	 * @return bool True when the size of the arguments is greater than 0.
	 */
	public function hasArguments(): bool {
		assert($this->arguments != null && is_array($this->arguments));

		return count($this->arguments) > 0;
	}
}
