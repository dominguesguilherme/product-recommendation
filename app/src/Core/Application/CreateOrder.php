<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Application;

use DateTimeImmutable;
use ProductRecommendation\Framework\Id\Id;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateOrder
{
    public string $id;

    public DateTimeImmutable $createdAt;

    /**
     * @var OrderItemDTO[]
     * @SerializedName("items")
     * @Assert\Valid
     */
    public array $items;

    public function __construct(array $items)
    {
        $this->id = Id::generate()->toString();
        $this->createdAt = new DateTimeImmutable();
        $this->items = $items;
    }
}
