<?php

namespace Armezit\GetCandy\VirtualInventory\Exceptions;

use GetCandy\Exceptions\Carts\CartException;

/**
 * There is not enough available stock for a virtual item
 */
class OutOfStockException extends CartException
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('virtual inventory is out of stock', previous: $previous);
    }
}
