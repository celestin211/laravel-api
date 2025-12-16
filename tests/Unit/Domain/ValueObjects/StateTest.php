<?php

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\State;
use InvalidArgumentException;
use Tests\TestCase;

class StateTest extends TestCase
{
    public function test_can_create_offer_state(): void
    {
        $state = State::offer('published');

        $this->assertEquals('published', $state->value());
    }

    public function test_can_create_product_state(): void
    {
        $state = State::product('published');

        $this->assertEquals('published', $state->value());
    }

    public function test_throws_exception_for_invalid_offer_state(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('State "invalid" is not allowed.');

        State::offer('invalid');
    }

    public function test_throws_exception_for_invalid_product_state(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('State "invalid" is not allowed.');

        State::product('invalid');
    }

    public function test_throws_exception_for_empty_state(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('State cannot be empty.');

        new State('', ['draft', 'published']);
    }

    public function test_is_published_returns_true_for_published_state(): void
    {
        $state = State::offer('published');

        $this->assertTrue($state->isPublished());
    }

    public function test_is_published_returns_false_for_draft_state(): void
    {
        $state = State::offer('draft');

        $this->assertFalse($state->isPublished());
    }

    public function test_is_draft_returns_true_for_draft_state(): void
    {
        $state = State::offer('draft');

        $this->assertTrue($state->isDraft());
    }

    public function test_is_draft_returns_false_for_published_state(): void
    {
        $state = State::offer('published');

        $this->assertFalse($state->isDraft());
    }

    public function test_is_hidden_returns_true_for_hidden_offer_state(): void
    {
        $state = State::offer('hidden');

        $this->assertTrue($state->isHidden());
    }

    public function test_is_hidden_returns_true_for_invisible_product_state(): void
    {
        $state = State::product('invisible');

        $this->assertTrue($state->isHidden());
    }

    public function test_is_hidden_returns_false_for_published_state(): void
    {
        $state = State::offer('published');

        $this->assertFalse($state->isHidden());
    }

    public function test_equals_returns_true_for_same_state(): void
    {
        $state1 = State::offer('published');
        $state2 = State::offer('published');

        $this->assertTrue($state1->equals($state2));
    }

    public function test_equals_returns_false_for_different_state(): void
    {
        $state1 = State::offer('published');
        $state2 = State::offer('draft');

        $this->assertFalse($state1->equals($state2));
    }

    public function test_to_string_returns_state_value(): void
    {
        $state = State::offer('published');

        $this->assertEquals('published', (string) $state);
    }
}
