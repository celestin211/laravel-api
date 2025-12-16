<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\Slug;
use InvalidArgumentException;
use Tests\TestCase;

class SlugTest extends TestCase
{
    public function test_can_create_slug_from_string(): void
    {
        $slug = Slug::from('Summer Sale');

        $this->assertEquals('summer-sale', $slug->value());
    }

    public function test_can_create_slug_from_existing_slug(): void
    {
        $slug = Slug::fromSlug('summer-sale');

        $this->assertEquals('summer-sale', $slug->value());
    }

    public function test_slugifies_string_with_special_characters(): void
    {
        $slug = Slug::from('Summer Sale 2024!');

        $this->assertEquals('summer-sale-2024', $slug->value());
    }

    public function test_throws_exception_for_empty_slug(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Slug cannot be empty.');

        new Slug('');
    }

    public function test_throws_exception_for_slug_exceeding_maximum_length(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Slug cannot exceed 255 characters.');

        new Slug(str_repeat('a', 256));
    }

    public function test_throws_exception_for_invalid_slug_format(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Slug must contain only lowercase letters, numbers, and hyphens.');

        new Slug('Invalid_Slug');
    }

    public function test_throws_exception_for_slug_with_uppercase_letters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Slug must contain only lowercase letters, numbers, and hyphens.');

        new Slug('Invalid-Slug');
    }

    public function test_equals_returns_true_for_same_slug(): void
    {
        $slug1 = Slug::fromSlug('test-slug');
        $slug2 = Slug::fromSlug('test-slug');

        $this->assertTrue($slug1->equals($slug2));
    }

    public function test_equals_returns_false_for_different_slug(): void
    {
        $slug1 = Slug::fromSlug('test-slug');
        $slug2 = Slug::fromSlug('other-slug');

        $this->assertFalse($slug1->equals($slug2));
    }

    public function test_to_string_returns_slug_value(): void
    {
        $slug = Slug::fromSlug('test-slug');

        $this->assertEquals('test-slug', (string) $slug);
    }
}
