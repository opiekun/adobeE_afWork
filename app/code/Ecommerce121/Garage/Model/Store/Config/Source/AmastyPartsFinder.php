<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Model\Store\Config\Source;

use Amasty\Finder\Model\ResourceModel\Finder\CollectionFactory as FinderCollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class AmastyPartsFinder implements OptionSourceInterface
{
    /**
     * @var FinderCollectionFactory
     */
    private $finderCollectionFactory;

    /**
     * @param FinderCollectionFactory $finderCollectionFactory
     */
    public function __construct(FinderCollectionFactory $finderCollectionFactory)
    {
        $this->finderCollectionFactory = $finderCollectionFactory;
    }

    /**
     * @inheridoc
     */
    public function toOptionArray(): array
    {
        $collection = $this->finderCollectionFactory->create();
        $result = [];

        foreach ($collection->getItems() as $item) {
            $result[] = [
                'value' => $item->getId(),
                'label' => $item->getName(),
            ];
        }

        return $result;
    }
}
