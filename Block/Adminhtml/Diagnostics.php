<?php
namespace Flamix\Bitrix24OrdersIntegration\Block\Adminhtml;

use Magento\Backend\Block\Template;

class Diagnostics extends Template
{
    protected function _toHtml()
    {
        return '<div><h2>Diagnostics</h2><p>This is a custom HTML content block.</p></div>';
    }
}
