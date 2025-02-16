<?php

declare(strict_types=1);

namespace Magento\LowestPrice\Controller\Adminhtml\LowestPrice;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;


class Index extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Magento_LowestPrice::lowest_price';

    private PageFactory $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute(): Page
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magento_LowestPrice::lowest_price');
        $resultPage->getConfig()->getTitle()->prepend(__('Lowest Price'));

        return $resultPage;
    }
}
