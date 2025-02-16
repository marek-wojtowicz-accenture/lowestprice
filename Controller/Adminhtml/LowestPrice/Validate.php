<?php

declare(strict_types=1);
namespace Magento\LowestPrice\Controller\Adminhtml\LowestPrice;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\DataObject;

class Validate extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Magento_LowestPrice::lowest_price';

    private JsonFactory $jsonFactory;
    private DataObject $response;

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->response = new DataObject();
    }

    public function validateRequireEntries(array $data): void
    {
        $requiredFields = [
            'product_id' => 'Prodict ID',
            'price' => 'Price',
            'date' => 'Entry Date',
        ];
        foreach ($data as $field => $value) {
            if (in_array($field, array_keys($requiredFields)) && $value == '') {
                $this->addErrorMessage(
                    (string) __('To apply changes you should fill in required "%1" field', $requiredFields[$field])
                );
            }
        }
    }

    private function addErrorMessage(string $message): void
    {
        $this->response->setError(true);
        if (!is_array($this->response->getMessages())) {
            $this->response->setMessages([]);
        }
        $messages = $this->response->getMessages();
        $messages[] = $message;
        $this->response->setMessages($messages);
    }

    public function execute(): Json
    {
        $this->response->setError(0);

        $this->validateRequireEntries($this->getRequest()->getParams());

        /** @var Json $resultJson */
        $resultJson = $this->jsonFactory->create()->setData($this->response);
        return $resultJson;
    }
}
