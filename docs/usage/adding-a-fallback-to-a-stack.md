# GrizzIT Fallback - Adding a fallback to a stack

The fallback stack is used to registered multiple fallback entries to a single
object. These can then be invoke through one line of code and will either
return a result (if everything went as planned), or throw an exception. The
fallback stack takes a validator as an argument to verify the output of every
single fallback entry. The method  `addFallback` is used to add the fallback to
the stack. This function takes the Fallback and a position as its arguments.
The stack can then be invoked with a set of parameters, which will be delegated
to the fallback entry.

```php
<?php

use GrizzIt\Fallback\Component\FallbackStack;
use GrizzIt\Validator\Component\Type\StringValidator;

$validator = new StringValidator();

$fallbackStack = new FallbackStack($validator);

$fallbackStack->addFallback($fallback, 0);

$fallbackStack('bar');
```

## Further reading

[Back to usage index](index.md)

[Configuring a fallback](configuring-a-fallback.md)
