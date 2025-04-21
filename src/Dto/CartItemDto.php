<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CartItemDto
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    private int $productId;

    #[Assert\NotBlank]
    #[Assert\Positive]
    private int $quantity;

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): self
    {
        $this->productId = $productId;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }
}
