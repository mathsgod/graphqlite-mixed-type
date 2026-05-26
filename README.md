[![PHP Composer](https://github.com/mathsgod/graphqlite-mixed-type/actions/workflows/php.yml/badge.svg)](https://github.com/mathsgod/graphqlite-mixed-type/actions/workflows/php.yml)

# graphqlite-mixed-type

`mixed` input and output type support for [thecodingmachine/graphqlite](https://github.com/thecodingmachine/graphqlite).

GraphQL does not natively support a `mixed` scalar type. This package adds a custom `mixed` scalar that can accept and return any value — objects, arrays, strings, numbers, or booleans — without requiring a predefined schema type.

## Requirements

- PHP 8.1+
- `thecodingmachine/graphqlite` ^8.1

## Installation

```bash
composer require mathsgod/graphqlite-mixed-type
```

## Setup

Register the mapper factory when building your schema:

```php
use GQL\Type\MixedTypeMapperFactory;

$factory = new SchemaFactory($cache, $container);
// ...
$factory->addRootTypeMapperFactory(new MixedTypeMapperFactory());
```

## Usage

### Output type

Use `outputType: "mixed"` on a `#[Query]` or `#[Mutation]` to return any value:

```php
use TheCodingMachine\GraphQLite\Annotations\Query;

class Controllers
{
    /**
     * @param mixed $a
     */
    #[Query(outputType: "mixed")]
    public function mixedInput($a): mixed
    {
        return $a;
    }
}
```

### Input type

Annotate a parameter with `@param mixed` to accept any input value:

```php
/**
 * @param mixed $data
 */
#[Query(outputType: "mixed")]
public function echo(mixed $data): mixed
{
    return $data;
}
```

### Example query

```graphql
query {
    mixedInput(a: {hello: "world"})
}
```

Response:

```json
{
  "data": {
    "mixedInput": {
      "hello": "world"
    }
  }
}
```

## How it works

| Class | Role |
|---|---|
| `MixedType` | Custom GraphQL scalar that passes any value through as-is |
| `MixedTypeMapper` | Maps PHP `mixed` type to/from `MixedType` for inputs and outputs |
| `MixedTypeMapperFactory` | Factory registered with `SchemaFactory` to plug in the mapper |

## Testing

```bash
composer install
vendor/bin/phpunit
```
