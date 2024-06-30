<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Infrastructure\Delivery;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use ProductRecommendation\Core\Application\RecommendProducts;
use ProductRecommendation\Core\Application\RecommendProductsHandler;

class RecommendProductsController extends AbstractController
{
    public function __construct(private RecommendProductsHandler $handler)
    {
    }

    /**
     * @Route("/products/{id}/recommendation", methods={"GET"}, name="product_recommendation_recommend")
     */
    public function recommend(Request $request, SerializerInterface $serializer) : Response
    {
        $id = $request->attributes->get('id', '');

        $command = new RecommendProducts($id);

        $recommendation = $this->handler->handle($command);

        return new JsonResponse($recommendation, Response::HTTP_OK);
    }
}