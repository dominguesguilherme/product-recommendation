<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Infrastructure\Delivery;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateOrderControllerTest extends WebTestCase
{
    public function testCreate() : void
    {
        $client = static::createClient();
        $client->request('POST', '/orders', [], [], ['CONTENT_TYPE' => 'application/json'], (string) json_encode([
            'id' => '1',
            'createdAt' => '2021-01-01',
            'items' => [
                [
                    'id' => '1',
                    'productId' => '1',
                    'unitPrice' => 10.0,
                    'quantity' => 1,
                ],
                [
                    'id' => '2',
                    'productId' => '2',
                    'unitPrice' => 20.0,
                    'quantity' => 2,
                ],
            ],
        ]));

        $response = $client->getResponse();

        $this->assertEquals(201, $response->getStatusCode());
    }
}