<?php

namespace App\Http\Resources\Offer;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Shop\Model as ShopResource;

/**
 * @mixin Offer
 */
class Model extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->external_id,
            'product' => $this->product,
            'price' => $this->price,
            'currency' => $this->currency,
            'description' => $this->description,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'shop' => new ShopResource($this->whenLoaded('shop')),
        ];
    }
}
