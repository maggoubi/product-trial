<?php

namespace App\Service;

use App\Entity\Product;
use App\Dto\ProductDto;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductService
{
    private ProductRepository $productRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }

    public function getAllProducts(): array
    {
        return $this->productRepository->findAll();
    }

    public function getProductById(int $id): Product
    {
        $product = $this->productRepository->find($id);
        
        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }
        
        return $product;
    }

    public function createProduct(ProductDto $productdto): Product
    {
        $product = new Product();
		$this->mapDtoToProduct($productdto, $product);
		$this->entityManager->persist($product);
		$this->entityManager->flush();
		return $product;
    }

    public function updateProduct(int $id, ProductDto $productdto): Product
    {
        $product = $this->getProductById($id);
		$this->mapDtoToProduct($productdto, $product);
		$this->entityManager->flush();
		return $product;
    }

    public function deleteProduct(int $id): void
    {
        $product = $this->getProductById($id);
        $this->productRepository->remove($product, true);
    }
	
	private function mapDtoToProduct(ProductDto $dto, Product $product): void
	{
		$product->setCode($dto->getCode());
		$product->setName($dto->getName());
		$product->setDescription($dto->getDescription());
		$product->setImage($dto->getImage());
		$product->setCategory($dto->getCategory());
		$product->setPrice($dto->getPrice());
		$product->setQuantity($dto->getQuantity());
		$product->setInternalReference($dto->getInternalReference());
		$product->setShellId($dto->getShellId());
		$product->setInventoryStatus($dto->getInventoryStatus());
		$product->setRating($dto->getRating());
	}
}
