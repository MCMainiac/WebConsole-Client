<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 23.11.2016
 * Time: 14:53
 */

namespace WebConsole\utils;

/**
 * Class Address
 * @package WebConsole\utils
 */
class Address {
	private $host;
	private $port;

	/**
	 * Address constructor.
	 *
	 * @param string $host
	 * @param int    $port
	 */
	public function __construct(string $host, int $port) {
		$this->host = $host;
		$this->port = $port;
	}

	/**
	 * @return string
	 */
	public function getHost(): string {
		return $this->host;
	}

	/**
	 * @return int
	 */
	public function getPort(): int {
		return $this->port;
	}

	/**
	 * @param string $host
	 */
	public function setHost(string $host) {
		$this->host = $host;
	}

	/**
	 * @param int $port
	 */
	public function setPort(int $port) {
		$this->port = $port;
	}
}