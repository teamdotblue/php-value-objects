<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\Bool\TestBool;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\Bool\TestBoolWithFromString;

class BoolTest extends TestCase
{
    public function testBool(): void
    {
        $testTrue = TestBool::fromBool(true);
        $testFalse = TestBool::fromValue(false);
        $this->assertTrue($testTrue->toValue());
        $this->assertEquals('false', (string) $testFalse);
        $this->assertFalse($testTrue->isEqualTo($testFalse));
        $this->assertFalse($testTrue->isEqualTo('test'));
    }

    public function testExampleBool(): void
    {
        $this->assertInstanceOf(TestBool::class, TestBool::example());
    }

    public function testFromString(): void
    {
        $this->assertTrue(TestBoolWithFromString::fromString('true')->toValue());
        $this->assertTrue(TestBoolWithFromString::fromString('TRUE')->toValue());
        $this->assertTrue(TestBoolWithFromString::fromString('1')->toValue());
        $this->assertTrue(TestBoolWithFromString::fromString(' true ')->toValue());
        $this->assertFalse(TestBoolWithFromString::fromString('false')->toValue());
        $this->assertFalse(TestBoolWithFromString::fromString('FALSE')->toValue());
        $this->assertFalse(TestBoolWithFromString::fromString('0')->toValue());
        $this->assertFalse(TestBoolWithFromString::fromString(' 0 ')->toValue());
    }

    public function testFromStringInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        TestBoolWithFromString::fromString('not-a-boolean');
    }
}
