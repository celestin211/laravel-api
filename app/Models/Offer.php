<?php

namespace App\Models;

use Database\Factories\OfferFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $image
 * @property string $state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $products
 *
 * @method static \Illuminate\Database\Eloquent\Builder<Offer> newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<Offer> newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<Offer> query()
 * @method static \Illuminate\Database\Eloquent\Builder<Offer> where($column, $operator = null, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder<Offer> whereNull(string|array<string> $columns)
 * @method static int count()
 * @method static \Illuminate\Database\Eloquent\Collection<int, Offer> all()
 * @method static \Illuminate\Database\Eloquent\Collection<int, Offer> get()
 * @method static Offer create(array<string, mixed> $attributes = [])
 * @method static Offer findOrFail(int|string $id, array<string> $columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<Offer> ofState(string $state)
 * @method \Illuminate\Database\Eloquent\Builder<Offer> ofState(string $state)
 *
 * @mixin \Illuminate\Database\Eloquent\Builder<Offer>
 */
class Offer extends Model
{
    /** @use HasFactory<OfferFactory> */
    use HasFactory;

    /** @var array<string, string> */
    public static array $states = [
        'draft' => 'Brouillon',
        'published' => 'Publié',
        'hidden' => 'Masqué',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'state',
    ];

    /**
     * @return HasMany<Product>
     *
     * @phpstan-return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope a query to filter offers by state.
     *
     * @param  Builder<Offer>  $query
     * @return Builder<Offer>
     */
    public function scopeOfState(Builder $query, string $state): Builder
    {
        return $query->where('state', $state);
    }
}
