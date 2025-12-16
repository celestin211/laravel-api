<?php

namespace Tests\Unit\Domain\Specifications\Product;

use App\Domain\Specifications\Product\ProductHasValidPriceSpecification;
use App\Models\Product;
use Tests\TestCase;

class ProductHasValidPriceSpecificationTest extends TestCase
{
    private ProductHasValidPriceSpecification $specification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->specification = new ProductHasValidPriceSpecification();
    }

    public function test_is_satisfied_by_returns_true_for_product_with_positive_price(): void
    {
        $product = new Product(['price' => 99.99]);

        $this->assertTrue($this->specification->isSatisfiedBy($product));
    }

    public function test_is_satisfied_by_returns_false_for_product_with_zero_price(): void
    {
        $product = new Product(['price' => 0.0]);

        $this->assertFalse($this->specification->isSatisfiedBy($product));
    }

    public function test_is_satisfied_by_returns_false_for_product_with_negative_price(): void
    {
        $product = new Product(['price' => -10.0]);

        $this->assertFalse($this->specification->isSatisfiedBy($product));
    }

    public function test_is_satisfied_by_returns_false_for_non_product_instance(): void
    {
        $this->assertFalse($this->specification->isSatisfiedBy('not-a-product'));
    }
}
