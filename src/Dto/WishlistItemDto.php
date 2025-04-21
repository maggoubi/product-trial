<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class WishlistItemDto
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    private int $productId;

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): self
    {
        $this->productId = $productId;
        return $this;
    }
}
