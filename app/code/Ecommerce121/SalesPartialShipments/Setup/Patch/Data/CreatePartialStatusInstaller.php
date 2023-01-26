<?php

declare(strict_types=1);

namespace Ecommerce121\SalesPartialShipments\Setup\Patch\Data;

use Ecommerce121\SalesPartialShipments\Constants;
use Magento\Framework\Setup\Patch\DataPatchInterface;

use Exception;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Status;
use Magento\Sales\Model\Order\StatusFactory;
use Magento\Sales\Model\ResourceModel\Order\Status as StatusResource;
use Magento\Sales\Model\ResourceModel\Order\StatusFactory as StatusResourceFactory;


class CreatePartialStatusInstaller implements DataPatchInterface
{
    /**
     * @var StatusFactory
     */
    protected StatusFactory $statusFactory;

    /**
     * @var StatusResourceFactory
     */
    protected StatusResourceFactory $statusResourceFactory;

    /**
     * @param StatusFactory $statusFactory
     * @param StatusResourceFactory $statusResourceFactory
     */
    public function __construct(
        StatusFactory $statusFactory,
        StatusResourceFactory $statusResourceFactory
    ) {
        $this->statusFactory = $statusFactory;
        $this->statusResourceFactory = $statusResourceFactory;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function apply(): CreatePartialStatusInstaller
    {

        /** @var StatusResource $statusResource */
        /** @var Status $status */
        $statusResource = $this->statusResourceFactory->create();
        $status = $this->statusFactory->create();
        $status->setData([
            'status' => Constants::ORDER_STATUS_PARTIALLY_SHIPPED_CODE,
            'label' => Constants::ORDER_STATUS_PARTIALLY_SHIPPED_LABEL,
        ]);

        try {
            $statusResource->save($status);
        } catch (AlreadyExistsException $exception) {
            return $this;
        }

        $status->assignState(Order::STATE_PROCESSING, false, true);

        return $this;
    }


    /**
     * @return array|string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public function getAliases(): array
    {
        return [];
    }
}