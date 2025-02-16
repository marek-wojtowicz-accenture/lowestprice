<?php

declare(strict_types=1);

namespace Magento\LowestPrice\Model;

use Magento\LowestPrice\Api\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Config implements ConfigInterface
{
    public function __construct(private ScopeConfigInterface $scopeConfig)
    {
    }

    public function getApiEndpoint(): string
    {
        return $this->scopeConfig->getValue(self::API_ENDPOINT_PATH);
    }

    public function getApiKey(): string
    {
        return $this->scopeConfig->getValue(self::API_KEY_PATH);
    }

    public function getNameSurname(): string
    {
        return $this->scopeConfig->getValue(self::NAME_SURNAME);
    }

    public function getIsSendToCustomer(): string
    {
        return $this->scopeConfig->getValue(self::IS_SEND_TO_CUSTOMER);
    }
}
