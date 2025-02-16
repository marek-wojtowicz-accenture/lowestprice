<?php

declare(strict_types=1);
namespace Magento\LowestPrice\Controller\Adminhtml\LowestPrice;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\LowestPrice\Api\LowestPriceRepositoryInterface;
use Magento\LowestPrice\Model\LowestPrice;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InlineEdit extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Magento_LowestPrice::lowest_price';

    private JsonFactory $jsonFactory;
    private LowestPriceRepositoryInterface $repository;

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        LowestPriceRepositoryInterface $repository
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->repository = $repository;
    }

    public function execute(): Json
    {
        /** @var Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        try {
            foreach (array_keys($postItems) as $lowestPriceId) {
                /** @var LowestPrice $lowestPrice */
                $lowestPrice = $this->repository->getById($lowestPriceId);
                foreach ($postItems[$lowestPriceId] as $key => $value) {
                    $lowestPrice->setData($key, $value);
                }
                $this->repository->save($lowestPrice);
            }
        } catch (Exception $e) {
            $messages[] = __('There was an error saving the data: ') . $e->getMessage();
            $error = true;
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
