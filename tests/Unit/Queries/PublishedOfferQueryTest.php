<?php

namespace Tests\Unit\Queries;

use App\Models\Offer;
use App\Models\Product;
use App\Queries\PublishedOfferQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishedOfferQueryTest extends TestCase
{
    use RefreshDatabase;

    private PublishedOfferQuery $query;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure database is clean before each test
        Offer::query()->delete();
        Product::query()->delete();
        $this->query = new PublishedOfferQuery();
    }

    public function test_builds_query_for_published_offers(): void
    {
        Offer::factory()->published()->create(['name' => 'Published Offer']);
        Offer::factory()->create(['name' => 'Draft Offer', 'state' => 'draft']);
        Offer::factory()->hidden()->create(['name' => 'Hidden Offer']);

        $results = $this->query->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Published Offer', $results->first()->name);
        $this->assertEquals('published', $results->first()->state);
    }

    public function test_includes_only_published_products(): void
    {
        $offer = Offer::factory()->published()->create();
        Product::factory()->published()->create(['offer_id' => $offer->id]);
        Product::factory()->create(['offer_id' => $offer->id, 'state' => 'draft']);
        Product::factory()->invisible()->create(['offer_id' => $offer->id]);

        $results = $this->query->get();
        $offer = $results->first();

        $this->assertCount(1, $offer->products);
        $this->assertEquals('published', $offer->products->first()->state);
    }

    public function test_get_returns_collection(): void
    {
        Offer::factory()->published()->count(3)->create();

        $results = $this->query->get();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $results);
        $this->assertCount(3, $results);
    }

    public function test_returns_empty_collection_when_no_published_offers(): void
    {
        Offer::factory()->create(['state' => 'draft']);
        Offer::factory()->hidden()->create();

        $results = $this->query->get();

        $this->assertCount(0, $results);
    }
}
