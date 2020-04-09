# GrizzIT Fallback - Configuring a fallback

A fallback is a mechanism that checks whether the output of a callable is
returning the correct value. If it doesn't it will throw an exception, notifying
the fallback stack to move on to the next registered entry.

A fallback can be created by providing it with a callable, a (optional)
validator and a (optional) set of parameters. The callable will be executed
when it is reached inside the stack. The validator (see package:
`grizz-it/validator`) will be used to verify the output of the callable. The
parameters are variadic and will override the parameters send by the stack.

An implementation would look like the following example:
```php
<?php

use GrizzIt\Fallback\Component\Fallback;
use GrizzIt\Validator\Component\Logical\ConstValidator;

$class = new class {
    /**
     * Returns the input.
     *
     * @param string $input
     *
     * @return string
     */
    public function foo(string $input): string
    {
        return $input;
    }
};

$validator = new ConstValidator('bar');

$fallback = new Fallback([$class, 'foo'], $validator);
```

The fallback entry can then be registered to a stack.

## Further reading

[Back to usage index](index.md)

[Adding a fallback to a stack](adding-a-fallback-to-a-stack.md)
