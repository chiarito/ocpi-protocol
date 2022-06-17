<?php

declare(strict_types=1);

namespace Tests\Chargemap\OCPI\Versions\V2_2_1\Server\Sender\Sessions\Get;

use Chargemap\OCPI\Versions\V2_2_1\Common\Factories\SessionFactory;
use Chargemap\OCPI\Versions\V2_2_1\Server\Sender\Sessions\GetListing\SenderSessionGetListingRequest;
use Chargemap\OCPI\Versions\V2_2_1\Server\Sender\Sessions\GetListing\SenderSessionGetListingResponse;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase;
use Tests\Chargemap\OCPI\OcpiTestCase;
use Tests\Chargemap\OCPI\Versions\V2_2_1\Common\Models\SessionTest;

/**
 * @covers \Chargemap\OCPI\Versions\V2_2_1\Server\Sessions\SenderSessionGetListingResponse
 */
class ResponseConstructionTest extends TestCase
{
    public function testShouldReturnEmptyArrayWithoutTokens(): void
    {
        $response = new SenderSessionGetListingResponse(self::getRequest(), 0, 10);
        $responseInterface = $response->getResponseInterface();
        $this->assertSame([], json_decode($responseInterface->getBody()->getContents(), true)['data']);
    }

    private function getRequest(): SenderSessionGetListingRequest
    {
        return new SenderSessionGetListingRequest(
            Psr17FactoryDiscovery::findServerRequestFactory()->createServerRequest('GET', '/test?offset=10&limit=10')
                ->withQueryParams(['offset' => '10', 'limit' => '10'])
                ->withHeader('Authorization', 'Token 01234567-0123-0123-0123-0123456789ab')
        );
    }

    public function validPayloadsProvider(): iterable
    {
        foreach (scandir(__DIR__ . '/payloads/valid/') as $filename) {
            if ($filename !== '.' && $filename !== '..') {
                yield basename($filename, '.json') => [
                    'payload' => file_get_contents(__DIR__ . '/payloads/valid/' . $filename),
                ];
            }
        }
    }

    /**
     * @dataProvider validPayloadsProvider
     * @param string $payload
     */
    public function testShouldReturnDataWithSessions(string $payload): void
    {
        $response = new SenderSessionGetListingResponse(self::getRequest(), 0, 10);
        $sessions = [];
        foreach (json_decode($payload)->data as $index => $jsonSession) {
            $session = SessionFactory::fromJson($jsonSession);
            $sessions[$index] = $session;
            $response->addSession($session);
        }
        $responseInterface = $response->getResponseInterface();
        $payload = json_decode($responseInterface->getBody()->getContents());
        OcpiTestCase::coerce('V2_2_1/Sender/Sessions/sessionGetResponse.schema.json', $payload);
        foreach ($payload->data as $index => $jsonSession) {
            SessionTest::assertJsonSerialization($sessions[$index], $jsonSession);
        }
    }
}
