<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 24.11.2016
 * Time: 00:55
 */

namespace WebConsole\packet;

use WebConsole\commands\ClientCommand;

/**
 * Class ClientPacket
 * @package WebConsole\packet
 */
class ClientPacket extends Packet {
	private $command;

	/**
	 * ClientPacket constructor.
	 *
	 * @param int                                $id        The id of the packet. Should be unique.
	 * @param \WebConsole\commands\ClientCommand $command   The command included in the packet.
	 * @param array                              $arguments Optional array of strings representing the arguments.
	 */
	public function __construct(int $id, ClientCommand $command, array $arguments = array()) {
		$this->id = $id;
		$this->command = $command;
		$this->commandString = $command->__toString();
		$this->arguments = $arguments;
	}

	/**
	 * @return string A string representation of this packet.
	 */
	public function __toString(): string {
		return "ClientPacket [#" . $this->id . "] command=" . $this->command . "  arguments=[" . implode(", ", $this->arguments) . "]" . PHP_EOL;
	}
}
