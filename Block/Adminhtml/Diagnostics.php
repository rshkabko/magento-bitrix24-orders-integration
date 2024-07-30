<?php
namespace Flamix\Bitrix24OrdersIntegration\Block\Adminhtml;

use Flamix\Bitrix24\Lead as Flamix24Lead;
use Flamix\Plugin\General\Checker;
use Magento\Backend\Block\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Diagnostics extends Template
{
    protected $scopeConfig;

    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    protected function _toHtml()
    {
        return "<div>
            <h2>Diagnostics</h2>
            <ul>{$this->check()}</ul>
        </div>";
    }

    private function check(): string
    {
        $domain = $this->scopeConfig->getValue('flamix_integration/general/domain', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $api = $this->scopeConfig->getValue('flamix_integration/general/api', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $working = $this->isWorking();
        $output = "<li>" . Checker::params('Status', $working === true, ['Working...', $working]) . "</li>";
        $output .= "<li>" . Checker::params('cURL', extension_loaded('curl')) . "</li>";
        $output .= "<li>" . Checker::params('PHP version', version_compare(PHP_VERSION, '7.4.0') >= 0) . "</li>";

        return $output;
    }

    public function isWorking()
    {
        $domain = $this->scopeConfig->getValue('flamix_integration/general/domain', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $api = $this->scopeConfig->getValue('flamix_integration/general/api', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $status = Flamix24Lead::getInstance()->changeSubDomain($this->getSubDomain())->setDomain($domain)->setToken($api)->send(['status' => 'check'], 'check');

        if (($status['status'] ?? 'error') === 'success') {
            return true;
        } else {
            return $status['msg'] ?? 'Unknown error';
        }

        return true;
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
}
