<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use App\Http\Controllers\Controller;
use App\Http\Resources\Offer\Collection;

class OfferController extends Controller
{
    public function __invoke(
        Request $request,
        Shop $shop,
        Route $route
    ): Collection {
        $page = $request->input('page');
        $perPage = $request->input('perPage');

        $inputBeforeSubstituteBinding = $route->originalParameter('shopIdOrShopCountryCode');
        if (is_numeric($inputBeforeSubstituteBinding)) {
            $orderedCollectionByPrice = $shop->offers()->orderBy('price')->paginate(
                perPage: $perPage,
                page: $page
            );

            return new Collection($orderedCollectionByPrice);
        }

        $collectionWithShop = $shop->offers()->with('shop')->paginate(
            perPage: $perPage,
            page: $page
        );

        return new Collection($collectionWithShop);
    }
}
