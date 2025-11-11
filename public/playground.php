<?php

declare(strict_types=1);

/**
 * @property string $name
 */
final class Foo
{
    public string $name = 'John Deo';

    public function getName(): string
    {
        return $this->name;
    }
}

$foo = new Foo();

$foo->name = 'hello';

echo $foo->name.PHP_EOL;
