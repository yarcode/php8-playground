<?php
namespace decorators;

class DecoratorLoader
{
    private object $decoratedInstance;
    private array $decoratedMethods = [];

    public function __construct(object $decoratedInstance)
    {
        $this->decoratedInstance = $decoratedInstance;

        $reflectionClass = new \ReflectionClass($decoratedInstance);
        foreach ($reflectionClass->getMethods() as $method) {
            $attributes = $method->getAttributes(DecoratorAttribute::class, \ReflectionAttribute::IS_INSTANCEOF);
            foreach ($attributes as $attribute) {
                $attribute = $attribute->newInstance();
                $this->decoratedMethods[$method->getShortName()][] = $attribute;
            }
        }
    }

    public function __call($name, $args)
    {
        echo "method call: $name" . PHP_EOL;
        echo 'started: ' . date('Y-m-d H:i:s') . PHP_EOL;
        $callable = [$this->decoratedInstance, $name];

        if (isset($this->decoratedMethods[$name])) {
            foreach ($this->decoratedMethods[$name] as $decorator) {
                $callable = $decorator->decorate($callable);
            }
        }

        $result = call_user_func_array($callable, $args);
        echo 'finished: ' . date('Y-m-d H:i:s') . PHP_EOL;
        return $result;
    }
}
