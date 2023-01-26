<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Setup\Patch\Data;

use Magento\Framework\File\Csv;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Ecommerce121\Garage\Setup\Patch\Data\Model\ImportGaragesDataProvider;
use Ecommerce121\Garage\Setup\Patch\Data\Model\ImportGaragesDataProcessor;
use Psr\Log\LoggerInterface;

class ImportGarages implements DataPatchInterface
{
    /**
     * @var ImportGaragesDataProvider
     */
    private $importGaragesDataProvider;

    /**
     * @var ImportGaragesDataProcessor
     */
    private $importGaragesDataProcessor;

    /**
     * @var SchemaSetupInterface
     */
    private $schemaSetup;

    /**
     * @var Csv
     */
    private $csv;

    /**
     * @var Reader
     */
    private $dirReader;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param SchemaSetupInterface $schemaSetup
     * @param Csv $csv
     * @param Reader $dirReader
     * @param ImportGaragesDataProvider $importGaragesDataProvider
     * @param LoggerInterface $logger
     * @param ImportGaragesDataProcessor $importGaragesDataProcessor
     */
    public function __construct(
        SchemaSetupInterface $schemaSetup,
        Csv $csv,
        Reader $dirReader,
        ImportGaragesDataProvider $importGaragesDataProvider,
        LoggerInterface $logger,
        ImportGaragesDataProcessor $importGaragesDataProcessor
    ) {
        $this->schemaSetup = $schemaSetup;
        $this->csv = $csv;
        $this->dirReader = $dirReader;
        $this->importGaragesDataProvider = $importGaragesDataProvider;
        $this->logger = $logger;
        $this->importGaragesDataProcessor = $importGaragesDataProcessor;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function apply(): ImportGarages
    {
        $this->schemaSetup->startSetup();

        $importData = [];

        foreach ($this->getGarageData() as $data) {
            $customerId = $this->importGaragesDataProvider->getCustomerId($data['email']);
            if (!$customerId) {
                $this->logger->critical('the customer does not exist', $data);
                continue;
            }

            $parentId = 0;
            $values = [];
            foreach ($data as $key => $value) {
                if ($key === 'email') {
                    continue;
                }

                if (!$value) {
                    break;
                }

                $dropdownId = $this->importGaragesDataProvider->getDropdownId($key);
                $valueId = $parentId = $this->importGaragesDataProvider->getValueId($dropdownId, $value, $parentId);
                if (!$valueId) {
                    $this->logger->critical("$key with value $value does not exist", $data);
                    continue 2;
                }

                $values[] = $valueId;
            }

            $importData[] = [
                'customer_id' => $customerId,
                'values' => $values,
            ];
        }

        if ($importData) {
            $this->importGaragesDataProcessor->process($importData);
        }

        $this->schemaSetup->endSetup();

        return $this;
    }

    /**
     * @return \Generator
     * @throws \Exception
     */
    private function getGarageData(): \Generator
    {
        $filePath = $this->dirReader->getModuleDir('Setup', 'Ecommerce121_Garage')
            . '/Patch/Fixtures/garages.csv';
        $this->csv->setDelimiter(';');
        $rows = $this->csv->getData($filePath);

        $keys = array_shift($rows);

        foreach ($rows as $row) {
            yield \array_combine($keys, $row);
        }
    }

    /**
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }
}
