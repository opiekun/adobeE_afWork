<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Model\ResourceModel;

use Magento\Framework\DB\Select;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Vehicle extends AbstractDb
{
    const TABLE_NAME = 'ecommerce121_garage_vehicle';

    /**
     * @return void
     */
    // @codingStandardsIgnoreLine
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'id');
    }

    /**
     * @inheritdoc
     */
    // @codingStandardsIgnoreLine
    protected function _afterSave(AbstractModel $object)
    {
        if (is_array($object->getValues())) {
            $vehicleId = $object->getId();
            $values = [];
            foreach ($object->getValues() as $valueId) {
                $values[] = [
                    'vehicle_id' => $vehicleId,
                    'value_id' => $valueId,
                ];
            }

            $this->getConnection()->insertMultiple('ecommerce121_garage_vehicle_values', $values);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    // @codingStandardsIgnoreLine
    protected function _beforeSave(AbstractModel $object)
    {
        if ($object->getId()) {
            $this->deleteValues((int) $object->getId());
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    // @codingStandardsIgnoreLine
    protected function _afterLoad(AbstractModel $object)
    {
        $values = $this->getValues([$object->getId()]);
        $object->setValues($values[$object->getId()] ?? []);

        return $this;
    }

    /**
     * @param array $vehicleIds
     * @return array
     * @throws \Zend_Db_Statement_Exception
     */
    public function getValues(array $vehicleIds): array
    {
        $select = $this->getConnection()->select();
        $select->from('ecommerce121_garage_vehicle_values')->where('vehicle_id IN (?)', $vehicleIds);
        $select->joinLeft(
            'amasty_finder_value',
            'ecommerce121_garage_vehicle_values.value_id = amasty_finder_value.value_id'
        );
        $select->reset(Select::COLUMNS);
        $select->columns([
            'vehicle_id' => 'ecommerce121_garage_vehicle_values.vehicle_id',
            'value_id' => 'ecommerce121_garage_vehicle_values.value_id',
            'dropdown_id' => 'amasty_finder_value.dropdown_id',
            'name' => 'amasty_finder_value.name',
        ]);

        $query = $this->getConnection()->query($select);
        $result = [];
        while ($item = $query->fetch()) {
            $result[$item['vehicle_id']][$item['dropdown_id']] = [
                'value_id' => $item['value_id'],
                'name' => $item['name'],
            ];
        }

        return $result;
    }

    /**
     * @param int $vehicleId
     */
    private function deleteValues(int $vehicleId)
    {
        $this->getConnection()->delete('ecommerce121_garage_vehicle_values', ['vehicle_id = ?' => $vehicleId]);
    }
}
