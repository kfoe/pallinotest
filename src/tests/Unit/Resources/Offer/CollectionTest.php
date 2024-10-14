<?php

namespace Tests\Unit\Resources\Offer;

use Tests\TestCase;
use App\Http\Resources\Offer\Model;
use App\Http\Resources\Offer\Collection;

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
