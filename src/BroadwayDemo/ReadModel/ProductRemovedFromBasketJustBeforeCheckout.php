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

use Broadway\ReadModel\ReadModelInterface;
use Broadway\Serializer\SerializableInterface;

final class ProductRemovedFromBasketJustBeforeCheckout implements ReadModelInterface, SerializableInterface
{
    private $productId;
    private $counter;

    /**
     * @param string $productId
     */
    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->productId;
    }

    public function incrementCounter()
    {
        $this->counter++;
    }

    /**
     * @return integer
     */
    public function getCounter()
    {
        return $this->counter;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return array(
            'productId' => $this->productId,
            'counter'   => $this->counter,
        );
    }

    public static function deserialize(array $data)
    {
        return self::fromProductIdAndCounter($data['productId'], $data['counter']);
    }

    /**
     * @param string  $productId
     * @param integer $counter
     */
    private static function fromProductIdAndCounter($productId, $counter)
    {
        $model = new self($productId);
        $model->counter = $counter;

        return $model;
    }
}
