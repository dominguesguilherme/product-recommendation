<?php

declare(strict_types=1);

namespace ProductRecommendation\Core\Infrastructure\Delivery;

use DateTimeImmutable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HealthcheckController extends AbstractController
{
    /**
     * @Route("/healthcheck", methods={"GET"}, name="product_recommendation_healthcheck")
     */
    public function check() : Response
    {
        $now = new DateTimeImmutable();

        return new JsonResponse([
            'msg' => 'ok',
            'datetime' => $now->format('Y-m-d\TH:i:s\Z'),
            'timestamp' => $now->format('U'),
        ]);
    }
}
