<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 24.11.2016
 * Time: 00:56
 */

namespace WebConsole;

/**
 * Class OutputEncoder
 *
 * @package WebConsole
 */
class OutputEncoder {
	private $socket;

	public function __construct(ClientSocket $socket) {
		$this->socket = $socket;
	}

	/**
	 * Send an encoded {@link ClientPacket} over the {@link ClientSocket}.
	 *
	 * @param \WebConsole\ClientPacket $packet The packet to send
	 *
	 * @return bool True if the packet has been successfully sent.
	 */
	public function writePacket(ClientPacket $packet): bool {
		echo "Sending:  " . $packet;

		# gather information about the packet
		$commandString = $packet->getCommandString();
		$commandStringLength = strlen($commandString);
		$argumentsString = implode(Packet::ARGUMENTS_DELIMITER, $packet->getArguments());
		$argumentsStringLength = strlen($argumentsString);

		# pack the information into a binary string
		$binaryPacket  = $this->pack_int32be($packet->getId());
		$binaryPacket .= $this->pack_int32be($commandStringLength);
		$binaryPacket .= $this->pack_int32be($argumentsStringLength);
		$binaryPacket .= trim($commandString);
		if ($argumentsStringLength > 0)
			$binaryPacket .= trim($argumentsString);
		$binaryPacket .= Packet::getPacketEnd();

		# send the binary string over the socket connection
		return $this->socket->sendBinaryString($binaryPacket);
	}

	/**
	 * <p>
	 *  This method is the {@link pack()} method for unsigned 32bit integers with big endian.
	 * </p>
	 * <p>
	 *  Triggers an error, if the integer is out of range.
	 * </p>
	 *
	 * @param int $integer The integer to pack into a binary string.
	 *
	 * @return string The binary string.
	 */
	private function pack_int32be(int $integer): string {
		if ($integer < -2147483648 || $integer > 2147483647) {
			trigger_error("Out of bounds: " . $integer, E_USER_ERROR);
		}

		return pack('C4',
			($integer >> 24) & 0xFF,
			($integer >> 16) & 0xFF,
			($integer >>  8) & 0xFF,
			($integer >>  0) & 0xFF
		);
	}
}
