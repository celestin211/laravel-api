<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\Sku;
use InvalidArgumentException;
use Tests\TestCase;

class SkuTest extends TestCase
{
    public function test_can_create_sku_from_string(): void
    {
        $sku = Sku::from('PROD-001');

        $this->assertEquals('PROD-001', $sku->value());
    }

    public function test_normalizes_sku_to_uppercase(): void
    {
        $sku = Sku::from('prod-001');

        $this->assertEquals('PROD-001', $sku->value());
    }

    public function test_trims_whitespace_from_sku(): void
    {
        $sku = Sku::from('  PROD-001  ');

        $this->assertEquals('PROD-001', $sku->value());
    }

    public function test_throws_exception_for_empty_sku(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('SKU cannot be empty.');

        new Sku('');
    }

    public function test_throws_exception_for_sku_exceeding_maximum_length(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('SKU cannot exceed 255 characters.');

        new Sku(str_repeat('A', 256));
    }

    public function test_throws_exception_for_invalid_sku_format(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('SKU must contain only uppercase letters, numbers, and hyphens.');

        Sku::from('prod_001');
    }

    public function test_accepts_valid_sku_with_letters_numbers_and_hyphens(): void
    {
        $sku = Sku::from('PROD-001-ABC');

        $this->assertEquals('PROD-001-ABC', $sku->value());
    }

    public function test_equals_returns_true_for_same_sku(): void
    {
        $sku1 = Sku::from('PROD-001');
        $sku2 = Sku::from('PROD-001');

        $this->assertTrue($sku1->equals($sku2));
    }

    public function test_equals_returns_false_for_different_sku(): void
    {
        $sku1 = Sku::from('PROD-001');
        $sku2 = Sku::from('PROD-002');

        $this->assertFalse($sku1->equals($sku2));
    }

    public function test_to_string_returns_sku_value(): void
    {
        $sku = Sku::from('PROD-001');

        $this->assertEquals('PROD-001', (string) $sku);
    }
}
