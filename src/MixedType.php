<?php

namespace GQL\Type;

use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\AST;


class MixedType extends ScalarType
{
    public const NAME = 'mixed';

    /**
     * @var self
     */
    private static $instance;

    public function __construct()
    {
        parent::__construct([
            'name' => 'mixed',
            'description' => 'A GraphQL type that can contain any value'
        ]);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Serializes an internal value to include in a response.
     *
     * @param string $value
     * @return string
     */
    public function serialize($value)
    {
        return $value;
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * @param mixed $value
     * @return mixed
     */
    public function parseValue($value)
    {
        return $value;
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null)
    {
        $value = AST::valueFromASTUntyped($valueNode);
        return $value;
    }


}
