<?php
namespace Magento\LowestPrice\Plugin;

class ProductPricePlugin
{
    protected $lowestPriceRepository;

    public function __construct(
        \Magento\LowestPrice\Api\LowestPriceRepositoryInterface $lowestPriceRepository
    ) {
        $this->lowestPriceRepository = $lowestPriceRepository;
    }

    public function afterToHtml(
        \Magento\Catalog\Pricing\Render\FinalPriceBox $subject,
                                                      $result
    ) {
        // Get the product entity ID
        $productId = (int)$subject->getSaleableItem()->getId();

        // Fetch the lowest price from your custom table via the repository
        $lowestPriceEntry = $this->lowestPriceRepository->getLowestPriceByProductId($productId);

        if ($lowestPriceEntry) {
            $taxedPrice = $lowestPriceEntry->getPrice() * 1.23;
            $formatedPrice = number_format($taxedPrice, 2, ',', '');
            // Append lowest price text
            $lowestPriceText = "<div class='lowest-price' style='font-size: 1.1rem;'>Najniższa cena sprzed 30 dni: <span style='font-weight: bold;'>" . $formatedPrice . " zł </span></div>";
            $result = str_replace('</div>', $lowestPriceText . '</div>', $result);
            //$result .= $lowestPriceText;
        }

        return $result;
    }
}
