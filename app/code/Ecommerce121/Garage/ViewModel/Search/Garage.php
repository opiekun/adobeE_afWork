<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\ViewModel\Search;

use Ecommerce121\Garage\Api\VehicleRepositoryInterface;
use Ecommerce121\Garage\Model\CustomerDataContainer;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\UrlInterface;

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
     * @var UrlInterface
     */
    private $url;

    /**
     * @param Json $json
     * @param VehicleRepositoryInterface $vehicleRepository
     * @param CustomerDataContainer $customerDataContainer
     * @param UrlInterface $url
     */
    public function __construct(
        Json                       $json,
        VehicleRepositoryInterface $vehicleRepository,
        CustomerDataContainer      $customerDataContainer,
        UrlInterface               $url
    )
    {
        $this->json = $json;
        $this->vehicleRepository = $vehicleRepository;
        $this->customerDataContainer = $customerDataContainer;
        $this->url = $url;
    }

    /**
     * @return string|null
     */
    public function getVehicles(): ?string
    {
        $customerId = (int)$this->customerDataContainer->getCustomerId();
        if (!$customerId) {
            return null;
        }

        $vehicles = $this->vehicleRepository->getVehiclesForCustomer($customerId);
        if (!$vehicles) {
            return null;
        }

        $searchItems = [];
        foreach ($vehicles as $vehicle) {
            $values = $vehicle->getValues();
            $labels = [];
            array_walk($values, function ($item) use (&$labels) {
                $labels[] = $item['name'];
            });

            $searchItems[] = [
                'label' => implode(' / ', $labels),
                'url' => $this->buildUrl($labels, $values),
                'values' => $values
            ];
        }

        return $this->json->serialize($searchItems);
    }

    /**
     * @param array $labels
     * @param array $values
     * @return string
     */
    private function buildUrl(array $labels, array $values): string
    {
        if (count($values) > 0) {
            $path = preg_replace('/[^\da-zA-Z]/', '-', strtolower(implode('-', $labels)));
            return $this->url->getBaseUrl()
                . 'amfinder?find='
                . $path
                . '-'
                . end($values)['value_id'] ?? '';
        }
        return "";
    }

}
