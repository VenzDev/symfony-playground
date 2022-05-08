<?php

declare(strict_types=1);

namespace App\Controller\Api\Product;

use App\Controller\Api\AbstractApiController;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api/v1/")]
class ProductController extends AbstractApiController
{
    #[Route("products/{id}", name: "api_products_get_one", methods: ["GET"])]
    public function getProduct(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return $this->error("cannot find product");
        }

        return $this->response($this->serialize($product));
    }

    #[Route("products", name: "api_products_get", methods: ["GET"])]
    public function getProducts(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->response($this->serialize($products));
    }


}
