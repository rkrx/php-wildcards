Simple wildcard component (PHP 5.3+)
====================================

[![Build Status](https://travis-ci.org/rkrx/php-wildcards.svg)](https://travis-ci.org/rkrx/php-wildcards)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rkrx/php-wildcards/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rkrx/php-wildcards/?branch=master)

This project aims to provide a dead simple component for php to support wildcards. Wildcards are * (zero or more character) and ? (exactly one character). The component is not tied to filenames. You can use it also for namespaces, urls and other strings.


## Why can't you just provide a simple function for this? 

Because of effectivity. When you create an instance of the `Pattern`-class, you also "compile" the pattern. This means that I try to find the optimal test method for your later input. So if you run the same pattern more often in the same run, you could benefit from that optimization. If not, the Interface should still be simple enough to make you happy. If not, go wrap a function around it.

## Why not just use regular expressions?

Because there is no reason to use regular expressions for the most common figures:

`string*` means "starts with".<br />
`*string` means "ends with".

So even if I use regular expressions to cover complex patterns, it is too pointless to use regular expressions for one of these. Internally I use substr() instead. If you like to provide more speedups for such simple patterns, feel free to push me some.

## Composer:

```
composer require rkr/wildcards ^1.0
```

## Example:

```php
<?php
use Kir\StringUtils\Matching\Wildcards\Pattern;

(new Pattern('test.*'))->match('test.txt');      // true
Pattern::create('test.*')->match('test.txt');    // true
Pattern::create('*.txt')->match('test.txt');     // true
Pattern::create('*.*')->match('test.txt');       // true
Pattern::create('test*txt')->match('test.txt');  // true
Pattern::create('test?txt')->match('test.txt');  // true
Pattern::create('t*.???')->match('test.txt');    // true
Pattern::create('t*t?.txt')->match('test8.txt'); // true
```
