<?php

namespace GQL\Type\Tests;

use GQL\Type\MixedTypeMapperFactory;
use GraphQL\GraphQL;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;
use TheCodingMachine\GraphQLite\SchemaFactory;

class SimpleContainer implements ContainerInterface
{
    public function __construct(private array $entries) {}

    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new \RuntimeException("Entry not found: $id");
        }
        return $this->entries[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }
}

class IntegrationTest extends TestCase
{
    private static ?\GraphQL\Type\Schema $schema = null;

    private function buildSchema(): \GraphQL\Type\Schema
    {
        if (self::$schema !== null) {
            return self::$schema;
        }

        $cache = new Psr16Cache(new ArrayAdapter());
        $container = new SimpleContainer([
            TestController::class => new TestController(),
        ]);

        $factory = new SchemaFactory($cache, $container);
        $factory->addNamespace('GQL\\Type\\Tests');
        $factory->addRootTypeMapperFactory(new MixedTypeMapperFactory());

        return self::$schema = $factory->createSchema();
    }

    public function testMixedObjectInputAndOutput(): void
    {
        $result = GraphQL::executeQuery(
            $this->buildSchema(),
            'query { mixedInput(a: {hello: "world"}) }'
        );

        $this->assertEmpty($result->errors);
        $this->assertSame(['hello' => 'world'], $result->data['mixedInput']);
    }

    public function testMixedStringInput(): void
    {
        $result = GraphQL::executeQuery(
            $this->buildSchema(),
            'query { mixedInput(a: "hello") }'
        );

        $this->assertEmpty($result->errors);
        $this->assertSame('hello', $result->data['mixedInput']);
    }

    public function testMixedIntInput(): void
    {
        $result = GraphQL::executeQuery(
            $this->buildSchema(),
            'query { mixedInput(a: 42) }'
        );

        $this->assertEmpty($result->errors);
        $this->assertSame(42, $result->data['mixedInput']);
    }

    public function testMixedBoolInput(): void
    {
        $result = GraphQL::executeQuery(
            $this->buildSchema(),
            'query { mixedInput(a: true) }'
        );

        $this->assertEmpty($result->errors);
        $this->assertTrue($result->data['mixedInput']);
    }

    public function testMixedListInput(): void
    {
        $result = GraphQL::executeQuery(
            $this->buildSchema(),
            'query { mixedInput(a: [1, 2, 3]) }'
        );

        $this->assertEmpty($result->errors);
        $this->assertSame([1, 2, 3], $result->data['mixedInput']);
    }

    public function testMixedNestedObjectInput(): void
    {
        $result = GraphQL::executeQuery(
            $this->buildSchema(),
            'query { mixedInput(a: {outer: {inner: "value"}}) }'
        );

        $this->assertEmpty($result->errors);
        $this->assertSame(['outer' => ['inner' => 'value']], $result->data['mixedInput']);
    }
}
