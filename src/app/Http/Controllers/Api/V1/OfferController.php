<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Paginators\PaginatorCollection;
use App\Services\Contracts\ShopService;
use App\Http\Resources\Offer\Collection;
use App\Services\Contracts\OfferService;

class OfferController extends Controller
{
    public function __invoke(
        int|string $shopIdOrShopCountryCode,
        ShopService $shopService,
        OfferService $offerService,
        Request $request
    ): Collection {
        $perPage = $request->input('perPage', 15);

        if (is_numeric($shopIdOrShopCountryCode) && ($shop = $shopService->findByExternalId($shopIdOrShopCountryCode))) {
            $results = $offerService->getOffersByShop($shop);

            $paginatedResults = new PaginatorCollection($results);

            return new Collection($paginatedResults->paginate($perPage));
        }

        $results = $offerService->getOffersByShopCountry($shopIdOrShopCountryCode);
        $paginatedResults = new PaginatorCollection($results);

        return new Collection($paginatedResults->paginate($perPage));
    }
}
