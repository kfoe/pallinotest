<?php

namespace Tests\Unit\Resources\Shop;

use Tests\TestCase;
use App\Http\Resources\Shop\Model;
use App\Http\Resources\Shop\Collection;

/**
 * @internal
 */
class CollectionTest extends TestCase
{
    public function testCollectionHasRightCollects(): void
    {
        $collection = new Collection([]);

        $this->assertSame(Model::class, $collection->collects);
    }
}
