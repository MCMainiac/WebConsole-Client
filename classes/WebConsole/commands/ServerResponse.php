<?php

namespace WebConsole\commands;

/**
 * Class ServerResponse
 * @package WebConsole\commands
 */
class ServerResponse {
	const UNDEFINED = -1;

	const QUIT = 0;
	const PONG = 1;
	const MC_COMMAND = 2;
	const ERROR = 3;

	private $value = ServerResponse::UNDEFINED;

	/**
	 * ServerResponse constructor.
	 *
	 * @param int $value MUST be a const of ServerResponse.
	 */
	private function __construct(int $value) {
		$this->value = $value;
	}

	/**
	 * @return int The ServerResponse.
	 */
	public function getValue(): int {
		return $this->value;
	}

	/**
	 * @return string The server response as an uppercase string.
	 */
	public function __toString(): string {
		switch ($this->value) {
			case ServerResponse::QUIT:
				return "quit";
			case ServerResponse::PONG:
				return "pong";
			case ServerResponse::MC_COMMAND:
				return "mc_command";
			case ServerResponse::ERROR:
				return "error";

			default:
			case ServerResponse::UNDEFINED:
				return "undefined";
		}
	}

	/**
	 * @param string $response The raw response string.
	 *
	 * @return \WebConsole\commands\ServerResponse The decoded response from the server.
	 */
	public static function fromString(string $response): ServerResponse {
		switch (strtolower($response)) {
			case "pong":
				$value = ServerResponse::PONG;
				break;
			case "quit":
				$value = ServerResponse::QUIT;
				break;
			case "mc_command":
				$value = ServerResponse::MC_COMMAND;
				break;
			case "error":
				$value = ServerResponse::ERROR;
				break;

			default:
			case "undefined":
				$value = ServerResponse::UNDEFINED;
				break;
		}

		return new ServerResponse($value);
	}
}
