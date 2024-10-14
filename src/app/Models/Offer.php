<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string      $id
 * @property int         $external_id
 * @property string      $product
 * @property int         $price
 * @property string      $currency
 * @property string      $description
 * @property string      $shop_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Shop $shop
 *
 * @method static \Database\Factories\OfferFactory            factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Offer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Offer extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'external_id',
        'product',
        'price',
        'currency',
        'description',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => $value / 100,
            set: static fn ($value) => $value * 100
        );
    }
}
