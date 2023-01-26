<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\ViewModel\Customer\Account;

use Ecommerce121\Garage\Api\Data\VehicleInterface;
use Ecommerce121\Garage\Api\VehicleRepositoryInterface;
use Ecommerce121\Garage\Model\CustomerDataContainer;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ecommerce121\Garage\Model\ResourceModel\Index\CollectionFactory as IndexedValueCollectionFactory;

class Garage implements ArgumentInterface
{
    /**
     * @var Json
     */
    private $json;

    /**
     * @var VehicleRepositoryInterface
     */
    private $vehicleRepository;

    /**
     * @var CustomerDataContainer
     */
    private $customerDataContainer;

    /**
     * @var RedirectInterface
     */
    private $redirect;

    private $indexedValueCollectionFactory;

    /**
     * @param Json $json
     * @param VehicleRepositoryInterface $vehicleRepository
     * @param CustomerDataContainer $customerDataContainer
     * @param RedirectInterface $redirect
     */
    public function __construct(
        Json $json,
        VehicleRepositoryInterface $vehicleRepository,
        CustomerDataContainer $customerDataContainer,
        RedirectInterface $redirect,
        IndexedValueCollectionFactory $indexedValueCollectionFactory
    ) {
        $this->json = $json;
        $this->vehicleRepository = $vehicleRepository;
        $this->customerDataContainer = $customerDataContainer;
        $this->redirect = $redirect;
        $this->indexedValueCollectionFactory = $indexedValueCollectionFactory;
    }

    /**
     * @return array
     */
    public function getVehicles(): array
    {
        $customerId = (int) $this->customerDataContainer->getCustomerId();
        return $this->vehicleRepository->getVehiclesForCustomer($customerId);
    }

    /**
     * @param VehicleInterface $vehicle
     * @return string
     */
    public function getFormattedValues(VehicleInterface $vehicle): string
    {
        $values = [];
        foreach ($vehicle->getValues() as $key => $items) {
            $values[$key] = $items['value_id'];
        }

        return $this->json->serialize($values);
    }

    /**
     * @param VehicleInterface $vehicle
     * @return string
     */
    public function getSelectedValues(VehicleInterface $vehicle): string
    {
        $values = [];
        foreach ($vehicle->getValues() as $key => $items) {

            $indexedValueCollection = $this->indexedValueCollectionFactory->create();
            $indexedValueCollection->addFieldToFilter('dropdown_id', $key);
            $indexedValueCollection->addFieldToFilter('value_id', $items['value_id']);

            foreach ($indexedValueCollection->getItems() as $item) {
                $values[$key][] = [
                    'dropdown_id' => $key,
                    'value' => $item->getName(),
                    'parent_id' => $item->getParentId(),
                    'value_id' => $item->getValueId(),
                ];
            }
        }

        return $this->json->serialize($values);
    }


    /**
     * @param array $vehicles
     * @return string
     */
    public function getVehicleComponentNames(array $vehicles): string
    {
        $vehicleComponents = ['ecommerce121-garage-vehicle'];
        foreach ($vehicles as $vehicle) {
            $vehicleComponents[] = 'ecommerce121-garage-vehicle-' . $vehicle->getId();
        }

        return $this->json->serialize($vehicleComponents);
    }

    /**
     * @return string
     */
    public function getBackUrl(): string
    {
        return $this->redirect->getRefererUrl();
    }
}
