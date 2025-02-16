<?php

declare(strict_types=1);

namespace Magento\LowestPrice\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\LowestPrice\Api\Data\LowestPriceInterface;

class LowestPrice extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init(LowestPriceInterface::TABLE_NAME, LowestPriceInterface::ENTITY_ID);
    }
}
