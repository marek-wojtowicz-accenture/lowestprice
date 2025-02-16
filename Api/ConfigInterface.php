<?php

declare(strict_types=1);

namespace Magento\LowestPrice\Api;

interface ConfigInterface
{
    public const API_ENDPOINT_PATH = 'fakturaxl/general/api_endpoint';
    public const API_KEY_PATH = 'fakturaxl/general/api_key';
    public const NAME_SURNAME = 'fakturaxl/general/imie_nazwisko_wystawcy';
    public const IS_SEND_TO_CUSTOMER = 'fakturaxl/general/wyslij_dokument_do_klienta_emailem';

    public function getApiEndpoint(): string;

    public function getApiKey(): string;

    public function getNameSurname(): string;

    public function getIsSendToCustomer(): string;
}
