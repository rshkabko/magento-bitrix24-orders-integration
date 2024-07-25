<?php

namespace Flamix\Bitrix24OrdersIntegration\Controller\Adminhtml\Integration;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Flamix_Bitrix24OrdersIntegration::integration');
        $resultPage->getConfig()->getTitle()->prepend(__('Integration Diagnostics'));
        return $resultPage;
    }
}
