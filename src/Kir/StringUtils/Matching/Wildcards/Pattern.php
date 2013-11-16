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
	 * @var callable
	 */
	private $func = null;

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
		$func = $this->func;
		return $func($string);
	}

	/**
	 * @param string $pattern
	 * @return array
	 */
	private function convert($pattern) {
		$pattern = preg_replace('/\\*+/', '*', $pattern);
		
		if(preg_match('/^[^\\*]+\\*$/', $pattern) && strpos($pattern, '?') === false) {
			$this->initStartsWith($pattern);
		} elseif(preg_match('/^\\*[^\\*]+$/', $pattern) && strpos($pattern, '?') === false) {
			$this->initEndsWith($pattern);
		} else {
			$this->initRegExp($pattern);
		}
	}

	/**
	 * @param $pattern
	 * @return string
	 */
	private function initStartsWith($pattern) {
		$pattern = rtrim($pattern, '*');
		$this->func = function ($string) use ($pattern) {
			$patternLength = strlen($pattern);
			return substr($string, 0, $patternLength) == $pattern;
		};
	}

	/**
	 * @param $pattern
	 * @return string
	 */
	private function initEndsWith($pattern) {
		$pattern = ltrim($pattern, '*');
		$this->func = function ($string) use ($pattern) {
			$stringLength = strlen($string);
			$patternLength = strlen($pattern);
			return substr($string, $stringLength - $patternLength, $patternLength) == $pattern;
		};
	}

	/**
	 * @param $pattern
	 */
	private function initRegExp($pattern) {
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
		$pattern = join('', $parts);
		$this->func = function ($string) use ($pattern) {
			return !!preg_match("/^{$pattern}$/", $string);
		};
	}
} 