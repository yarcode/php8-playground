<?php
namespace decorators;

include_once __DIR__ . "/../../vendor/autoload.php";

$test = new DecoratorLoader(new DecoratorTester());
$test->testDelay();
echo "-----------------------------------------------\n";
$test->testRetry();
echo "-----------------------------------------------\n";
$test->testFallback();
