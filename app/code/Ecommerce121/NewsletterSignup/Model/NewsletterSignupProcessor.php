<?php

declare(strict_types=1);

namespace Ecommerce121\NewsletterSignup\Model;

use Ecommerce121\NewsletterSignup\Model\HubSpot\Service as HubSpotService;
use Ecommerce121\NewsletterSignup\Model\Store\Config;
use Magento\Framework\Exception\LocalizedException;

class NewsletterSignupProcessor
{
    /**
     * @var HubSpotService
     */
    private $hubSpotService;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param HubSpotService $hubSpotService
     * @param Config $config
     */
    public function __construct(HubSpotService $hubSpotService, Config $config)
    {
        $this->hubSpotService = $hubSpotService;
        $this->config = $config;
    }

    /**
     * @param array $params
     * @return bool
     * @throws LocalizedException
     */
    public function process(array $params): bool
    {
        return $this->hubSpotService->execute($this->getRequestParams($params));
    }

    /**
     * @param array $params
     * @return array
     * @throws LocalizedException
     */
    private function getRequestParams(array $params): array
    {
        $requestParams = [];
        $requestParameterMapping = $this->config->getRequestParameterMapping();
        if (!$requestParameterMapping) {
            throw new LocalizedException(__('Mapping has not been set'));
        }

        foreach ($requestParameterMapping as $mapper) {
            if (isset($params[$mapper['form_input_name']])) {
                $requestParams[$mapper['hubspot_parameter_name']] = $params[$mapper['form_input_name']];
            }
        }

        return $requestParams;
    }
}
