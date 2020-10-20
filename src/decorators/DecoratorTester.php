<?php

namespace decorators;

class DecoratorTester
{
    private int $failCounter = 1;

    #[Retryable(5)]
    public function testRetry()
    {
        echo 'method call: ' . __METHOD__ . PHP_EOL;
        if ($this->failCounter <= 3) {
            $this->failCounter++;
            throw new \BadMethodCallException('Fail this time');
        }
    }

    #[Delayable(3)]
    public function testDelay()
    {
        echo 'method call: ' . __METHOD__ . PHP_EOL;
    }

    #[Retryable(2)]
    #[Fallback("\decorators\DecoratorTester::fallbackStaticMethod")]
    public function testFallback()
    {
        echo 'always fail method call: ' . __METHOD__ . PHP_EOL;
        throw new \BadMethodCallException('Always fail');
    }

    public static function fallbackStaticMethod()
    {
        echo 'fallback method call: ' . __METHOD__ . PHP_EOL;
    }
}
