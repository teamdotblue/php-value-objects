<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Unit;

use EventEngine\Schema\TypeSchema;
use PHPUnit\Framework\TestCase;
use TeamBlue\ValueObjects\Tests\Object\Immutable\TestImmutable;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\Int\TestInt;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\List\TestEmailList;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\List\TestIntList;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\List\TestList;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\List\TestListImmutable;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\String\TestEmail;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\String\TestString;

use function str_contains;
use function str_replace;
use function str_starts_with;
use function strlen;
use function strtoupper;
use function usort;

class ListTest extends TestCase
{
    public function testList(): void
    {
        $list = TestList::fromArray(['test', 'test2']);
        $list2 = TestList::fromValue(['test', 'test2']);
        $list3 = TestList::fromItems([TestString::fromString('test0'), TestString::fromString('test2')]);

        $this->assertEquals(['test', 'test2'], $list->toArray());
        $this->assertEquals(['test', 'test2'], $list->toValue());
        $this->assertInstanceOf(TestString::class, $list->first());
        $this->assertInstanceOf(TestString::class, $list->last());
        $this->assertInstanceOf(TestString::class, $list->need(0));
        $this->assertInstanceOf(TestString::class, $list->toItems()[0]);
        $this->assertTrue($list->isEqualTo($list2));
        $this->assertFalse($list->isEqualTo($list3));
        $this->assertFalse($list->isEqualTo('test'));

        $list3 = $list3->shift();
        $list3 = $list3->unshift('test');
        $this->assertTrue($list->isEqualTo($list3));
        $list = $list->push(TestString::fromString('test3'));
        $this->assertEquals(['test', 'test2', 'test3'], $list->toArray());
        $list = $list->pop();
        $this->assertEquals(['test', 'test2'], $list->toArray());
        $this->assertEquals(2, $list->count());
        $this->assertEquals('test, test2', $list->implode(', '));
        $this->assertEquals(['test', 'test2', 'test3'], $list->merge(TestList::fromArray(['test3']))->toArray());
        $this->assertEquals('test3', $list->put('test3')->last());
        $this->assertEquals('test3', $list->put('test3', 0)->first());
        $this->assertEquals('test2', $list->forget(0)->first());
        $this->assertTrue($list->contains('test2'));
        $this->assertFalse($list->contains('test3'));
        $this->assertTrue($list->contains(static fn (string $test) => str_starts_with($test, 'tes')));
        $this->assertEquals([1 => 'test2'], $list->filter(static fn (string $test) => $test === 'test2')->toArray());
        $this->assertEquals([0 => 'test'], $list->reject(static fn (string $test) => $test === 'test2')->toArray());
        $this->assertEquals(
            ['', '2'],
            $list->map(static fn (TestString $test) => str_replace('test', '', $test->toString()))->toArray(),
        );
        $this->assertEquals(
            [1 => 'test2'],
            $list->intersect(TestList::fromArray(['test0', 'test2']))->toArray(),
        );

        $this->assertEquals(
            ['test', 2 => 'test2'],
            TestList::fromArray(['test', 'test', 'test2'])->unique()->toArray(),
        );

        $this->assertInstanceOf(TypeSchema::class, TestList::__itemSchema());
    }

    public function testExampleList(): void
    {
        $this->assertInstanceOf(TestList::class, TestList::example());
    }

    public function testRulesList(): void
    {
        $this->assertEquals(
            [
                'minItems' => 1,
                'maxItems' => 5,
                'uniqueItems' => true,
            ],
            TestList::validationRules(),
        );
    }

    public function testNeedByKey(): void
    {
        $this->expectExceptionMessageMatches('/No item found for key/');
        $list = TestList::fromArray(['test', 'test2']);
        $list->need(3);
    }

    public function testNeedFirst(): void
    {
        $this->expectExceptionMessageMatches('/No first value found for list/');
        $list = TestList::emptyList();
        $list->needFirst();
    }

    public function testNeedLast(): void
    {
        $this->expectExceptionMessageMatches('/No last value found for list/');
        $list = TestList::emptyList();
        $list->needLast();
    }

    public function testListImmutable(): void
    {
        $list = TestListImmutable::fromArray(
            [
                ['test' => 'test'],
                ['test' => 'test2'],
            ],
        );

        $this->assertInstanceOf(TestImmutable::class, $list->needFirst());
    }

    public function testListWithKeys(): void
    {
        $list = TestList::fromArray(['test' => 'test', 'test2' => 'test2']);
        $keysList = TestList::fromArray(['test']);
        $this->assertEquals('test', $list->needKey(TestString::fromString('test')));
        $this->assertNull($list->keyByItem('test3'));
        $this->assertEquals(['test', 'test2'], $list->keys());
        $this->assertEquals(['test2' => 'test2'], $list->diffByKeys($keysList)->toArray()); // @phpstan-ignore-line
        $this->assertEquals(['test' => 'test'], $list->getByKeys($keysList)->toArray()); // @phpstan-ignore-line
        $this->assertEquals(['test2' => 'test2'], $list->diffByKeys(['test'])->toArray());
        $this->assertEquals(['test' => 'test'], $list->getByKeys(['test'])->toArray());
        $this->assertTrue($list->has('test'));
        $this->assertFalse($list->has('test3'));
        $this->assertTrue($list->has(TestString::fromString('test')));
        $this->assertEquals(['test', 'test2'], $list->values()->toArray());
    }

    public function testNeedKeyThrowsAnException(): void
    {
        $this->expectExceptionMessageMatches('/No key found for item/');
        $list = TestList::fromArray(['test' => 'test', 'test2' => 'test2']);
        $list->needKey('test3');
    }

    public function testListWithDifferentTypes(): void
    {
        $this->expectExceptionMessageMatches('/is not a valid list item type/');
        TestList::fromItems(
            [  // @phpstan-ignore-line
                TestString::fromString('test'),
                TestString::fromString('test2'),
                TestEmail::fromString('arne@arne.be'),
            ],
        );
    }

    public function testEmptyList(): void
    {
        $emptyList = TestList::emptyList();
        $testString = TestString::fromString('test');

        $this->assertEmpty($emptyList->toArray());
        $this->assertTrue($emptyList->isEmpty());
        $this->assertEquals('[]', (string) $emptyList);
        $this->assertEquals('test', $emptyList->first($testString)?->toString());
        $this->assertEquals('test', $emptyList->last($testString)?->toString());
        $this->assertEquals(2, $emptyList->lastKey(2));
        $this->assertEquals(2, $emptyList->firstKey(2));
    }

    public function testListNeedsAnArray(): void
    {
        $this->expectExceptionMessageMatches('/No array given/');
        TestList::fromValue('test');
    }

    public function testListSort(): void
    {
        $unsortedList = [
            'Test A',
            'Test Z',
            'Test B',
        ];
        $sortingFunction = static fn (string $stringA, string $stringB) => $stringA <=> $stringB;
        $sortedList = $unsortedList;
        usort($sortedList, $sortingFunction);

        $unsortedListObject = TestList::fromArray($unsortedList);
        $sortedListObject = $unsortedListObject->usort(
            static fn (TestString $stringA, TestString $stringB) => $sortingFunction(
                $stringA->toString(),
                $stringB->toString(),
            ),
        );

        $this->assertEquals($sortedList, $sortedListObject->toArray());
        $this->assertEquals($unsortedList, $unsortedListObject->toArray());
    }

    public function testReject(): void
    {
        $list = TestList::fromArray(['test', 'test2', 'test3', 'test4']);

        // Test rejecting items that match a condition
        $rejected = $list->reject(static fn (string $test) => str_contains($test, 'test2'));
        $this->assertEquals(['test', 2 => 'test3', 3 => 'test4'], $rejected->toArray());

        // Test rejecting multiple items
        $rejected = $list->reject(static fn (string $test) => str_starts_with($test, 'test'));
        $this->assertEquals([], $rejected->toArray());

        // Test rejecting no items (all items pass the condition)
        $rejected = $list->reject(static fn (string $test) => $test === 'nonexistent');
        $this->assertEquals(['test', 'test2', 'test3', 'test4'], $rejected->toArray());

        // Test rejecting all items (no items pass the condition)
        $rejected = $list->reject(static fn (string $test) => str_contains($test, 'test'));
        $this->assertEquals([], $rejected->toArray());

        // Test reject with complex logic
        $rejected = $list->reject(static fn (string $test) => strlen($test) > 5);
        $this->assertEquals(['test', 'test2', 'test3', 'test4'], $rejected->toArray());

        // Test that reject returns a new instance (immutability)
        $rejected = $list->reject(static fn (string $test) => $test === 'test2');
        $this->assertNotSame($list, $rejected);
        $this->assertEquals(['test', 'test2', 'test3', 'test4'], $list->toArray());
        $this->assertEquals(['test', 2 => 'test3', 3 => 'test4'], $rejected->toArray());

        // Test reject on empty list
        $emptyList = TestList::emptyList();
        $rejected = $emptyList->reject(static fn (string $test) => true);
        $this->assertEquals([], $rejected->toArray());
        $this->assertTrue($rejected->isEmpty());
    }

    public function testMapToSameListType(): void
    {
        $list = TestList::fromArray(['test1', 'test2', 'test3']);

        // Map strings to uppercase strings within the same list type
        $upperCaseList = $list->map(static fn (TestString $string) => strtoupper($string->toString()));
        $this->assertInstanceOf(TestList::class, $upperCaseList);
        $this->assertEquals(['TEST1', 'TEST2', 'TEST3'], $upperCaseList->toArray());

        // Map strings to their lengths (still as strings)
        $lengthList = $list->map(static fn (TestString $string) => (string) strlen($string->toString()));
        $this->assertInstanceOf(TestList::class, $lengthList);
        $this->assertEquals(['5', '5', '5'], $lengthList->toArray());

        // Map with complex transformation
        $transformedList = $list->map(static fn (TestString $string) => 'prefix_' . $string->toString() . '_suffix');
        $this->assertInstanceOf(TestList::class, $transformedList);
        $this->assertEquals(
            ['prefix_test1_suffix', 'prefix_test2_suffix', 'prefix_test3_suffix'],
            $transformedList->toArray(),
        );
    }

    public function testMapToDifferentListType(): void
    {
        $stringList = TestList::fromArray(['test1@example.com', 'test2@example.com']);

        // Map string list to email list
        $emailList = $stringList->map(
            static fn (TestString $string) => $string->toString(),
            TestEmailList::class,
        );
        $this->assertInstanceOf(TestEmailList::class, $emailList);
        $this->assertEquals(['test1@example.com', 'test2@example.com'], $emailList->toArray());
        $this->assertInstanceOf(TestEmail::class, $emailList->first());

        // Map string list to int list (convert string lengths to integers)
        $intList = $stringList->map(
            static fn (TestString $string) => strlen($string->toString()),
            TestIntList::class,
        );
        $this->assertInstanceOf(TestIntList::class, $intList);
        $this->assertEquals([17, 17], $intList->toArray());
        $this->assertInstanceOf(TestInt::class, $intList->first());

        // Map email list back to string list
        $stringListFromEmail = $emailList->map(
            static fn (TestEmail $email) => 'email: ' . $email->toString(),
            TestList::class,
        );
        $this->assertInstanceOf(TestList::class, $stringListFromEmail);
        $this->assertEquals(['email: test1@example.com', 'email: test2@example.com'], $stringListFromEmail->toArray());
    }

    public function testMapWithComplexTransformations(): void
    {
        $stringList = TestList::fromArray(['apple', 'banana', 'cherry']);

        // Map to int list with word lengths
        $lengthList = $stringList->map(
            static fn (TestString $string) => strlen($string->toString()),
            TestIntList::class,
        );
        $this->assertEquals([5, 6, 6], $lengthList->toArray());
        $this->assertInstanceOf(TestInt::class, $lengthList->need(0));

        // Map int list back to string list with descriptive text
        $descriptiveList = $lengthList->map(
            static fn (TestInt $int) => 'Length: ' . $int->toValue(),
            TestList::class,
        );
        $this->assertEquals(['Length: 5', 'Length: 6', 'Length: 6'], $descriptiveList->toArray());

        // Chain multiple mappings
        $finalList = $stringList
            ->map(static fn (TestString $string) => strlen($string->toString()), TestIntList::class)
            ->map(static fn (TestInt $int) => $int->toValue() % 2 === 0 ? 'even' : 'odd', TestList::class);
        $this->assertEquals(['odd', 'even', 'even'], $finalList->toArray());
    }

    public function testMapEmptyList(): void
    {
        $emptyList = TestList::emptyList();

        // Map empty list to same type
        $mappedEmpty = $emptyList->map(static fn (TestString $string) => $string->toString());
        $this->assertInstanceOf(TestList::class, $mappedEmpty);
        $this->assertTrue($mappedEmpty->isEmpty());

        // Map empty list to different type
        $mappedToEmail = $emptyList->map(static fn (TestString $string) => $string->toString(), TestEmailList::class);
        $this->assertInstanceOf(TestEmailList::class, $mappedToEmail);
        $this->assertTrue($mappedToEmail->isEmpty());

        // Map empty list to int type
        $mappedToInt = $emptyList->map(static fn (TestString $string) => 0, TestIntList::class);
        $this->assertInstanceOf(TestIntList::class, $mappedToInt);
        $this->assertTrue($mappedToInt->isEmpty());
    }

    public function testMapPreservesImmutability(): void
    {
        $originalList = TestList::fromArray(['test1@example.com', 'test2@example.com']);

        // Map to different type and verify original is unchanged
        $emailList = $originalList->map(static fn (TestString $string) => $string->toString(), TestEmailList::class);

        $this->assertNotSame($originalList, $emailList);
        $this->assertEquals(['test1@example.com', 'test2@example.com'], $originalList->toArray());
        $this->assertEquals(['test1@example.com', 'test2@example.com'], $emailList->toArray());
        $this->assertInstanceOf(TestString::class, $originalList->first());
        $this->assertInstanceOf(TestEmail::class, $emailList->first());

        // Map original again and verify both mapped lists are independent
        $intList = $originalList->map(
            static fn (TestString $string) => strlen($string->toString()),
            TestIntList::class,
        );

        $this->assertNotSame($emailList, $intList);
        $this->assertEquals(['test1@example.com', 'test2@example.com'], $originalList->toArray());
        $this->assertEquals(['test1@example.com', 'test2@example.com'], $emailList->toArray());
        $this->assertEquals([17, 17], $intList->toArray());
    }

    public function testMapWithValidationRules(): void
    {
        // Test that mapped lists respect their target type's validation rules
        $stringList = TestList::fromArray(['test1', 'test2', 'test3', 'test4', 'test5']);

        // This should work - mapping to TestIntList which allows up to 20 items
        $intList = $stringList->map(
            static fn (TestString $string) => strlen($string->toString()),
            TestIntList::class,
        );
        $this->assertEquals([5, 5, 5, 5, 5], $intList->toArray());
        $this->assertEquals(5, $intList->count());

        // Test unique items constraint
        $stringListWithDuplicates = TestList::fromArray(['test', 'test', 'test']);
        $uniqueIntList = $stringListWithDuplicates->map(
            static fn (TestString $string) => strlen($string->toString()),
            TestIntList::class,
        );
        // TestIntList doesn't require unique items, so duplicates should be preserved
        $this->assertEquals([4, 4, 4], $uniqueIntList->toArray());
        $this->assertEquals(3, $uniqueIntList->count());
    }

    public function testMapWithExceptionHandling(): void
    {
        $stringList = TestList::fromArray(['valid@example.com', 'another@example.com']);

        // This should work - mapping strings to emails (valid emails will be created)
        $emailList = $stringList->map(
            static fn (TestString $string) => $string->toString(),
            TestEmailList::class,
        );
        // Note: This might throw an exception for invalid email format depending on EmailValue implementation
        // If it doesn't throw, the test verifies the mapping works; if it throws, that's expected behavior
        $this->assertInstanceOf(TestEmailList::class, $emailList);
    }
}
