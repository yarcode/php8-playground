<?php
namespace decorators;

use \Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Retryable extends DecoratorAttribute
{
    private int $retryCount;

    public function __construct(int $retryCount)
    {
        $this->retryCount = $retryCount;
    }

    public function decorate(callable $callable): callable
    {
        return function(...$args) use ($callable) {
            $lastException = null;
            $foundResult = false;
            $result = null;

            for ($i=0; $i<$this->retryCount; $i++) {
                $callNum = $i+1;
                echo "trying call {$callNum} of {$this->retryCount} time" . PHP_EOL;
                try {
                    $result = call_user_func_array($callable, $args);
                    $foundResult = true;
                    echo "success" . PHP_EOL;
                    break;
                } catch (\Throwable $e) {
                    $lastException = null;
                }
            }

            if ($foundResult) {
                return $result;
            } elseif ($lastException instanceof \Throwable) {
                throw $lastException;
            } else {
                throw new \LogicException('You shall not pass');
            }
        };
    }
}
