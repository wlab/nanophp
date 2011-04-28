<?php

/**
 * Example class 
 * 
 * Example
 * 
 * @author Christopher Beck <chris.beck@jpmh.co.uk>
 * @version SVN: $id
 */
class Example extends \nano\core\test\Unit
{
	/**
	 * Function - Test Push And Pop
	 */
    public function testPushAndPop()
    {
        $stack = array();
        $this->assertEquals(0, count($stack));

        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack)-1]);
        $this->assertEquals(1, count($stack));
 
        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }
}