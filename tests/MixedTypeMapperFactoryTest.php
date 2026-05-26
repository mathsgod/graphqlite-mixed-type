<?php

namespace GQL\Type\Tests;

use GQL\Type\MixedTypeMapper;
use GQL\Type\MixedTypeMapperFactory;
use PHPUnit\Framework\TestCase;
use TheCodingMachine\GraphQLite\Mappers\Root\RootTypeMapperFactoryContext;
use TheCodingMachine\GraphQLite\Mappers\Root\RootTypeMapperInterface;

class MixedTypeMapperFactoryTest extends TestCase
{
    public function testCreateReturnsMixedTypeMapper(): void
    {
        $factory = new MixedTypeMapperFactory();
        $next = $this->createStub(RootTypeMapperInterface::class);
        // RootTypeMapperFactoryContext is final with a complex constructor;
        // create without constructor since MixedTypeMapperFactory doesn't use it.
        $context = (new \ReflectionClass(RootTypeMapperFactoryContext::class))->newInstanceWithoutConstructor();

        $result = $factory->create($next, $context);

        $this->assertInstanceOf(MixedTypeMapper::class, $result);
    }
}
