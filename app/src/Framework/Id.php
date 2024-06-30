<?php

declare(strict_types=1);

namespace ProductRecommendation\Framework;

use Ramsey\Uuid\Uuid;

/**
 * @psalm-immutable
 */
final class Id
{
    private string $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromString(string $idAsString): self
    {
        $id = Uuid::fromString($idAsString);
        return new self($id->toString());
    }

    public static function generate(): self
    {
        $id = Uuid::uuid4();

        return new self($id->toString());
    }

    public function toString(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function isEqualTo(Id $id): bool
    {
        return $this->toString() === $id->toString();
    }
}
