<?php
namespace Kir\StringUtils\Matching\Wildcards;

class PatternTest extends \PHPUnit_Framework_TestCase {
	public function testStars() {
		$this->assertEquals(true, Pattern::create('*test.txt')->match('abc-test.txt'));
		$this->assertEquals(true, Pattern::create('*test.txt')->match('test.txt'));
		$this->assertEquals(false, Pattern::create('*test.txt')->match('est.txt'));
		
		$this->assertEquals(true, Pattern::create('test*.txt')->match('test-abc.txt'));
		$this->assertEquals(true, Pattern::create('test*.txt')->match('test.txt'));
		$this->assertEquals(false, Pattern::create('test*.txt')->match('tes.txt'));
		
		$this->assertEquals(true, Pattern::create('test.*')->match('test.txt'));
		$this->assertEquals(true, Pattern::create('test.*')->match('test.'));
		$this->assertEquals(false, Pattern::create('test.*')->match('test'));
	}
	
	public function testMarks() {
		$this->assertEquals(true, Pattern::create('?test.txt')->match('1test.txt'));
		$this->assertEquals(false, Pattern::create('?test.txt')->match('test.txt'));
		
		$this->assertEquals(true, Pattern::create('test?txt')->match('test.txt'));
		$this->assertEquals(true, Pattern::create('test?txt')->match('test-txt'));
		$this->assertEquals(false, Pattern::create('test?txt')->match('testtxt'));
		
		$this->assertEquals(true, Pattern::create('test.???')->match('test.txt'));
		$this->assertEquals(false, Pattern::create('test.???')->match('test.text'));
	}
}
 