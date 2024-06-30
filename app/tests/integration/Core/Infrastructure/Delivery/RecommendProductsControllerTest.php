<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Infrastructure\Delivery;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecommendProductsControllerTest extends WebTestCase
{
    public function testRecommend() : void
    {
        $client = static::createClient();
        $client->request('GET', '/products/1/recommendation');

        $response = $client->getResponse();
        $body = json_decode($response->getContent() ?: '');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );

        $this->assertIsArray($body);
    }
}