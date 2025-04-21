<?php

namespace App\Service;

use App\Entity\CartItem;
use App\Entity\User;
use App\Repository\CartItemRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartService
{
    private CartItemRepository $cartItemRepository;
    private ProductRepository $productRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        CartItemRepository $cartItemRepository,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->cartItemRepository = $cartItemRepository;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }

    public function getCartItems(User $user): array
    {
        return $this->cartItemRepository->findBy(['user' => $user]);
    }

    public function addToCart(User $user, int $productId, int $quantity): CartItem
    {
        $product = $this->productRepository->find($productId);
        
        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }
        
        $cartItem = $this->cartItemRepository->findOneByUserAndProduct($user, $product);
        
        if ($cartItem) {
            $cartItem->setQuantity($cartItem->getQuantity() + $quantity);
        } else {
            $cartItem = new CartItem();
            $cartItem->setUser($user);
            $cartItem->setProduct($product);
            $cartItem->setQuantity($quantity);
        }
        
        $this->cartItemRepository->save($cartItem, true);
        
        return $cartItem;
    }

    public function updateCartItem(User $user, int $cartItemId, int $quantity): CartItem
    {
        $cartItem = $this->cartItemRepository->find($cartItemId);
        
        if (!$cartItem || $cartItem->getUser() !== $user) {
            throw new NotFoundHttpException('Cart item not found');
        }
        
        $cartItem->setQuantity($quantity);
        $this->entityManager->flush();
        
        return $cartItem;
    }

    public function removeFromCart(User $user, int $cartItemId): void
    {
        $cartItem = $this->cartItemRepository->find($cartItemId);
        
        if (!$cartItem || $cartItem->getUser() !== $user) {
            throw new NotFoundHttpException('Cart item not found');
        }
        
        $this->cartItemRepository->remove($cartItem, true);
    }

    public function clearCart(User $user): void
    {
        $cartItems = $this->cartItemRepository->findBy(['user' => $user]);
        
        foreach ($cartItems as $cartItem) {
            $this->cartItemRepository->remove($cartItem, false);
        }
        
        $this->entityManager->flush();
    }
}
