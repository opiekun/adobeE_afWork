<?php

declare(strict_types=1);

namespace Ecommerce121\NewsletterSignup\Model\HubSpot;

use Ecommerce121\NewsletterSignup\Model\Store\Config;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\HTTP\Client\CurlFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

class Service
{
    /**
     * @var Config
     */
    private $storeConfig;

    /**
     * @var CurlFactory
     */
    private $curlFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var CookieManagerInterface
     */
    private $cookieManager;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * @param CurlFactory $curlFactory
     * @param Config $storeConfig
     * @param LoggerInterface $logger
     * @param CookieManagerInterface $cookieManager
     * @param Json $json
     * @param RemoteAddress $remoteAddress
     */
    public function __construct(
        CurlFactory $curlFactory,
        Config $storeConfig,
        LoggerInterface $logger,
        CookieManagerInterface $cookieManager,
        Json $json,
        RemoteAddress $remoteAddress
    ) {
        $this->curlFactory = $curlFactory;
        $this->storeConfig = $storeConfig;
        $this->logger = $logger;
        $this->cookieManager = $cookieManager;
        $this->json = $json;
        $this->remoteAddress = $remoteAddress;
    }

    /**
     * @param array $params
     * @return bool
     */
    public function execute(array $params): bool
    {
        $params = $this->addParams($params);
        $url = $this->storeConfig->getEndpoint();
        try {
            /** @var Curl $curl */
            $curl = $this->curlFactory->create();
            $curl->setHeaders($this->getHeaders());
            $curl->post($url, $params);

            return true;
        } catch (\Exception $e) {
            $this->logger->critical(
                $e->getMessage(),
                [
                    'url' => $url,
                    'headers' => $this->getHeaders()
                ]
            );

            return false;
        }
    }

    /**
     * @return array
     */
    private function getHeaders(): array
    {
        return [
            'Content-Type: application/x-www-form-urlencoded'
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    private function addParams(array $params): array
    {
        $hsContext = $this->json->serialize([
            'hutk' => $this->cookieManager->getCookie('hubspotutk'),
            'ipAddress' => $this->remoteAddress->getRemoteAddress(),
            'pageUrl' => 'http://afepower.com/newsletter-signup',
            'pageName' => 'newsletter-signup'
        ]);

        $params['lead_source'] = 'newsletter-signup';
        $params['hs_context'] = urlencode($hsContext);
        return $params;
    }
}
