<?php

namespace GQL\Type\Tests;

use GQL\Type\MixedType;
use GraphQL\Language\AST\BooleanValueNode;
use GraphQL\Language\AST\FloatValueNode;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\ListValueNode;
use GraphQL\Language\AST\NodeList;
use GraphQL\Language\AST\ObjectFieldNode;
use GraphQL\Language\AST\ObjectValueNode;
use GraphQL\Language\AST\StringValueNode;
use PHPUnit\Framework\TestCase;

class MixedTypeTest extends TestCase
{
    public function testGetInstanceReturnsSameInstance(): void
    {
        $a = MixedType::getInstance();
        $b = MixedType::getInstance();
        $this->assertSame($a, $b);
    }

    public function testNameIsMixed(): void
    {
        $type = MixedType::getInstance();
        $this->assertSame('mixed', $type->name);
        $this->assertSame('mixed', MixedType::NAME);
    }

    public function testSerializeReturnsValueAsIs(): void
    {
        $type = MixedType::getInstance();
        $this->assertSame('hello', $type->serialize('hello'));
        $this->assertSame(42, $type->serialize(42));
        $this->assertSame(true, $type->serialize(true));
        $this->assertNull($type->serialize(null));
        $this->assertSame(['a' => 1], $type->serialize(['a' => 1]));
    }

    public function testParseValueReturnsValueAsIs(): void
    {
        $type = MixedType::getInstance();
        $this->assertSame('world', $type->parseValue('world'));
        $this->assertSame(3.14, $type->parseValue(3.14));
        $this->assertSame(false, $type->parseValue(false));
        $this->assertNull($type->parseValue(null));
    }

    public function testParseLiteralStringNode(): void
    {
        $type = MixedType::getInstance();
        $node = new StringValueNode(['value' => 'hello']);
        $this->assertSame('hello', $type->parseLiteral($node));
    }

    public function testParseLiteralIntNode(): void
    {
        $type = MixedType::getInstance();
        $node = new IntValueNode(['value' => '123']);
        $this->assertSame(123, $type->parseLiteral($node));
    }

    public function testParseLiteralFloatNode(): void
    {
        $type = MixedType::getInstance();
        $node = new FloatValueNode(['value' => '1.5']);
        $this->assertSame(1.5, $type->parseLiteral($node));
    }

    public function testParseLiteralBooleanNode(): void
    {
        $type = MixedType::getInstance();
        $node = new BooleanValueNode(['value' => true]);
        $this->assertTrue($type->parseLiteral($node));
    }

    public function testParseLiteralObjectNode(): void
    {
        $type = MixedType::getInstance();

        $field = new ObjectFieldNode([
            'name'  => new \GraphQL\Language\AST\NameNode(['value' => 'hello']),
            'value' => new StringValueNode(['value' => 'world']),
        ]);
        $node = new ObjectValueNode(['fields' => new NodeList([$field])]);

        $result = $type->parseLiteral($node);
        $this->assertSame(['hello' => 'world'], $result);
    }

    public function testParseLiteralListNode(): void
    {
        $type = MixedType::getInstance();
        $node = new ListValueNode([
            'values' => new NodeList([
                new IntValueNode(['value' => '1']),
                new IntValueNode(['value' => '2']),
            ])
        ]);

        $result = $type->parseLiteral($node);
        $this->assertSame([1, 2], $result);
    }
}
