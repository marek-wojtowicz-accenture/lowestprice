<?php

declare(strict_types=1);

namespace Magento\LowestPrice\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\LowestPrice\Api\ConfigInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Exception;
use Psr\Log\LoggerInterface;

class OrderPlaceAfter implements ObserverInterface
{
    public function __construct(
        private ConfigInterface $config,
        private LoggerInterface $logger
    ) {
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        try {
            $this->sendInvoiceToFacturaXL($order);
        } catch (Exception $e) {
            $this->logger->error('Error sending invoice to FacturaXL: ' . $e->getMessage());
        }
    }


    private function sendInvoiceToFacturaXL(OrderInterface $order)
    {
        $xmlData = $this->prepareXmlData($order);
        $this->sendXmlToFacturaXL($xmlData);
    }

    private function prepareXmlData(OrderInterface $order)
    {
        $input_xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $input_xml .= '<dokument>';

        $input_xml .= $this->getBasicInput($order);
        $input_xml .= $this->getCustomerDetails($order);
        $input_xml .= $this->getProductDetails($order);
        $input_xml .= $this->getShippingDetails($order);

        $input_xml .= '</dokument>';

        return $input_xml;
    }

    private function getBasicInput($order) {
        $billingAddress = $order->getBillingAddress();

        if (empty($billingAddress)) {
            $billingAddress = $order->getShippingAdress();
        }

        $input_xml = '<api_token>'.$this->config->getApiKey().'</api_token>';
        $input_xml .= '<typ_faktury>0</typ_faktury>';
        $input_xml .= '<typ_faktur_podtyp>0</typ_faktur_podtyp>';
        $input_xml .= '<obliczaj_sume_wartosci_faktury_wg>0</obliczaj_sume_wartosci_faktury_wg>';
        $input_xml .= '<numer_faktury></numer_faktury>';
        $input_xml .= '<data_wystawienia>'.date('Y-m-d').'</data_wystawienia>';
        $input_xml .= '<data_sprzedazy>'.date('Y-m-d').'</data_sprzedazy>';
        $input_xml .= '<termin_platnosci_data>'.date('Y-m-d').'</termin_platnosci_data>';
        $input_xml .= '<kwota_oplacona>0</kwota_oplacona>';
        $input_xml .= '<waluta>'.$order->getOrderCurrencyCode().'</waluta>';
        $input_xml .= '<kurs>0</kurs>';
        $input_xml .= '<rodzaj_platnosci>' . $this->getPaymentMethod($order) . '</rodzaj_platnosci>';
        $input_xml .= '<jezyk>0</jezyk>';
        $input_xml .= '<szablon>0</szablon>';
        $input_xml .= '<imie_nazwisko_wystawcy>'.$this->config->getNameSurname().'</imie_nazwisko_wystawcy>';
        $input_xml .= '<imie_nazwisko_odbiorcy>'.$billingAddress->getFirstname().' '.$billingAddress->getLastname().'</imie_nazwisko_odbiorcy>';
        $input_xml .= '<nr_zamowienia>'.$order->getIncrementId().'</nr_zamowienia>';
        $input_xml .= '<dodatkowe_uwagi></dodatkowe_uwagi>';
        $input_xml .= '<id_dzialy_firmy>159059</id_dzialy_firmy>';
        $input_xml .= '<wyslij_dokument_do_klienta_emailem>'.$this->config->getIsSendToCustomer().'</wyslij_dokument_do_klienta_emailem>';
        $input_xml .= '<magazyn_id></magazyn_id>';
        $input_xml .= '<automatyczne_tworzenie_dokumentu_magazynowego></automatyczne_tworzenie_dokumentu_magazynowego>';
        $input_xml .= '<obliczaj_wartosc_faktury_od>0</obliczaj_wartosc_faktury_od> ';
        $input_xml .= '<notatka_prywatna></notatka_prywatna>';

        return $input_xml;
    }

    private function getPaymentMethod($order) {

        $paymentMethod = $order->getPayment()->getMethod();
        $paymentMap = [
            'banktransfer' => 'Przelew',
            'przelewy24' => 'Przelewy24',
            'przelewy24_blik' => 'BLIK',
            'przelewy24_card' => 'Karta płatnicza',
            'przelewy24_google_pay' => 'Płatność elektroniczna',
            'przelewy24_apple_pay' => 'Płatność elektroniczna',
            'paypal_express' => 'PayPal',
            'paypal' => 'PayPal',
            'checkmo' => 'Czek',
            'cashondelivery' => 'Opłata za pobraniem'
        ];

        return $paymentMap[$paymentMethod] ?? '';
    }

    private function getCustomerDetails($order) {
        $billingAddress = $order->getBillingAddress();
        if (empty($billingAddress)) {
            $billingAddress = $order->getShippingAdress();
        }
        $name = $billingAddress->getFirstname();
        $surname = $billingAddress->getLastname();
        $companyName = !empty($billingAddress->getCompany()) ? $billingAddress->getCompany() : ($name . ' ' . $surname);
        $companyNIP = $billingAddress->getVatId();
        $street = $billingAddress->getStreetLine(1);
        $postCode = $billingAddress->getPostcode();
        $city = $billingAddress->getCity();
        $country = $billingAddress->getCountryId();
        $email = $billingAddress->getEmail();
        $phone = $billingAddress->getTelephone();
        $fax = $billingAddress->getFax();

        $input_xml = '<nabywca>';

        if ($companyName !== null) {
            $input_xml .= '<nazwa>' . $companyName . '</nazwa>';
        } else {
            $input_xml .= '<nazwa>' . $name .' ' . $surname . '</nazwa>';
        }

        if ($name !== null) {
            $input_xml .= '<imie>' . $name . '</imie>';
        }

        if ($surname !== null) {
            $input_xml .= '<nazwisko>' . $surname . '</nazwisko>';
        }

        if ($companyNIP !== null) {
            $input_xml .= '<nip>' . $companyNIP . '</nip>';
        }

        if ($street !== null) {
            $input_xml .= '<ulica_i_numer>' . htmlspecialchars($street) . '</ulica_i_numer>';
        }

        if ($postCode !== null) {
            $input_xml .= '<kod_pocztowy>' . $postCode . '</kod_pocztowy>';
        }

        if ($city !== null) {
            $input_xml .= '<miejscowosc>' . $city . '</miejscowosc>';
        }

        if ($country !== null) {
            $input_xml .= '<kraj>' . $country . '</kraj>';
        }

        if ($email !== null) {
            $input_xml .= '<email>' . $email . '</email>';
        }

        if ($phone !== null) {
            $input_xml .= '<telefon>' . $phone . '</telefon>';
        }

        if ($fax !== null) {
            $input_xml .= '<fax>' . $fax . '</fax>';
        }

        $input_xml .= '<www></www>';
        $input_xml .= '<nr_konta_bankowego></nr_konta_bankowego>';
        $input_xml .= '</nabywca>';

        return $input_xml;
    }

    private function getProductDetails($order) {

        $items = $order->getAllVisibleItems();
        $input_xml = '';
        foreach ($items as $item) {
            $input_xml .= '<faktura_pozycje>';
            $input_xml .= '<nazwa>' . htmlspecialchars($item->getName()) . '</nazwa>';
            $input_xml .= '<kod_produktu>' . $item->getSku() .'</kod_produktu>';
            $input_xml .= '<produkt_id>' . $item->getProductId() . '</produkt_id>';
            $input_xml .= '<pkwiu></pkwiu>';
            $input_xml .= '<symbol_gtu></symbol_gtu>';
            $input_xml .= '<ilosc>' . $item->getQtyOrdered() . '</ilosc>';
            $input_xml .= '<jm>szt.</jm>';
            $input_xml .= '<vat>' . $item->getTaxPercent() . '</vat>';
            $input_xml .= '<wartosc_brutto>' . $item->getRowTotalInclTax() . '</wartosc_brutto>';
            $input_xml .= '</faktura_pozycje>';
        }

        return $input_xml;
    }

    private function getShippingDetails($order) {

        $input_xml = '<faktura_pozycje>';
        $input_xml .= '<nazwa>Dostawa</nazwa>';
        $input_xml .= '<ilosc>1</ilosc>';
        $input_xml .= '<jm>szt.</jm>';
        $input_xml .= '<vat>' . $order->getShippingTaxAmount() .'</vat>';
        $input_xml .= '<wartosc_brutto>'. $order->getShippingInclTax() . '</wartosc_brutto>';
        $input_xml .= '</faktura_pozycje>';

        return $input_xml;

    }

    private function sendXmlToFacturaXL($xmlData)
    {
        $this->logger->debug('FakturaXL: ' . $xmlData);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config->getApiEndpoint());
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        $returnXml = curl_exec($ch);
        curl_close($ch);

        $response = simplexml_load_string($returnXml);
        $this->logger->debug('Faktura została wysłana do FakturaXL: ' . print_r($response, true));
    }
}
