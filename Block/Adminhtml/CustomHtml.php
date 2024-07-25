<?php
namespace Flamix\Bitrix24OrdersIntegration\Block\Adminhtml;

use Magento\Backend\Block\Template;

class CustomHtml extends Template
{
    protected function _toHtml()
    {
        return '<div><h2>Custom HTML Content</h2><p>This is a custom HTML content block.</p></div>';
    }
}
