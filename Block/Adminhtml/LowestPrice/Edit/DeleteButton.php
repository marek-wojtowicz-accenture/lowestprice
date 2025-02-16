<?php

declare(strict_types=1);
namespace Magento\LowestPrice\Block\Adminhtml\LowestPrice\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton implements ButtonProviderInterface
{
    private UrlInterface $urlBuilder;
    private Registry $registry;

    public function __construct(
        Context $context,
        Registry $registry
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->registry = $registry;
    }

    public function getButtonData(): array
    {
        if (!$this->registry->registry('entity_id')) {
            return [];
        }

        return [
            'label' => __('Delete'),
            'class' => 'delete',
            'id' => 'lowest_price-edit-delete-button',
            'data_attribute' => [
                'url' => $this->getDeleteUrl()
            ],
            'on_click' => 'deleteConfirm(\''
                . __("Are you sure you want to do delete this entity?")
                . '\', \'' . $this->getDeleteUrl() . '\')',
            'sort_order' => 20,
        ];
    }

    public function getDeleteUrl(): string
    {
        return $this->urlBuilder->getUrl('*/*/delete', ['entity_id' => $this->registry->registry('entity_id')]);
    }
}
