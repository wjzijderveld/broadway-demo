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
use Broadway\ReadModel\Projector;
use Broadway\ReadModel\RepositoryInterface;

class ProductRemovedFromBasketJustBeforeCheckoutProjector extends Projector
{
    private $buffer;
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->buffer     = array();
        $this->repository = $repository;
    }

    public function applyProductWasAddedToBasket(ProductWasAddedToBasket $event)
    {
        unset($this->buffer[(string) $event->getBasketId()]);
    }

    public function applyProductWasRemovedFromBasket(ProductWasRemovedFromBasket $event)
    {
        $this->buffer[(string) $event->getBasketId()] = $event->getProductId();
    }

    public function applyBasketCheckedOut(BasketCheckedOut $event)
    {
        if (! isset($this->buffer[(string) $event->getBasketId()])) {
            return;
        }

        $readModel = $this->getOrCreateReadModel($this->buffer[(string) $event->getBasketId()]);
        $readModel->incrementCounter();

        $this->repository->save($readModel);
    }

    private function getOrCreateReadModel($productId)
    {
        $readModel = $this->repository->find($productId);

        if (null === $readModel) {
            $readModel = new ProductRemovedFromBasketJustBeforeCheckout($productId);
        }

        return $readModel;
    }
}
