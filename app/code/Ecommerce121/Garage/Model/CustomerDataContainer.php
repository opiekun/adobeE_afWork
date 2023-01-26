<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Model;

class CustomerDataContainer
{
    /**
     * @var int|null
     */
    private $customerId;

    /**
     * @param int $customerId
     * @return CustomerDataContainer
     */
    public function setCustomerId(int $customerId): CustomerDataContainer
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }
}
