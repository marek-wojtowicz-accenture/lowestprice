<?php

declare(strict_types=1);

namespace Magento\LowestPrice\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\LowestPrice\Api\Data\LowestPriceInterface;
use Magento\LowestPrice\Model\ResourceModel\LowestPrice as LowestPriceResourceModel;

class LowestPrice extends AbstractModel implements LowestPriceInterface
{
    protected $_eventPrefix = 'magento_lowest_price_lowest_price';

    protected function _construct(): void
    {
        $this->_init(LowestPriceResourceModel::class);
    }

    public function getEntityId(): int
    {
        return (int) $this->getData(LowestPriceInterface::ENTITY_ID);
    }

    public function setEntityId($value): LowestPriceInterface
    {
        $this->setData(LowestPriceInterface::ENTITY_ID, $value);
        return $this;
    }

    public function getProductId(): int
    {
        return (int) $this->getData(LowestPriceInterface::PRODUCT_ID);
    }


    public function setProductId(int $productId): self
    {
        $this->setData(LowestPriceInterface::PRODUCT_ID, $productId);
        return $this;
    }

    public function getPrice(): float
    {
        return (float) $this->getData(LowestPriceInterface::PRICE);
    }

    public function setPrice(float $price): self
    {
        $this->setData(LowestPriceInterface::PRICE, $price);
        return $this;
    }

    public function getDate(): string
    {
        return (string) $this->getData(LowestPriceInterface::DATE);
    }

    public function setDate(string $dateFrom): self
    {
        $this->setData(LowestPriceInterface::DATE, $dateFrom);
        return $this;
    }

}
