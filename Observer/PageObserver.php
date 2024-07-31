<?php

namespace Flamix\Bitrix24OrdersIntegration\Observer;

use Flamix\Bitrix24\Trace;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Page\Config as PageConfig;

class PageObserver implements ObserverInterface
{
    protected $request;
    protected $pageConfig;

    public function __construct(
        RequestInterface $request,
        PageConfig $pageConfig
    ) {
        $this->request = $request;
        $this->pageConfig = $pageConfig;
    }

    public function execute(Observer $observer)
    {
        if (!$this->request->isXmlHttpRequest()) {
            $title = $this->pageConfig->getTitle()->get();
            if (!empty($title)) {
                Trace::init($title);
            }
        }
    }
}
