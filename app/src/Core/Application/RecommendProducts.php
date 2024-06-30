<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Application;

use Symfony\Component\Validator\Constraints as Assert;

final class RecommendProducts
{
    /**
     * @Assert\NotBlank(allowNull = false)
     * @Assert\Type(type="string")
     */
    public function __construct(public string $productId)
    {
        $this->productId = $productId;
    }
}