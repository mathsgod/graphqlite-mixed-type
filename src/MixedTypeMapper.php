<?php

use GQL\Type\MixedType;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\NamedType;
use GraphQL\Type\Definition\OutputType;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Type;
use ReflectionMethod;
use TheCodingMachine\GraphQLite\Mappers\Root\RootTypeMapperInterface;

class MixedTypeMapper implements RootTypeMapperInterface
{
    /** @var RootTypeMapperInterface */
    private $next;

    public function __construct(RootTypeMapperInterface $next)
    {
        $this->next = $next;
    }

    public function toGraphQLOutputType(Type $type, ?OutputType $subType, ReflectionMethod $refMethod, DocBlock $docBlockObj): OutputType
    {
        if ((string)$type == "mixed") {
            return MixedType::getInstance();
        }
        return $this->next->toGraphQLOutputType($type, $subType, $refMethod, $docBlockObj);
    }

    public function toGraphQLInputType(Type $type, ?InputType $subType, string $argumentName, ReflectionMethod $refMethod, DocBlock $docBlockObj): InputType
    {
        if ((string)$type == "mixed") {
            return MixedType::getInstance();
        }
        return $this->next->toGraphQLInputType($type, $subType, $argumentName, $refMethod, $docBlockObj);
    }

    /**
     * Returns a GraphQL type by name.
     * If this root type mapper can return this type in "toGraphQLOutputType" or "toGraphQLInputType", it should
     * also map these types by name in the "mapNameToType" method.
     *
     * @param string $typeName The name of the GraphQL type
     * @return NamedType
     */
    public function mapNameToType(string $typeName): NamedType
    {
        if ($typeName === MixedType::NAME) {
            return MixedType::getInstance();
        }
        return $this->next->mapNameToType($typeName);
    }
}
