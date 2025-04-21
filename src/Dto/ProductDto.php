<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

class ProductDto
{
    public const INVENTORY_STATUS_INSTOCK = 'INSTOCK';
    public const INVENTORY_STATUS_LOWSTOCK = 'LOWSTOCK';
    public const INVENTORY_STATUS_OUTOFSTOCK = 'OUTOFSTOCK';

    #[Assert\NotBlank]
    #[Groups(['product:write'])]
    private string $code;

    #[Assert\NotBlank]
    #[Groups(['product:write'])]
    private string $name;

    #[Groups(['product:write'])]
    private ?string $description = null;

    #[Groups(['product:write'])]
    private ?string $image = null;

    #[Assert\NotBlank]
    #[Groups(['product:write'])]
    private string $category;

    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[Groups(['product:write'])]
    private float $price;

    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[Groups(['product:write'])]
    private int $quantity;

    #[Assert\NotBlank]
    #[Groups(['product:write'])]
    private string $internalReference;

    #[Groups(['product:write'])]
    private ?int $shellId = null;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: [self::INVENTORY_STATUS_INSTOCK, self::INVENTORY_STATUS_LOWSTOCK, self::INVENTORY_STATUS_OUTOFSTOCK])]
    #[Groups(['product:write'])]
    private string $inventoryStatus = self::INVENTORY_STATUS_INSTOCK;

    #[Assert\Range(min: 0, max: 5)]
    #[Groups(['product:write'])]
    private ?float $rating = null;

    public function getCode(): string { return $this->code; }
    public function setCode(string $code): self { $this->code = $code; return $this; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $image): self { $this->image = $image; return $this; }

    public function getCategory(): string { return $this->category; }
    public function setCategory(string $category): self { $this->category = $category; return $this; }

    public function getPrice(): float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }

    public function getQuantity(): int { return $this->quantity; }
    public function setQuantity(int $quantity): self { $this->quantity = $quantity; return $this; }

    public function getInternalReference(): string { return $this->internalReference; }
    public function setInternalReference(string $internalReference): self { $this->internalReference = $internalReference; return $this; }

    public function getShellId(): ?int { return $this->shellId; }
    public function setShellId(?int $shellId): self { $this->shellId = $shellId; return $this; }

    public function getInventoryStatus(): string { return $this->inventoryStatus; }
    public function setInventoryStatus(string $inventoryStatus): self { $this->inventoryStatus = $inventoryStatus; return $this; }

    public function getRating(): ?float { return $this->rating; }
    public function setRating(?float $rating): self { $this->rating = $rating; return $this; }
}
