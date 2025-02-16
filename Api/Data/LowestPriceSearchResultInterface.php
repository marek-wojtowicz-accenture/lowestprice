<?php

declare(strict_types=1);

namespace Magento\LowestPrice\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface LowestPriceSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Magento\LowestPrice\Api\Data\LowestPriceInterface[]
     */
    public function getItems();

    /**
     * @param \Magento\LowestPrice\Api\Data\LowestPriceInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
