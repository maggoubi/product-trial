<?php

namespace App\Controller;

use App\Dto\ProductDto;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/products')]
class ProductController extends AbstractController
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;
    private ProductService $productService;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        ProductService $productService
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->productService = $productService;
    }

    #[Route('', name: 'app_products_get_all', methods: ['GET'])]
    public function getAllProducts(): JsonResponse
    {
        $products = $this->productService->getAllProducts();
        
        return $this->json(
            $products,
            Response::HTTP_OK,
            [],
            ['groups' => ['product:read']]
        );
    }

    #[Route('/{id}', name: 'app_products_get_one', methods: ['GET'])]
    public function getProduct(int $id): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($id);
            
            return $this->json(
                $product,
                Response::HTTP_OK,
                [],
                ['groups' => ['product:read']]
            );
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('', name: 'app_products_create', methods: ['POST'])]
    #[IsGranted('ADMIN_ACCESS')]
    public function createProduct(Request $request): JsonResponse
    {
        try {
            $productDto = $this->serializer->deserialize($request->getContent(), ProductDto::class, 'json', ['groups' => ['product:write']]);
            
            $errors = $this->validator->validate($productDto);
            if (count($errors) > 0) {
                return $this->json(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
            }
            
            $product = $this->productService->createProduct($productDto);
            
            return $this->json(
                $product,
                Response::HTTP_CREATED,
                [],
                ['groups' => ['product:read']]
            );
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_products_update', methods: ['PATCH'])]
    #[IsGranted('ADMIN_ACCESS')]
    public function updateProduct(int $id, Request $request): JsonResponse
    {
        try {
            $updatedProductDto = $this->serializer->deserialize($request->getContent(), ProductDto::class, 'json', ['groups' => ['product:write']]);
            
            $errors = $this->validator->validate($updatedProductDto);
            if (count($errors) > 0) {
                return $this->json(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
            }
            
            $product = $this->productService->updateProduct($id, $updatedProductDto);
            
            return $this->json(
                $product,
                Response::HTTP_OK,
                [],
                ['groups' => ['product:read']]
            );
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_products_delete', methods: ['DELETE'])]
    #[IsGranted('ADMIN_ACCESS')]
    public function deleteProduct(int $id): JsonResponse
    {
        try {
            $this->productService->deleteProduct($id);
            
            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
