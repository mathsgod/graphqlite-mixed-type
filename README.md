# graphqlite-mixed-type

input and output mixed type for 

## Setup
```php

$factory = new SchemaFactory($cache, $container);
//...
$factory->addRootTypeMapperFactory(new MixedTypeMapperFactory);

```

## Example

```php
class Controllers{
    
    /**
     * @param mixed $a
     */
    #[Query(outputType: "mixed")]
    public function mixedInput($a)
    {
        return $a;
    }
}
```

```gql
query {
    mixedInput(a:{hello:"world"})
}
```
It will output {hello:"world}




