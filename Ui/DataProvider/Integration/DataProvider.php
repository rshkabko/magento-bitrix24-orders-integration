<?php
namespace Flamix\Bitrix24OrdersIntegration\Ui\DataProvider\Integration;

use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    public function getData()
    {
        return [
            'items' => [
                [
                    'sample_field' => 'Sample Data'
                ]
            ],
            'totalRecords' => 1
        ];
    }
}
