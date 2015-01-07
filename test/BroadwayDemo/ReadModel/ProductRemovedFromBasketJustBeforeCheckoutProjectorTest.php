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

use BroadwayDemo\Basket\BasketCheckedOut;
use BroadwayDemo\Basket\BasketId;
use BroadwayDemo\Basket\ProductWasAddedToBasket;
use BroadwayDemo\Basket\ProductWasRemovedFromBasket;
use Broadway\ReadModel\InMemory\InMemoryRepository;
use Broadway\ReadModel\Testing\ProjectorScenarioTestCase;

class ProductRemovedFromBasketJustBeforeCheckoutProjectorTest extends ProjectorScenarioTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->basketId = new BasketId('aeb8ef3c-6d40-4ef9-ad3e-4984019a4cc6');
    }

    protected function createProjector(InMemoryRepository $repository)
    {
        $projector = new ProductRemovedFromBasketJustBeforeCheckoutProjector($repository);

        return $projector;
    }

    /**
     * @test
     */
    public function it_creates_no_readmodel_when_product_was_not_removed_last()
    {
        $this->scenario
            ->given(array(new ProductWasAddedToBasket($this->basketId, 'product-fake-uuid', 'product-dummy-name')))
            ->when(new BasketCheckedOut($this->basketId, array('product-fake-id' => 1)))
            ->then(array());
    }

    /**
     * @test
     */
    public function it_creates_a_readmodel_when_a_product_was_removed_just_before_checkout()
    {
        $readModel = new ProductRemovedFromBasketJustBeforeCheckout('another-fake-uuid');
        $readModel->incrementCounter();

        $this->scenario
            ->given(array(
                new ProductWasAddedToBasket($this->basketId, 'product-fake-uuid', 'product-dummy-name'),
                new ProductWasAddedToBasket($this->basketId, 'another-fake-uuid', 'dummy'),
                new ProductWasRemovedFromBasket($this->basketId, 'another-fake-uuid'),
            ))
            ->when(new BasketCheckedOut($this->basketId, array('product-fake-id' => 1)))
            ->then(array($readModel));
    }
}
