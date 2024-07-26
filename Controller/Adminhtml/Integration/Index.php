<?php

namespace Flamix\Bitrix24OrdersIntegration\Controller\Adminhtml\Integration;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Controller\ResultFactory;
use Psr\Log\LoggerInterface;

class Index extends Action
{
    protected $configWriter;
    protected $logger;

    public function __construct(
        Context $context,
        WriterInterface $configWriter,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->configWriter = $configWriter;
        $this->logger = $logger;
    }

    protected function _isAllowed()
    {
        return true; // Временно установим в true для проверки
    }

    public function execute()
    {
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPostValue();
            $this->logger->info('Received post data:', $postData);

            if (isset($postData['sample_field'])) {
                $this->configWriter->save('flamix_integration/general/sample_field', $postData['sample_field']);
                $this->logger->info('Sample field saved: ' . $postData['sample_field']);
                $this->messageManager->addSuccessMessage(__('Settings have been saved.'));
            } else {
                $this->logger->info('Sample field not found in post data.');
                $this->messageManager->addErrorMessage(__('Sample field not found in post data.'));
            }
        }

        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Flamix_Bitrix24OrdersIntegration::integration');
        $resultPage->addBreadcrumb(__('Integration'), __('Integration'));
        $resultPage->addBreadcrumb(__('Integration Diagnostics'), __('Integration Diagnostics'));
        $resultPage->getConfig()->getTitle()->prepend(__('Integration Diagnostics'));

        return $resultPage;
    }
}
