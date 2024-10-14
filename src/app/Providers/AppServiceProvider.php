<?php

namespace App\Providers;

use GuzzleHttp\Client;
use App\Services\ShopService;
use App\Services\OfferService;
use GuzzleHttp\ClientInterface;
use App\Repositories\ShopRepository;
use App\Repositories\OfferRepository;
use Illuminate\Support\ServiceProvider;
use App\Services\Contracts\ShopService as ShopServiceContract;
use App\Services\Contracts\OfferService as OfferServiceContract;
use App\Repositories\Contracts\ShopRepository as ShopRepositoryContract;
use App\Repositories\Contracts\OfferRepository as OfferRepositoryContract;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        ShopServiceContract::class => ShopService::class,
        OfferServiceContract::class => OfferService::class,
        OfferRepositoryContract::class => OfferRepository::class,
        ShopRepositoryContract::class => ShopRepository::class,
    ];

    public function register(): void
    {
        $this->bindingGuzzleClient();
    }

    public function boot(): void {}

    private function bindingGuzzleClient(): void
    {
        $this->app->bind(ClientInterface::class, static fn () => new Client(config('guzzle.config')));
    }
}
