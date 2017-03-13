<?php

namespace WebConsole\commands;

/**
 * Class ClientCommand
 * @package WebConsole\commands
 */
class ClientCommand {
	const UNDEFINED = -1;

	const QUIT = 0;
	const PING = 1;
	const MC_COMMAND = 2;

	private $value = ClientCommand::UNDEFINED;

	/**
	 * ClientCommand constructor.
	 *
	 * @param int $value MUST be a const of ClientCommand.
	 */
	public function __construct(int $value) {
		$this->value = $value;
	}

	/**
	 * @return string The lowercase version of the command.
	 */
	public function __toString(): string {
		switch ($this->value) {
			case ClientCommand::QUIT:
				return "quit";
			case ClientCommand::PING:
				return "ping";
			case ClientCommand::MC_COMMAND:
				return "mc_command";

			default:
			case ClientCommand::UNDEFINED:
				return "undefined";
		}
	}
}
