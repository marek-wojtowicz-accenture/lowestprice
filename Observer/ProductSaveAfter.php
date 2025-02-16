<?php
declare(strict_types=1);

namespace Magento\LowestPrice\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\LowestPrice\Api\Data\LowestPriceInterfaceFactory;
use Magento\LowestPrice\Api\LowestPriceRepositoryInterface;

class ProductSaveAfter implements ObserverInterface
{
    private LowestPriceInterfaceFactory $lowestPriceFactory;
    private LowestPriceRepositoryInterface $lowestPriceRepository;

    public function __construct(
        LowestPriceInterfaceFactory $lowestPriceFactory,
        LowestPriceRepositoryInterface $lowestPriceRepository
    ) {
        $this->lowestPriceFactory = $lowestPriceFactory;
        $this->lowestPriceRepository = $lowestPriceRepository;
    }

    public function execute(Observer $observer)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $observer->getEvent()->getProduct();
        $productId = (int)$product->getId();
        $newPrice = $product->getPrice();

        try {
            $lowestPrice = $this->lowestPriceRepository->getLowestPriceByDate($productId);

            // Check if there is an existing entry and if the new price is lower
            if ($lowestPrice) {
                if (((float)$newPrice < (float)$lowestPrice->getPrice())) {
                    $lowestPrice->setPrice((float)$newPrice);
                    $lowestPrice->setDate(date('Y-m-d')); // Set the current date
                    $this->lowestPriceRepository->save($lowestPrice);
                }
            } else {
                $lowestPrice = $this->lowestPriceFactory->create();
                $lowestPrice->setProductId((int)$productId);
                $lowestPrice->setPrice((float)$newPrice);
                $lowestPrice->setDate(date('Y-m-d'));
                $this->lowestPriceRepository->save($lowestPrice);
            }
        } catch (\Exception $e) {
            // Handle the exception if needed
        }
    }
}
