<?php

namespace GQL\Type\Tests;

use GQL\Type\MixedType;
use GQL\Type\MixedTypeMapper;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\OutputType;
use GraphQL\Type\Definition\Type as DefinitionType;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Types\Mixed_;
use phpDocumentor\Reflection\Types\String_;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use TheCodingMachine\GraphQLite\Mappers\Root\RootTypeMapperInterface;

class MixedTypeMapperTest extends TestCase
{
    private RootTypeMapperInterface $next;
    private MixedTypeMapper $mapper;
    private ReflectionMethod $reflector;
    private DocBlock $docBlock;

    protected function setUp(): void
    {
        $this->next = $this->createStub(RootTypeMapperInterface::class);
        $this->mapper = new MixedTypeMapper($this->next);
        $this->reflector = new ReflectionMethod($this, 'setUp');
        $this->docBlock = new DocBlock();
    }

    public function testToGraphQLOutputTypeReturnsMixedTypeForMixedPhpType(): void
    {
        $result = $this->mapper->toGraphQLOutputType(new Mixed_(), null, $this->reflector, $this->docBlock);
        $this->assertInstanceOf(MixedType::class, $result);
    }

    public function testToGraphQLOutputTypeDelegatesToNextForNonMixedType(): void
    {
        $expected = \GraphQL\Type\Definition\Type::string();
        $next = $this->createMock(RootTypeMapperInterface::class);
        $next->expects($this->once())
            ->method('toGraphQLOutputType')
            ->willReturn($expected);

        $mapper = new MixedTypeMapper($next);
        $result = $mapper->toGraphQLOutputType(new String_(), null, $this->reflector, $this->docBlock);
        $this->assertSame($expected, $result);
    }

    public function testToGraphQLInputTypeReturnsMixedTypeForMixedPhpType(): void
    {
        $result = $this->mapper->toGraphQLInputType(new Mixed_(), null, 'arg', $this->reflector, $this->docBlock);
        $this->assertInstanceOf(MixedType::class, $result);
    }

    public function testToGraphQLInputTypeDelegatesToNextForNonMixedType(): void
    {
        $expected = \GraphQL\Type\Definition\Type::string();
        $next = $this->createMock(RootTypeMapperInterface::class);
        $next->expects($this->once())
            ->method('toGraphQLInputType')
            ->willReturn($expected);

        $mapper = new MixedTypeMapper($next);
        $result = $mapper->toGraphQLInputType(new String_(), null, 'arg', $this->reflector, $this->docBlock);
        $this->assertSame($expected, $result);
    }

    public function testMapNameToTypeReturnsMixedTypeForMixedName(): void
    {
        $result = $this->mapper->mapNameToType('mixed');
        $this->assertInstanceOf(MixedType::class, $result);
    }

    public function testMapNameToTypeDelegatesToNextForUnknownName(): void
    {
        $expected = \GraphQL\Type\Definition\Type::string();
        $next = $this->createMock(RootTypeMapperInterface::class);
        $next->expects($this->once())
            ->method('mapNameToType')
            ->with('String')
            ->willReturn($expected);

        $mapper = new MixedTypeMapper($next);
        $result = $mapper->mapNameToType('String');
        $this->assertSame($expected, $result);
    }
}
