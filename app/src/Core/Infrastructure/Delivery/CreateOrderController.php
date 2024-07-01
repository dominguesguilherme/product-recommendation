<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Infrastructure\Delivery;

use ProductRecommendation\Core\Application\CreateOrder;
use ProductRecommendation\Core\Application\CreateOrderHandler;
use ProductRecommendation\Core\Application\OrderItemDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CreateOrderController extends AbstractController
{
    public function __construct(private CreateOrderHandler $handler)
    {
    }

    /**
     * @Route("/orders", methods={"POST"}, name="product_recommendation_order_create")
     */
    public function create(Request $request, SerializerInterface $serializer): Response
    {
        $data = (string) $request->getContent();

        $command = $serializer->deserialize($data, CreateOrder::class, 'json');
        assert($command instanceof CreateOrder);

        $this->handler->handle($command);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
