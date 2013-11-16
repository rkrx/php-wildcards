<?php
namespace Kir\StringUtils\Matching\Wildcards;

class Pattern {
	/**
	 * @var string
	 */
	private $pattern = null;

	/**
	 * @var string
	 */
	private $regEx = array();

	/**
	 * @param static
	 * @return static
	 */
	static public function create($pattern) {
		return new static($pattern);
	}

	/**
	 * @param string $pattern
	 */
	public function __construct($pattern) {
		$this->pattern = $pattern;
		$this->regEx = $this->convert($pattern);
	}

	/**
	 * @param string $string
	 * @return bool
	 */
	public function match($string) {
		if($string == $this->pattern) {
			return true;
		}
		return preg_match("/^{$this->regEx}$/", $string);
	}

	/**
	 * @param string $pattern
	 * @return array
	 */
	private function convert($pattern) {
		$pattern = preg_replace('/\\*+/', '*', $pattern);
		$parts = preg_split('/([\\?\\*])/', $pattern, -1, PREG_SPLIT_DELIM_CAPTURE);
		foreach($parts as &$part) {
			switch($part) {
				case '*':
					$part = '.*';
					break;
				case '?':
					$part = '.';
					break;
				default:
					$part = preg_quote($part, '/');
			}
		}
		return join('', $parts);
	}
} 