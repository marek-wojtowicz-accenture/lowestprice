<?php
declare(strict_types=1);

namespace Magento\LowestPrice\Cron;

use Magento\LowestPrice\Model\ResourceModel\LowestPrice\CollectionFactory as LowestPriceCollectionFactory;

class DeleteLowestPrice
{
    private LowestPriceCollectionFactory $lowestPriceCollectionFactory;

    public function __construct(
        LowestPriceCollectionFactory $lowestPriceCollectionFactory
    ) {
        $this->lowestPriceCollectionFactory = $lowestPriceCollectionFactory;
    }

    public function execute()
    {
        $date = new \DateTime('now - 30 days');
        $formattedDate = $date->format('Y-m-d');

        $collection = $this->lowestPriceCollectionFactory->create();
        $collection->addFieldToFilter('date', ['lt' => $formattedDate]);

        foreach ($collection as $item) {
            $item->delete();
        }
    }
}
