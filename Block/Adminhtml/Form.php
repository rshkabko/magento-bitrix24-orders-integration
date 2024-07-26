<?php

namespace Flamix\Bitrix24OrdersIntegration\Block\Adminhtml;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\View\Element\Template;

class Form extends Template
{
    protected $formFactory;
    protected $scopeConfig;

    public function __construct(
        Context $context,
        FormFactory $formFactory,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->formFactory = $formFactory;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        $form = $this->formFactory->create();

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Integration Settings'), 'class' => 'fieldset-wide']
        );

        $domain = $this->scopeConfig->getValue('flamix_integration/general/domain', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, null);
        $api = $this->scopeConfig->getValue('flamix_integration/general/api', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $find = $this->scopeConfig->getValue('flamix_integration/general/find', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $fieldset->addField(
            'domain',
            'text',
            [
                'name' => 'domain',
                'label' => __('Domain'),
                'title' => __('Domain'),
                'required' => true,
                'value' => $domain,
            ]
        );

        $fieldset->addField(
            'api',
            'text',
            [
                'name' => 'api',
                'label' => __('Api'),
                'title' => __('Api'),
                'required' => true,
                'value' => $api,
            ]
        );

        $fieldset->addField(
            'find',
            'select',
            [
                'name' => 'find',
                'label' => __('Find By'),
                'title' => __('Find By'),
                'values' => [
                    ['value' => 'disable', 'label' => __('Disable')],
                    ['value' => 'sku', 'label' => __('SKU')],
                    ['value' => 'name', 'label' => __('Name')],
                ],
                'value' => $find,
            ]
        );

        $this->setForm($form);
        return parent::_prepareLayout();
    }

    public function getFormHtml()
    {
        return $this->getForm()->toHtml();
    }
}
