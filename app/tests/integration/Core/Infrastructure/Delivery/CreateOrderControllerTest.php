<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Infrastructure\Delivery;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use ProductRecommendation\Framework\Id\Id;

class CreateOrderControllerTest extends WebTestCase
{
    public function testCreate() : void
    {
        $client = static::createClient();
        $client->request('POST', '/orders', [], [], ['CONTENT_TYPE' => 'application/json'], (string) json_encode([
            'items' => [
                [
                    'productId' => Id::generate()->toString(),
                    'unitPrice' => 10.0,
                    'quantity' => 1,
                ],
                [
                    'productId' => Id::generate()->toString(),
                    'unitPrice' => 20.0,
                    'quantity' => 2,
                ],
            ],
        ]));

        $response = $client->getResponse();

        $this->assertEquals(201, $response->getStatusCode());
    }
}