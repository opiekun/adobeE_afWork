<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\ViewModel\Customer\Account;

use Ecommerce121\Garage\Model\ResourceModel\DefaultFormValues;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Serialize\Serializer\Json;

class FormData implements ArgumentInterface
{
    /**
     * @var DefaultFormValues
     */
    private $defaultFormValues;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @param DefaultFormValues $defaultFormValues
     * @param Json $json
     * @param UrlInterface $url
     */
    public function __construct(
        DefaultFormValues $defaultFormValues,
        Json $json,
        UrlInterface $url
    ) {
        $this->defaultFormValues = $defaultFormValues;
        $this->json = $json;
        $this->url = $url;
    }

    /**
     * @param bool $excludeHideInMenuItems
     * @return string
     */
    public function getDropdowns(bool $excludeHideInMenuItems = false): string
    {
        return $this->json->serialize($this->defaultFormValues->getFormDropdowns($excludeHideInMenuItems));
    }

    /**
     * @param bool $excludeHideInMenuItems
     * @return string
     */
    public function getValues(bool $excludeHideInMenuItems = false): string
    {
        return $this->json->serialize($this->defaultFormValues->getFormValues($excludeHideInMenuItems));
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url->getBaseUrl() . 'garage/dropdown/data';
    }
}
