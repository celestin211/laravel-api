<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\Price;
use InvalidArgumentException;
use Tests\TestCase;

class PriceTest extends TestCase
{
    public function test_can_create_price_from_float(): void
    {
        $price = new Price(99.99);

        $this->assertEquals(99.99, $price->value());
    }

    public function test_can_create_price_from_string(): void
    {
        $price = Price::from('99.99');

        $this->assertEquals(99.99, $price->value());
    }

    public function test_can_create_price_from_numeric_string(): void
    {
        $price = Price::from('100');

        $this->assertEquals(100.0, $price->value());
    }

    public function test_throws_exception_for_negative_price(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Price cannot be negative.');

        new Price(-10.0);
    }

    public function test_throws_exception_for_price_exceeding_maximum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Price cannot exceed 999,999.99.');

        new Price(1000000.0);
    }

    public function test_throws_exception_for_invalid_numeric_value(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Price must be numeric.');

        // Utiliser un objet qui n'est pas numérique
        Price::from(new \stdClass());
    }

    public function test_formatted_returns_string_with_two_decimals(): void
    {
        $price = new Price(99.9);

        $this->assertEquals('99.90', $price->formatted());
    }

    public function test_is_zero_returns_true_for_zero_price(): void
    {
        $price = new Price(0.0);

        $this->assertTrue($price->isZero());
    }

    public function test_is_zero_returns_false_for_non_zero_price(): void
    {
        $price = new Price(10.0);

        $this->assertFalse($price->isZero());
    }

    public function test_is_positive_returns_true_for_positive_price(): void
    {
        $price = new Price(10.0);

        $this->assertTrue($price->isPositive());
    }

    public function test_is_positive_returns_false_for_zero_price(): void
    {
        $price = new Price(0.0);

        $this->assertFalse($price->isPositive());
    }

    public function test_is_negative_returns_false_for_positive_price(): void
    {
        $price = new Price(10.0);

        $this->assertFalse($price->isNegative());
    }

    public function test_can_add_two_prices(): void
    {
        $price1 = new Price(50.0);
        $price2 = new Price(25.5);

        $result = $price1->add($price2);

        $this->assertEquals(75.5, $result->value());
    }

    public function test_can_subtract_two_prices(): void
    {
        $price1 = new Price(50.0);
        $price2 = new Price(25.5);

        $result = $price1->subtract($price2);

        $this->assertEquals(24.5, $result->value());
    }

    public function test_can_multiply_price_by_factor(): void
    {
        $price = new Price(50.0);

        $result = $price->multiply(1.5);

        $this->assertEquals(75.0, $result->value());
    }

    public function test_to_array_returns_formatted_array(): void
    {
        $price = new Price(99.99);

        $array = $price->toArray();

        $this->assertArrayHasKey('value', $array);
        $this->assertArrayHasKey('formatted', $array);
        $this->assertEquals(99.99, $array['value']);
        $this->assertEquals('99.99', $array['formatted']);
    }

    public function test_to_string_returns_formatted_price(): void
    {
        $price = new Price(99.99);

        $this->assertEquals('99.99', (string) $price);
    }
}
