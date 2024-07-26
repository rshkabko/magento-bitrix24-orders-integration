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

        $sampleFieldValue = $this->scopeConfig->getValue('flamix_integration/general/sample_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $fieldset->addField(
            'sample_field',
            'text',
            [
                'name' => 'sample_field',
                'label' => __('Sample Field'),
                'title' => __('Sample Field'),
                'required' => true,
                'value' => $sampleFieldValue,
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
