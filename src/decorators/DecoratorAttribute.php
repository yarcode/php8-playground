<?php
namespace decorators;

use \Attribute;

abstract class DecoratorAttribute
{
    abstract public function decorate(callable $callable): callable;
}
