<?php

namespace Tests\Unit\Queries;

use App\Models\Offer;
use App\Queries\DashboardOfferQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class DashboardOfferQueryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure database is clean before each test
        Offer::query()->delete();
    }

    public function test_builds_query_for_dashboard(): void
    {
        Offer::factory()->published()->create(['name' => 'Offer 1']);
        Offer::factory()->create(['name' => 'Offer 2', 'state' => 'draft']);

        $request = Request::create('/dashboard');
        $query = new DashboardOfferQuery($request);

        $results = $query->get();

        $this->assertCount(2, $results);
    }

    public function test_filters_by_state(): void
    {
        Offer::factory()->published()->create(['name' => 'Published']);
        Offer::factory()->create(['name' => 'Draft', 'state' => 'draft']);

        $request = Request::create('/dashboard', 'GET', ['state' => 'published']);
        $query = new DashboardOfferQuery($request);

        $results = $query->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Published', $results->first()->name);
        $this->assertEquals('published', $results->first()->state);
    }

    public function test_filters_by_name(): void
    {
        Offer::factory()->create(['name' => 'Test Offer']);
        Offer::factory()->create(['name' => 'Other Offer']);

        $request = Request::create('/dashboard', 'GET', ['name' => 'Test']);
        $query = new DashboardOfferQuery($request);

        $results = $query->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Test Offer', $results->first()->name);
    }

    public function test_filters_by_slug(): void
    {
        Offer::factory()->create(['slug' => 'test-offer']);
        Offer::factory()->create(['slug' => 'other-offer']);

        $request = Request::create('/dashboard', 'GET', ['slug' => 'test']);
        $query = new DashboardOfferQuery($request);

        $results = $query->get();

        $this->assertCount(1, $results);
        $this->assertEquals('test-offer', $results->first()->slug);
    }

    public function test_combines_multiple_filters(): void
    {
        Offer::factory()->published()->create(['name' => 'Test Published', 'slug' => 'test-published']);
        Offer::factory()->published()->create(['name' => 'Other Published', 'slug' => 'other-published']);
        Offer::factory()->create(['name' => 'Test Draft', 'state' => 'draft']);

        $request = Request::create('/dashboard', 'GET', [
            'state' => 'published',
            'name' => 'Test',
        ]);
        $query = new DashboardOfferQuery($request);

        $results = $query->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Test Published', $results->first()->name);
        $this->assertEquals('published', $results->first()->state);
    }

    public function test_get_returns_collection(): void
    {
        Offer::factory()->count(3)->create();

        $request = Request::create('/dashboard');
        $query = new DashboardOfferQuery($request);

        $results = $query->get();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $results);
        $this->assertCount(3, $results);
    }
}
