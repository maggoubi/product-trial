<?php

namespace App\Service;

use App\Entity\WishlistItem;
use App\Entity\User;
use App\Repository\WishlistItemRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WishlistService
{
    private WishlistItemRepository $wishlistItemRepository;
    private ProductRepository $productRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        WishlistItemRepository $wishlistItemRepository,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->wishlistItemRepository = $wishlistItemRepository;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }

    public function getWishlistItems(User $user): array
    {
        return $this->wishlistItemRepository->findBy(['user' => $user]);
    }

    public function addToWishlist(User $user, int $productId): WishlistItem
    {
        $product = $this->productRepository->find($productId);
        
        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }
        
        // Check if product already in wishlist
        $wishlistItem = $this->wishlistItemRepository->findOneByUserAndProduct($user, $product);
        
        if (!$wishlistItem) {
            // Create new wishlist item
            $wishlistItem = new WishlistItem();
            $wishlistItem->setUser($user);
            $wishlistItem->setProduct($product);
            
            $this->wishlistItemRepository->save($wishlistItem, true);
        }
        
        return $wishlistItem;
    }

    public function removeFromWishlist(User $user, int $wishlistItemId): void
    {
        $wishlistItem = $this->wishlistItemRepository->find($wishlistItemId);
        
        if (!$wishlistItem || $wishlistItem->getUser() !== $user) {
            throw new NotFoundHttpException('Wishlist item not found');
        }
        
        $this->wishlistItemRepository->remove($wishlistItem, true);
    }

    public function clearWishlist(User $user): void
    {
        $wishlistItems = $this->wishlistItemRepository->findBy(['user' => $user]);
        
        foreach ($wishlistItems as $wishlistItem) {
            $this->wishlistItemRepository->remove($wishlistItem, false);
        }
        
        $this->entityManager->flush();
    }
}