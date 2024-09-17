<?php

namespace GQL\Type;


use GQL\Type\MixedType;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\NamedType;
use GraphQL\Type\Definition\OutputType;
use GraphQL\Type\Definition\Type as DefinitionType;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Type;
use ReflectionMethod;
use ReflectionProperty;
use TheCodingMachine\GraphQLite\Mappers\Root\RootTypeMapperInterface;

class MixedTypeMapper implements RootTypeMapperInterface
{
    /** @var RootTypeMapperInterface */
    private $next;

    public function __construct(RootTypeMapperInterface $next)
    {
        $this->next = $next;
    }

    public function toGraphQLOutputType(Type $type, ?OutputType $subType, ReflectionMethod|ReflectionProperty $reflector, DocBlock $docBlockObj): OutputType&DefinitionType
    {
        if ((string)$type == "mixed") {
            return MixedType::getInstance();
        }
        return $this->next->toGraphQLOutputType($type, $subType, $reflector, $docBlockObj);
    }


    public function toGraphQLInputType(Type $type, ?InputType $subType, string $argumentName, ReflectionMethod|ReflectionProperty $reflector, DocBlock $docBlockObj): InputType&DefinitionType
    {
        if ((string)$type == "mixed") {
            return MixedType::getInstance();
        }
        return $this->next->toGraphQLInputType($type, $subType, $argumentName, $reflector, $docBlockObj);
    }

    /**
     * Returns a GraphQL type by name.
     * If this root type mapper can return this type in "toGraphQLOutputType" or "toGraphQLInputType", it should
     * also map these types by name in the "mapNameToType" method.
     *
     * @param string $typeName The name of the GraphQL type
     * @return NamedType
     */
    public function mapNameToType(string $typeName): NamedType&DefinitionType
    {
        if ($typeName === MixedType::NAME) {
            return MixedType::getInstance();
        }
        return $this->next->mapNameToType($typeName);
    }
}
