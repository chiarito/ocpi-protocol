<?php

declare(strict_types=1);

namespace Tests\Chargemap\OCPI\Versions\V2_2_1\Server\Common\Versions\Details\Get;

use Chargemap\OCPI\Common\Server\Models\VersionNumber;
use Chargemap\OCPI\Versions\V2_2_1\Common\Models\Endpoint;
use Chargemap\OCPI\Versions\V2_2_1\Common\Models\InterfaceRole;
use Chargemap\OCPI\Versions\V2_2_1\Common\Models\ModuleId;
use Chargemap\OCPI\Versions\V2_2_1\Server\Common\Versions\Details\Get\OcpiVersionDetailsGetResponse;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ResponseConstructionTest extends TestCase
{
    public function testShouldFailWithoutEndpoints()
    {
        $response = new OcpiVersionDetailsGetResponse(VersionNumber::VERSION_2_2_1(), 'Message!');

        $this->expectException(InvalidArgumentException::class);
        $response->getResponseInterface();
    }

    public function testShouldConstructWithEndpoint()
    {
        $response = new OcpiVersionDetailsGetResponse(VersionNumber::VERSION_2_2_1(), 'Message!');
        $response->addEndpoint(new Endpoint(
            ModuleId::CDRS(),
            InterfaceRole::SENDER(),
            'versionurl/2.2.1/cdrs'
        ));
        $responseInterface = $response->getResponseInterface();
        $this->assertEquals([
            'version' => VersionNumber::VERSION_2_2_1,
            'endpoints' => [
                [
                    'identifier' => ModuleId::CDRS(),
                    'role' => 'SENDER',
                    'url' => 'versionurl/2.2.1/cdrs'
                ]
            ]
        ], json_decode($responseInterface->getBody()->getContents(), true)['data']);
    }

    public function testShouldConstructWithMultipleEndpoints()
    {
        $response = new OcpiVersionDetailsGetResponse(VersionNumber::VERSION_2_2_1(), 'Message!');
        $response
            ->addEndpoint(new Endpoint(
                ModuleId::CDRS(),
                InterfaceRole::RECEIVER(),
                'versionurl/2.2.1/cdrs'
            ))->addEndpoint(new Endpoint(
                ModuleId::CRED_AND_REG(),
                InterfaceRole::SENDER(),
                'versionurl/2.2.1/credentials'
            ));
        $responseInterface = $response->getResponseInterface();
        $this->assertEquals([
            'version' => VersionNumber::VERSION_2_2_1,
            'endpoints' => [
                [
                    'identifier' => ModuleId::CDRS(),
                    'role' => 'RECEIVER',
                    'url' => 'versionurl/2.2.1/cdrs'
                ],
                [
                    'identifier' => ModuleId::CRED_AND_REG(),
                    'role' => 'SENDER',
                    'url' => 'versionurl/2.2.1/credentials'
                ]
            ]
        ], json_decode($responseInterface->getBody()->getContents(), true)['data']);
    }
}
