<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Application;

use DateTimeImmutable;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateOrder
{
    /** @Assert\NotBlank(allowNull = false) */
    public string $id;

    /** @Assert\NotBlank */
    public DateTimeImmutable $createdAt;

    /**
     * @var OrderItemDTO[]
     * @SerializedName("items")
     * @Assert\Valid
     */
    public array $items;

    public function __construct(string $id, DateTimeImmutable $createdAt, array $items)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->items = $items;
    }
}
