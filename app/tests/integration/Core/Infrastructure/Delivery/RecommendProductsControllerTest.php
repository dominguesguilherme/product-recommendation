<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Infrastructure\Delivery;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecommendProductsControllerTest extends WebTestCase
{
    public function testRecommendShouldReturnEmptyWhenThereIsNoRecommendation(): void
    {
        $client = static::createClient();
        $client->request('GET', '/products/ca8b71fe-0c86-4b61-b48b-2f594730ab07/recommendation');
        $expectedResponse = ['products' => []];

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );

        $actualBody = json_decode($response->getContent(), true);

        $this->assertEquals(
            $expectedResponse,
            $actualBody
        );
    }
}
