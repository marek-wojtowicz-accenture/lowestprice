<?php

declare(strict_types=1);

namespace Magento\LowestPrice\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\LowestPrice\Api\Data\LowestPriceInterface;
use Magento\LowestPrice\Api\Data\LowestPriceSearchResultInterface;

interface LowestPriceRepositoryInterface
{
    /**
     * @param int $id
     * @return \Magento\LowestPrice\Api\Data\LowestPriceInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): LowestPriceInterface;

    public function getLowestPriceByProductId(int $productId): ?LowestPriceInterface;

    public function getLowestPriceByDate(int $productId): ?LowestPriceInterface;

    /**
     * @param \Magento\LowestPrice\Api\Data\LowestPriceInterface
     * @return void
     */
    public function save(LowestPriceInterface $LowestPrice): void;

    /**
     * @param \Magento\LowestPrice\Api\Data\LowestPriceInterface
     * @return void
     */
    public function delete(LowestPriceInterface $LowestPrice): void;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\LowestPrice\Api\Data\LowestPriceSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): LowestPriceSearchResultInterface;
}
