<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $unitTesting = true;

        $testEnvironment = 'testing';

        $app = require __DIR__.'/../../bootstrap/start.php';

        $this->prepareForTests();

        return $app;
    }

    private function prepareForTests()
    {
        Artisan::call('migrate:refresh', ['--database' => Config::get('database.default')]);
    }

}
