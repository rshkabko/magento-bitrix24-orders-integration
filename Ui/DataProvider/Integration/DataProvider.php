<?php

namespace Flamix\Bitrix24OrdersIntegration\Ui\DataProvider\Integration;

use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Magento\Framework\Data\Collection $collectionFactory,
        array $addFieldStrategies = [],
        array $addFilterStrategies = []
    ) {
        $this->collection = $collectionFactory;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $addFieldStrategies, $addFilterStrategies);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $item) {
            $this->loadedData[$item->getId()] = $item->getData();
        }

        // Добавьте свои данные для отображения в форме
        $this->loadedData[0]['sample_field'] = 'Sample Data';

        return $this->loadedData;
    }
}
