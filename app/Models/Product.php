<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $offer_id
 * @property string $name
 * @property string $sku
 * @property string|null $image
 * @property float $price
 * @property string $state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Offer $offer
 *
 * @method static \Illuminate\Database\Eloquent\Builder<Product> newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<Product> newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<Product> query()
 * @method static \Illuminate\Database\Eloquent\Builder<Product> where($column, $operator = null, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder<Product> whereNull(string|array<string> $columns)
 * @method static int count()
 * @method static \Illuminate\Database\Eloquent\Collection<int, Product> all()
 * @method static \Illuminate\Database\Eloquent\Collection<int, Product> get()
 * @method static Product create(array<string, mixed> $attributes = [])
 * @method static Product findOrFail(int|string $id, array<string> $columns = ['*'])
 */
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /** @var array<string, string> */
    public static array $states = [
        'draft' => 'Brouillon',
        'published' => 'Publié',
        'invisible' => 'Invisible',
    ];

    protected $fillable = [
        'offer_id',
        'name',
        'sku',
        'image',
        'price',
        'state',
    ];

    /**
     * @return BelongsTo<Product, Offer>
     *
     * @phpstan-return BelongsTo<Offer, $this>
     */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }
}
