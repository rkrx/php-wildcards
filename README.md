Simple wildcard component for PHP 5.3+
======================================

This project aims to provide a dead simple component for php to support wildcards. Wildcards are * (zero or more character) and ? (exactly one character). The component is not tied to filenames. You can use it also for namespaces, urls and other strings.


Why not just use regular expressions?
-------------------------------------

Because there is no reason to use regular expressions for the most common figures:

`string*` means "starts with".<br />
`*string` means "ends with".

So even if I use regular expressions to cover complex patterns, it is too simple to use regular expressions for one of these. Internally I use substr() instead. If you like to provide more speedups for such simple patterns, feel free to push me some.
 

Example:
--------

```php
<?php
use Kir\StringUtils\Matching\Wildcards\Pattern;

echo (new Pattern('test.*'))->match('test.txt') ? "is matching\n" : "is not matching\n";
echo Pattern::create('test.*')->match('test.txt') ? "is matching\n" : "is not matching\n";
echo Pattern::create('*.txt')->match('test.txt') ? "is matching\n" : "is not matching\n";
echo Pattern::create('*.*')->match('test.txt') ? "is matching\n" : "is not matching\n";
echo Pattern::create('test*txt')->match('test.txt') ? "is matching\n" : "is not matching\n";
echo Pattern::create('test?txt')->match('test.txt') ? "is matching\n" : "is not matching\n";
echo Pattern::create('t*.???')->match('test.txt') ? "is matching\n" : "is not matching\n";
echo Pattern::create('t*?.txt')->match('test8.txt') ? "is matching\n" : "is not matching\n";
```


Composer:
---------

```
"require": [
	"rkr/wildcards": "1.*"
]
```