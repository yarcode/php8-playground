<?php
namespace decorators;

use \Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Fallback extends DecoratorAttribute
{
    private string $fallback;

    public function __construct(string $fallback)
    {
        $this->fallback = $fallback;
    }

    public function decorate(callable $callable): callable
    {
        return function(...$args) use ($callable) {
            try {
                return call_user_func_array($callable, $args);
            } catch (\Throwable $e) {
                return call_user_func_array($this->fallback, $args);
            }
        };
    }
}
