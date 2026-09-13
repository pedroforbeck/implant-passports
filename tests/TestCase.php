<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // As views usam @vite; nos testes não precisamos dos assets compilados.
        $this->withoutVite();
    }
}
