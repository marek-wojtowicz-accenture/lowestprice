<?php

declare(strict_types=1);

namespace Magento\LowestPrice\Model\ResourceModel\LowestPrice;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\LowestPrice\Model\LowestPrice;
use Magento\LowestPrice\Model\ResourceModel\LowestPrice as LowestPriceResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    protected function _construct(): void
    {
        $this->_init(LowestPrice::class, LowestPriceResourceModel::class);
    }
}
