<?php
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Flamix_Bitrix24OrdersIntegration',
    __DIR__
);

// Init UTM
\Flamix\Bitrix24\SmartUTM::init();
