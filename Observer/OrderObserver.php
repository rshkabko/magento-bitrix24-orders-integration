<?php

namespace Flamix\Bitrix24OrdersIntegration\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface;
use Flamix\Bitrix24\Lead as Flamix24Lead;

class OrderObserver implements ObserverInterface
{
    protected $logger;
    protected $scopeConfig;

    public function __construct(LoggerInterface $logger, ScopeConfigInterface $scopeConfig)
    {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $this->logger->info("Flamix24: Start order obser to #{$order->getIncrementId()}");
        // Configuration
        $domain = $this->scopeConfig->getValue('flamix_integration/general/domain', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $api = $this->scopeConfig->getValue('flamix_integration/general/api', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $find = $this->scopeConfig->getValue('flamix_integration/general/find', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (empty($domain) || empty($api)) {
            $this->logger->error('Flamix24: Domain or API key is empty!');
            return $this;
        }

        $fields = $this->fields($order->getData(), $order->getShippingAddress()->getData());
        $find = strtoupper($find) === 'DISABLED' ? null : strtoupper($find);
        $products = $this->product($order->getAllItems(), $find);
        $bitrix24 = [
            'FIELDS' => $fields,
            'PRODUCTS' => $products,
            'STATUS' => strtoupper($order->getData('status')),
            'CURRENCY' => strtoupper($order->getOrderCurrencyCode()),
        ];

        // Try to send order to Bitrix24
        $this->logger->info("Flamix24: Order #{$order->getIncrementId()} will sent to Bitrix24", $bitrix24);
        //dd("Order #{$order->getIncrementId()} has been created!", $bitrix24); // Debug!
        try {
            $status = Flamix24Lead::getInstance()->changeSubDomain($this->getSubDomain())->setDomain($domain)->setToken($api)->send($bitrix24);
        } catch (\Exception $e) {
            $this->logger->error("Flamix24: Order #{$order->getIncrementId()} has not been sent to Bitrix24! Error: {$e->getMessage()}");
        }
        return $this;
    }

    /**
     * Determine the subdomain.
     *
     * @return string
     */
    private function getSubDomain(): string
    {
        return $_SERVER['SERVER_NAME'] === 'magento.test.chosten.com' ? 'devlead' : 'leadmagento';
    }

    /**
     * Prepare fields to Flamix format.
     *
     * @param  array  $fields
     * @param  array  $shipping
     * @return array
     */
    private function fields(array $fields, array $shipping): array
    {
        $fields = [
            'ORDER_ID' => $fields['increment_id'],
            'EMAIL' => $shipping['customer_email'] ?? null,
            'PHONE' => $fields['telephone'] ?? null,
            'FIRSTNAME' => $fields['customer_firstname'] ?? null,
            'LASTNAME' => $fields['customer_lastname'] ?? null,
            'NOTE' => $fields['customer_note'] ?? null,
            'VAT' => $shipping['vat_id'] ?? null,
            'COMPANY' => $shipping['company'] ?? null,
            'SHIPPING_PRICE' => $fields['shipping_amount'] ?? null,
            'SHIPPING_CITY' => $shipping['city'] ?? null,
            'SHIPPING_REGION' => $shipping['region'] ?? null,
            'SHIPPING_ZIP' => $shipping['postcode'] ?? null,
            'SHIPPING_STREET' => $shipping['street'] ?? null,
        ];

        return $fields;
    }

    /**
     * Prepare products to Flamix format.
     *
     * @param  array  $products
     * @param  string|null  $findby
     * @return array
     */
    private function product(array $products, ?string $findby = null): array
    {
        foreach ($products as $product) {
            $tmp = [
                'ID' => $product->getProductId(),
                'NAME' => $product->getName(),
                'SKU' => $product->getSku(),
                'PRICE' => $product->getPrice(),
                'QUANTITY' => $product->getQtyOrdered(),
            ];

            if ($findby) {
                $tmp['FIND_BY'] = $findby;
            }

            $flamix_products[] = $tmp;
        }

        return $flamix_products ?? [];
    }
}
