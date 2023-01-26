<?php declare(strict_types=1);

namespace Ecommerce121\StateFilter\Plugin;

class StateFilter
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var string
     */
    public const US_STATE_FILTER = 'checkout/state_filter/us_state_filter';

    /**
     * StateFilter constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * afterToOptionArray
     *
     * @param  mixed $subject
     * @param  mixed $options
     * @return array
     */
    public function afterToOptionArray($subject, $options)
    {
        $allowedStates = $this->scopeConfig->getValue(self::US_STATE_FILTER, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $allowedUsStates = explode(",", $allowedStates);
        $result = array_filter($options, function ($option) use ($allowedUsStates) {
            if (isset($option['label'])) {
                return in_array($option['label'], $allowedUsStates);
            }
            return true;
        });

        return $result;
    }
}
