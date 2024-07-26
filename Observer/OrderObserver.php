<?php

namespace Flamix\Bitrix24OrdersIntegration\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface;

class OrderObserver implements ObserverInterface
{
    protected $logger;
    protected $scopeConfig;

    public function __construct(
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $this->logger->info('New order placed: ' . $order->getIncrementId());

        // Получение значения конфигурации
        $sampleFieldValue = $this->scopeConfig->getValue('flamix_integration/general/sample_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->logger->info('Sample field value: ' . $sampleFieldValue);

        // Ваши действия при создании нового заказа
        // Например, отправка данных на указанный email или Bitrix24

        return $this;
    }
}
