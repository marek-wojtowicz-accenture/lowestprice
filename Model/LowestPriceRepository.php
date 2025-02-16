<?php

declare(strict_types=1);

namespace Magento\LowestPrice\Model;

use Magento\LowestPrice\Api\LowestPriceRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\LowestPrice\Api\Data\LowestPriceInterface;
use Magento\LowestPrice\Api\Data\LowestPriceSearchResultInterface;
use Magento\LowestPrice\Api\Data\LowestPriceSearchResultInterfaceFactory;
use Magento\LowestPrice\Model\ResourceModel\LowestPrice\CollectionFactory as LowestPriceCollectionFactory;

class LowestPriceRepository implements LowestPriceRepositoryInterface
{
    private LowestPriceFactory $lowestPriceFactory;
    private LowestPriceCollectionFactory $lowestPriceCollectionFactory;
    private LowestPriceSearchResultInterfaceFactory $searchResultFactory;
    private CollectionProcessorInterface $collectionProcessor;

    public function __construct(
        LowestPriceFactory $lowestPriceFactory,
        LowestPriceCollectionFactory $lowestPriceCollectionFactory,
        LowestPriceSearchResultInterfaceFactory $lowestPriceSearchResultInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->lowestPriceFactory = $lowestPriceFactory;
        $this->lowestPriceCollectionFactory = $lowestPriceCollectionFactory;
        $this->searchResultFactory = $lowestPriceSearchResultInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function getById(int $id): LowestPriceInterface
    {
        $lowestPrice = $this->lowestPriceFactory->create();
        $lowestPrice->getResource()->load($lowestPrice, $id);
        if (!$lowestPrice->getId()) {
            throw new NoSuchEntityException(__('Unable to find LowestPrice with ID "%1"', $id));
        }
        return $lowestPrice;
    }

    public function getLowestPriceByProductId(int $productId): ?LowestPriceInterface
    {
        $collection = $this->lowestPriceCollectionFactory->create();
        $collection->addFieldToFilter('product_id', $productId);
        $collection->setOrder('price', 'ASC');
        $collection->setPageSize(1);

        $item = $collection->getFirstItem();
        if ($item && $item->getId()) {
            return $item;
        }

        return null;
    }

    public function getLowestPriceByDate(int $productId): ?LowestPriceInterface
    {
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        $collection = $this->lowestPriceCollectionFactory->create();
        $collection->addFieldToFilter('product_id', $productId);
        $collection->addFieldToFilter('date', ['gteq' => $today]);
        $collection->addFieldToFilter('date', ['lt' => $tomorrow]);
        $collection->setOrder('date', 'DESC');
        $collection->setPageSize(1);

        $item = $collection->getFirstItem();
        if ($item && $item->getId()) {
            return $item;
        }

        return null;
    }

    public function save(LowestPriceInterface $lowestPrice): void
    {
        /** @var $lowestPrice LowestPrice **/
        $lowestPrice->getResource()->save($lowestPrice);
    }

    public function delete(LowestPriceInterface $lowestPrice): void
    {
        /** @var $lowestPrice LowestPrice **/
        $lowestPrice->getResource()->delete($lowestPrice);
    }

    public function getList(SearchCriteriaInterface $searchCriteria): LowestPriceSearchResultInterface
    {
        $collection = $this->lowestPriceCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }
}
