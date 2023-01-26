<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Setup\Patch\Schema;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;

class AddForeignKeyAmastyFinderValue implements SchemaPatchInterface
{
    /**
     * @var SchemaSetupInterface
     */
    private $schemaSetup;

    /**
     * @param SchemaSetupInterface $schemaSetup
     */
    public function __construct(
        SchemaSetupInterface $schemaSetup
    ) {
        $this->schemaSetup = $schemaSetup;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $this->schemaSetup->startSetup();

        $foreignKeyName = $this->schemaSetup->getFkName(
            'ecommerce121_garage_vehicle_values',
            'value_id',
            'amasty_finder_value',
            'value_id'
        );

        $this->schemaSetup->getConnection()
            ->addForeignKey(
                $foreignKeyName,
                'ecommerce121_garage_vehicle_values',
                'value_id',
                'amasty_finder_value',
                'value_id'
            );

        $this->schemaSetup->endSetup();
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }
}
