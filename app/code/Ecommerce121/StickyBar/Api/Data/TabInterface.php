<?php

declare(strict_types=1);

namespace Ecommerce121\StickyBar\Api\Data;

interface TabInterface
{
    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return string
     */
    public function getLink(): string;

    /**
     * @return string|null
     */
    public function getClass(): ?string;

    /**
     * @return int|null
     */
    public function getSortOrder(): ?int;
}
