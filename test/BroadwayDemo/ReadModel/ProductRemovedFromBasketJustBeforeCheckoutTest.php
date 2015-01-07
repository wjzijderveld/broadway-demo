<?php

/*
 * This file is part of the broadway/broadway-demo package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayDemo\ReadModel;

use Broadway\ReadModel\Testing\ReadModelTestCase;

class ProductRemovedFromBasketJustBeforeCheckoutTest extends ReadModelTestCase
{
    protected function createReadModel()
    {
        $productId = '4b8b712d-2701-43aa-bd99-f596cfb5ab79';
        $model = new ProductRemovedFromBasketJustBeforeCheckout($productId);

        return $model;
    }

    /**
     * @test
     */
    public function it_increments_the_counter_with_one()
    {
        $model = $this->createReadModel();

        $this->assertEquals(0, $model->getCounter());

        $model->incrementCounter();

        $this->assertEquals(1, $model->getCounter());
    }
}
