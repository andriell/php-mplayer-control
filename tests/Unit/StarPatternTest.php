<?php

namespace Tests\Unit;

use App\Lib\StarPattern;
use Tests\TestCase;


class StarPatternTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $patternStar = new StarPattern('*test1*test2*');
        $this->assertTrue($patternStar->match('str1 test1 str2 test2 str3'));
        $this->assertTrue($patternStar->match('test1 str2 test2 str3'));
        $this->assertTrue($patternStar->match('str1 test1 str2 test2'));
        $this->assertTrue($patternStar->match('test1 str2 test2'));
        $this->assertTrue($patternStar->match('test1test2'));
        $this->assertFalse($patternStar->match('str1 test1 str2 test1 str3'));
        $this->assertFalse($patternStar->match('test1 str2 test1 str3'));
        $this->assertFalse($patternStar->match('str1 test1 str2 test1'));
        $this->assertFalse($patternStar->match('test1 str2 test1'));
        $this->assertFalse($patternStar->match('test1test1'));

        $patternStar = new StarPattern('test1*test2*');
        $this->assertFalse($patternStar->match('str1 test1 str2 test2 str3'));
        $this->assertTrue($patternStar->match('test1 str2 test2 str3'));
        $this->assertFalse($patternStar->match('str1 test1 str2 test2'));
        $this->assertTrue($patternStar->match('test1 str2 test2'));
        $this->assertTrue($patternStar->match('test1test2'));
        $this->assertFalse($patternStar->match('str1 test1 str2 test1 str3'));
        $this->assertFalse($patternStar->match('test1 str2 test1 str3'));
        $this->assertFalse($patternStar->match('str1 test1 str2 test1'));
        $this->assertFalse($patternStar->match('test1 str2 test1'));
        $this->assertFalse($patternStar->match('test1test1'));

        $patternStar = new StarPattern('*test1*test2');
        $this->assertFalse($patternStar->match('str1 test1 str2 test2 str3'));
        $this->assertFalse($patternStar->match('test1 str2 test2 str3'));
        $this->assertTrue($patternStar->match('str1 test1 str2 test2'));
        $this->assertTrue($patternStar->match('test1 str2 test2'));
        $this->assertTrue($patternStar->match('test1test2'));
        $this->assertFalse($patternStar->match('str1 test1 str2 test1 str3'));
        $this->assertFalse($patternStar->match('test1 str2 test1 str3'));
        $this->assertFalse($patternStar->match('str1 test1 str2 test1'));
        $this->assertFalse($patternStar->match('test1 str2 test1'));
        $this->assertFalse($patternStar->match('test1test1'));

        $patternStar = new StarPattern('test1*test2');
        $this->assertFalse($patternStar->match('str1 test1 str2 test2 str3'));
        $this->assertFalse($patternStar->match('test1 str2 test2 str3'));
        $this->assertFalse($patternStar->match('str1 test1 str2 test2'));
        $this->assertTrue($patternStar->match('test1 str2 test2'));
        $this->assertTrue($patternStar->match('test1test2'));
        $this->assertFalse($patternStar->match('str1 test1 str2 test1 str3'));
        $this->assertFalse($patternStar->match('test1 str2 test1 str3'));
        $this->assertFalse($patternStar->match('str1 test1 str2 test1'));
        $this->assertFalse($patternStar->match('test1 str2 test1'));
        $this->assertFalse($patternStar->match('test1test1'));

    }
}
