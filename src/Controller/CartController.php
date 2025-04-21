<?php

namespace App\Controller;

use App\Dto\CartItemDto;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/cart')]
class CartController extends AbstractController
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;
    private CartService $cartService;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        CartService $cartService
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->cartService = $cartService;
    }

    #[Route('', name: 'app_cart_get', methods: ['GET'])]
    public function getCart(): JsonResponse
    {
        $user = $this->getUser();
        $cartItems = $this->cartService->getCartItems($user);
        
        return $this->json(
            $cartItems,
            Response::HTTP_OK,
            [],
            ['groups' => ['cart:read']]
        );
    }

    #[Route('', name: 'app_cart_add', methods: ['POST'])]
    public function addToCart(Request $request): JsonResponse
    {
        try {
            $cartItemDto = $this->serializer->deserialize($request->getContent(), CartItemDto::class, 'json');
            
            $errors = $this->validator->validate($cartItemDto);
            if (count($errors) > 0) {
                return $this->json(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
            }
            
            $user = $this->getUser();
            $cartItem = $this->cartService->addToCart(
                $user,
                $cartItemDto->getProductId(),
                $cartItemDto->getQuantity()
            );
            
            return $this->json(
                $cartItem,
                Response::HTTP_CREATED,
                [],
                ['groups' => ['cart:read']]
            );
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_cart_update', methods: ['PUT'])]
    public function updateCartItem(int $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $quantity = $data['quantity'] ?? null;
            
            if ($quantity === null || $quantity < 1) {
                return $this->json(['error' => 'Invalid quantity'], Response::HTTP_BAD_REQUEST);
            }
            
            $user = $this->getUser();
            $cartItem = $this->cartService->updateCartItem($user, $id, $quantity);
            
            return $this->json(
                $cartItem,
                Response::HTTP_OK,
                [],
                ['groups' => ['cart:read']]
            );
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_cart_remove', methods: ['DELETE'])]
    public function removeFromCart(int $id): JsonResponse
    {
        try {
            $user = $this->getUser();
            $this->cartService->removeFromCart($user, $id);
            
            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('', name: 'app_cart_clear', methods: ['DELETE'])]
    public function clearCart(): JsonResponse
    {
        $user = $this->getUser();
        $this->cartService->clearCart($user);
        
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
