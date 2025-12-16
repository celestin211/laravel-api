<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $value = random_int(0, 1) === 1;
        $this->assertContains($value, [true, false]);
    }
}
