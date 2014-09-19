<?php
namespace Kir\StringUtils\Matching\Wildcards;

class Pattern {
	/**
	 * @var \Closure
	 */
	private $func = null;

	/**
	 * PHP 5.3 can't use $this in closures
	 * 
	 * @var array
	 */
	private $helpers = array();

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
		$this->initHelpers();
		$this->compile($pattern);
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
	 */
	private function compile($pattern) {
		$pattern = preg_replace('/\\*+/', '*', $pattern);

		if (preg_match('/^[^\\*]+\\*$/', $pattern) && strpos($pattern, '?') === false) {
			$this->initStartsWith($pattern);
		} elseif (preg_match('/^\\*[^\\*]+$/', $pattern) && strpos($pattern, '?') === false) {
			$this->initEndsWith($pattern);
		} elseif (preg_match('/^[^\\*]+\\*[^\\*]+$/', $pattern) && strpos($pattern, '?') === false) {
			$this->initStartsAndEndsWith($pattern);
		} else {
			$this->initRegExp($pattern);
		}
	}

	/**
	 * @param $startsWith
	 * @return string
	 */
	private function initStartsWith($startsWith) {
		$startsWith = rtrim($startsWith, '*');
		$startsWithFunc = $this->helpers['startsWith'];
		$this->func = function ($string) use ($startsWith, $startsWithFunc) {
			return $startsWithFunc($string, $startsWith);
		};
	}

	/**
	 * @param $endsWith
	 * @return string
	 */
	private function initEndsWith($endsWith) {
		$endsWith = ltrim($endsWith, '*');
		$endsWithFunc = $this->helpers['endsWith'];
		$this->func = function ($string) use ($endsWith, $endsWithFunc) {
			return $endsWithFunc($string, $endsWith);
		};
	}

	/**
	 * @param $pattern
	 * @return string
	 */
	private function initStartsAndEndsWith($pattern) {
		list($startsWith, $endsWith) = explode('*', $pattern);
		$startsWithFunc = $this->helpers['startsWith'];
		$endsWithFunc = $this->helpers['endsWith'];
		$this->func = function ($string) use ($startsWith, $startsWithFunc, $endsWith, $endsWithFunc) {
			$stringLength = strlen($string);
			$bothLength = strlen($startsWith) + strlen($endsWith);
			if ($bothLength > $stringLength) {
				return false;
			}
			return $startsWithFunc($string, $startsWith) && $endsWithFunc($string, $endsWith);
		};
	}

	/**
	 * @param $pattern
	 */
	private function initRegExp($pattern) {
		$parts = preg_split('/([\\?\\*])/', $pattern, -1, PREG_SPLIT_DELIM_CAPTURE);
		foreach ($parts as &$part) {
			switch ($part) {
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

	/**
	 * PHP 5.3 can't use $this in closures
	 */
	private function initHelpers() {
		/**
		 * @param string $haystack
		 * @param string $needle
		 * @return bool
		 */
		$this->helpers['startsWith'] = function ($haystack, $needle) {
			$needleLen = strlen($needle);
			return substr($haystack, 0, $needleLen) == $needle;
		};
		
		/**
		 * @param string $haystack
		 * @param string $needle
		 * @return bool
		 */
		$this->helpers['endsWith'] = function ($haystack, $needle) {
			$haystackLen = strlen($haystack);
			$needleLen = strlen($needle);
			return substr($haystack, $haystackLen - $needleLen, $needleLen) == $needle;
		};
	}
} 