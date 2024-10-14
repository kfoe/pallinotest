<?php

namespace Tests\Unit\Paginators;

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use App\Paginators\PaginatorCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @internal
 */
class PaginatorCollectionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        app()->bind('request', function () {
            return Request::createFromGlobals();
        });
    }

    public function testPaginationHasCorrectItemsPerPage(): void
    {
        $items = collect(range(1, 30));
        $paginatorCollection = new PaginatorCollection(new Collection($items));
        $perPage = 5;

        $paginatedResult = $paginatorCollection->paginate($perPage);

        $this->assertCount($perPage, $paginatedResult->items());
        $this->assertEquals(1, $paginatedResult->currentPage());
        $this->assertEquals(30, $paginatedResult->total());
    }

    public function testPaginationOnSecondPage(): void
    {
        $items = collect(range(1, 20));

        $paginatorCollection = new PaginatorCollection(new Collection($items));
        $perPage = 5;

        LengthAwarePaginator::currentPageResolver(static fn () => 2);

        $paginatedResult = $paginatorCollection->paginate($perPage);

        $this->assertEquals(2, $paginatedResult->currentPage());
        $this->assertEquals([6, 7, 8, 9, 10], $paginatedResult->items());
        $this->assertEquals(20, $paginatedResult->total());

        LengthAwarePaginator::currentPageResolver(static fn () => 1);
    }

    public function testPaginationReturnsCorrectTotalPages(): void
    {
        $items = collect(range(1, 25));
        $paginatorCollection = new PaginatorCollection(new Collection($items));
        $perPage = 5;

        $paginatedResult = $paginatorCollection->paginate($perPage);

        $this->assertEquals(5, $paginatedResult->lastPage());
    }

    public function testPaginationOnEmptyCollection(): void
    {
        $items = collect();
        $paginatorCollection = new PaginatorCollection(new Collection($items));
        $perPage = 5;

        $paginatedResult = $paginatorCollection->paginate($perPage);

        $this->assertCount(0, $paginatedResult->items());
        $this->assertEquals(0, $paginatedResult->total());
        $this->assertEquals(1, $paginatedResult->currentPage());
    }
}
