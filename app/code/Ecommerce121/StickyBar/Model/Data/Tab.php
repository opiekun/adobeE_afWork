<?php

declare(strict_types=1);

namespace Ecommerce121\StickyBar\Model\Data;

use Ecommerce121\StickyBar\Api\Data\TabInterface;

class Tab implements TabInterface
{
    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $link;

    /**
     * @var string|null
     */
    private $class;
    /**
     * @var int|null
     */
    private $sortOrder;

    /**
     * @param string $label
     * @param string $link
     * @param string|null $class (Optional)
     * @param int|null $sortOrder (Optional)
     */
    public function __construct(string $label, string $link, string $class = null, int $sortOrder = null)
    {
        $this->label = $label;
        $this->link = $link;
        $this->class = $class;
        $this->sortOrder = $sortOrder;
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @inheritDoc
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @inheritDoc
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * @inheritDoc
     */
    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }
}
