<?php declare(strict_types=1);

namespace Ecommerce121\StateFilter\Model\Config;

class RegionInformationProvider
{

    /**
     * @var \Magento\Directory\Api\CountryInformationAcquirerInterface
     */
    protected $countryInformationAcquirer;

    /**
     * RegionInformationProvider constructor.
     * @param \Magento\Directory\Api\CountryInformationAcquirerInterface $countryInformationAcquirer
     */
    public function __construct(
        \Magento\Directory\Api\CountryInformationAcquirerInterface $countryInformationAcquirer
    ) {
        $this->countryInformationAcquirer = $countryInformationAcquirer;
    }


    /**
     * toOptionArray
     *
     * @return array
     */
    public function toOptionArray()
    {
        $countries = $this->countryInformationAcquirer->getCountriesInfo();
        // Get regions for US:
        $regions = [];
        foreach ($countries as $country) {
            if ($country->getId() != 'US') {
                continue;
            }
            if ($availableRegions = $country->getAvailableRegions()) {
                foreach ($availableRegions as $region) {
                    $regions[] = [
                        'value' => $region->getName(),
                        'label' => $region->getName()
                    ];
                }
            }
        }

        return $regions;
    }
}
