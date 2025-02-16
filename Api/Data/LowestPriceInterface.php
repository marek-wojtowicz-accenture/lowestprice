<?php

declare(strict_types=1);

namespace Magento\LowestPrice\Api\Data;

interface LowestPriceInterface
{
    public const TABLE_NAME = 'lowest_product_price';

    public const ENTITY_ID = 'entity_id';
    public const PRODUCT_ID = 'product_id';
    public const PRICE = 'price';
    public const DATE = 'date';


    public function getEntityId(): int;

    public function setEntityId(int $value): self;

    public function getProductId(): int;

    public function setProductId(int $productId): self;

    public function getPrice(): float;

    public function setPrice(float $price): self;

    public function getDate(): string;

    public function setDate(string $dateFrom): self;
}
