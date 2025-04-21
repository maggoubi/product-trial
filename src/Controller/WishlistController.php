<?php

namespace App\Controller;

use App\Dto\WishlistItemDto;
use App\Service\WishlistService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/wishlist')]
class WishlistController extends AbstractController
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;
    private WishlistService $wishlistService;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        WishlistService $wishlistService
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->wishlistService = $wishlistService;
    }

    #[Route('', name: 'app_wishlist_get', methods: ['GET'])]
    public function getWishlist(): JsonResponse
    {
        $user = $this->getUser();
        $wishlistItems = $this->wishlistService->getWishlistItems($user);
        
        return $this->json(
            $wishlistItems,
            Response::HTTP_OK,
            [],
            ['groups' => ['wishlist:read']]
        );
    }

    #[Route('', name: 'app_wishlist_add', methods: ['POST'])]
    public function addToWishlist(Request $request): JsonResponse
    {
        try {
            $wishlistItemDto = $this->serializer->deserialize($request->getContent(), WishlistItemDto::class, 'json');
            
            $errors = $this->validator->validate($wishlistItemDto);
            if (count($errors) > 0) {
                return $this->json(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
            }
            
            $user = $this->getUser();
            $wishlistItem = $this->wishlistService->addToWishlist(
                $user,
                $wishlistItemDto->getProductId()
            );
            
            return $this->json(
                $wishlistItem,
                Response::HTTP_CREATED,
                [],
                ['groups' => ['wishlist:read']]
            );
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_wishlist_remove', methods: ['DELETE'])]
    public function removeFromWishlist(int $id): JsonResponse
    {
        try {
            $user = $this->getUser();
            $this->wishlistService->removeFromWishlist($user, $id);
            
            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('', name: 'app_wishlist_clear', methods: ['DELETE'])]
    public function clearWishlist(): JsonResponse
    {
        $user = $this->getUser();
        $this->wishlistService->clearWishlist($user);
        
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
