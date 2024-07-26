<?php

namespace Flamix\Bitrix24OrdersIntegration\Controller\Adminhtml\Integration;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Controller\ResultFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ReinitableConfigInterface;

class Index extends Action
{
    protected $configWriter;
    protected $logger;
    protected $reinitableConfig;

    public function __construct(
        Context $context,
        WriterInterface $configWriter,
        LoggerInterface $logger,
        ReinitableConfigInterface $reinitableConfig
    ) {
        parent::__construct($context);
        $this->configWriter = $configWriter;
        $this->logger = $logger;
        $this->reinitableConfig = $reinitableConfig;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Flamix_Bitrix24OrdersIntegration::integration');
    }

    public function execute()
    {
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPostValue();

            if (isset($postData['domain'])) {
                $this->configWriter->save('flamix_integration/general/domain', $postData['domain']);
            }
            if (isset($postData['api'])) {
                $this->configWriter->save('flamix_integration/general/api', $postData['api']);
            }
            if (isset($postData['find'])) {
                $this->configWriter->save('flamix_integration/general/find', $postData['find']);
            }

            // Reinit config (cached)
            $this->reinitableConfig->reinit();

            $this->messageManager->addSuccessMessage(__('Settings have been saved.'));

            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath('*/*/index');
            return $resultRedirect;
        }

        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Flamix_Bitrix24OrdersIntegration::integration');
        $resultPage->addBreadcrumb(__('Integration'), __('Integration'));
        $resultPage->addBreadcrumb(__('Integration Settings'), __('Integration Settings'));
        $resultPage->getConfig()->getTitle()->prepend(__('Integration Settings'));

        return $resultPage;
    }
}
