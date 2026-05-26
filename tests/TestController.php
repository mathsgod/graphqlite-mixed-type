<?php

namespace GQL\Type\Tests;

use TheCodingMachine\GraphQLite\Annotations\Query;

class TestController
{
    /**
     * @param mixed $a
     */
    #[Query(outputType: "mixed")]
    public function mixedInput(mixed $a): mixed
    {
        return $a;
    }
}
