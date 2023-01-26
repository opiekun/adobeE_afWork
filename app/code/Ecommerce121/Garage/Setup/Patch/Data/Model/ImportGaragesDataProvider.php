<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Setup\Patch\Data\Model;

use Ecommerce121\Garage\Model\Store\Config;
use Magento\Framework\App\ResourceConnection;

class ImportGaragesDataProvider
{
    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    private $dropdowns;

    /**
     * @param Config $config
     * @param ResourceConnection $resource
     */
    public function __construct(Config $config, ResourceConnection $resource)
    {
        $this->resource = $resource;
        $this->config = $config;
    }

    /**
     * @param string $email
     * @return int
     */
    public function getCustomerId(string $email): int
    {
        $connection = $this->resource->getConnection();
        $select = $connection->select()
            ->from($connection->getTableName('customer_entity'), ['entity_id'])
            ->where('email = ?', $email);
        return (int) $connection->fetchOne($select);
    }

    /**
     * @param string $dropdownLabel
     * @return int|null
     */
    public function getDropdownId(string $dropdownLabel): ?int
    {
        if (null === $this->dropdowns) {
            $connection = $this->resource->getConnection();
            $select = $connection->select()
                ->from($connection->getTableName('amasty_finder_dropdown'), ['dropdown_id', 'name'])
                ->where('finder_id = ?', $this->config->getDefaultFinderId());

            $dropdowns = $connection->fetchPairs($select);

            array_walk($dropdowns, function ($dropdownLabel, $dropdownKey) {
                $this->dropdowns[strtolower($dropdownLabel)] = $dropdownKey;
            });
        }

        return $this->dropdowns[$dropdownLabel] ?? null;
    }

    /**
     * @param int $dropdownId
     * @param string $value
     * @param int $parentId
     * @return int|null
     */
    public function getValueId(int $dropdownId, string $value, int $parentId): int
    {
        $connection = $this->resource->getConnection();
        $select = $connection->select()
            ->from($connection->getTableName('amasty_finder_value'), ['value_id'])
            ->where('parent_id = ?', $parentId)
            ->where('dropdown_id = ?', $dropdownId)
            ->where('name = ?', $value);

        return (int) $connection->fetchOne($select);
    }
}
