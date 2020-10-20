<?php
namespace decorators;

use \Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Delayable extends DecoratorAttribute
{
    private int $delay;

    public function __construct(int $delay)
    {
        $this->delay = $delay;
    }

    public function decorate(callable $callable): callable
    {
        return function(...$args) use ($callable) {
            echo "sleeping for {$this->delay} seconds" . PHP_EOL;
            $result = call_user_func_array($callable, $args);
            sleep($this->delay);
            return $result;
        };
    }
}
