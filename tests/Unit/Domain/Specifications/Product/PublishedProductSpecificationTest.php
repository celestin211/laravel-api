<?php

namespace Tests\Unit\Domain\Specifications\Product;

use App\Domain\Specifications\Product\PublishedProductSpecification;
use App\Models\Product;
use Tests\TestCase;

class PublishedProductSpecificationTest extends TestCase
{
    private PublishedProductSpecification $specification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->specification = new PublishedProductSpecification();
    }

    public function test_is_satisfied_by_returns_true_for_published_product(): void
    {
        $product = new Product(['state' => 'published']);

        $this->assertTrue($this->specification->isSatisfiedBy($product));
    }

    public function test_is_satisfied_by_returns_false_for_draft_product(): void
    {
        $product = new Product(['state' => 'draft']);

        $this->assertFalse($this->specification->isSatisfiedBy($product));
    }

    public function test_is_satisfied_by_returns_false_for_invisible_product(): void
    {
        $product = new Product(['state' => 'invisible']);

        $this->assertFalse($this->specification->isSatisfiedBy($product));
    }

    public function test_is_satisfied_by_returns_false_for_non_product_instance(): void
    {
        $this->assertFalse($this->specification->isSatisfiedBy('not-a-product'));
    }
}
