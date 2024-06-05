<?php

namespace Helper;


use Codeception\Exception\ModuleException;
use Codeception\Module;
use Codeception\TestInterface;

class Acceptance extends Module
{
    /**
     * Do before test.
     *
     * @param TestInterface $test
     * @throws ModuleException
     */
    public function _before(TestInterface $test)
    {
        $name = $test->getMetadata()->getName();
        $this->getModule('WebDriver')->_capabilities(function($currentCapabilities) use ($name) {
            $currentCapabilities['name'] = $name;
            codecept_debug($currentCapabilities['name']);
            return $currentCapabilities;
        });
    }

    /**
     * Do after test failed.
     *
     * @param TestInterface $test
     * @param $fail
     * @throws ModuleException
     */
    public function _failed(TestInterface $test, $fail)
    {
        $this->getModule('WebDriver')->executeJS('browserstack_executor: {"action": "setSessionStatus", "arguments": {"status":"failed"}}');
    }
}