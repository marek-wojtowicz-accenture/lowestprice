<?php
declare(strict_types=1);

namespace Magento\LowestPrice\Cron;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\LowestPrice\Api\LowestPriceRepositoryInterface;
use Magento\LowestPrice\Api\Data\LowestPriceInterfaceFactory;
use Psr\Log\LoggerInterface;

class UpdateLowestPrice
{
    private ProductCollectionFactory $productCollectionFactory;
    private LowestPriceRepositoryInterface $lowestPriceRepository;
    private LowestPriceInterfaceFactory $lowestPriceFactory;
    protected $logger;

    public function __construct(
        ProductCollectionFactory $productCollectionFactory,
        LowestPriceRepositoryInterface $lowestPriceRepository,
        LowestPriceInterfaceFactory $lowestPriceFactory,
        LoggerInterface $logger
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->lowestPriceRepository = $lowestPriceRepository;
        $this->lowestPriceFactory = $lowestPriceFactory;
        $this->logger = $logger;
    }

    public function execute()
    {
        $currentDate = (new \DateTime())->format('Y-m-d');
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect('price');

        foreach ($productCollection as $product) {
            $lowestPrice = $this->lowestPriceFactory->create();
            $lowestPrice->setProductId((int)$product->getId());
            $lowestPrice->setPrice((float)$product->getPrice());
            $lowestPrice->setDate($currentDate);
            $this->lowestPriceRepository->save($lowestPrice);
        }
    }

}
