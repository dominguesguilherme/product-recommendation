<?php

declare(strict_types=1);

namespace ProductRecommendation\Tests\Core\Infrastructure\Delivery;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use function json_decode;

class HealthcheckControllerTest extends WebTestCase
{
    public function testCheck() : void
    {
        $client = static::createClient();
        $client->request('GET', '/healthcheck');

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
        $this->assertEquals('ok', $body->msg);
    }
}