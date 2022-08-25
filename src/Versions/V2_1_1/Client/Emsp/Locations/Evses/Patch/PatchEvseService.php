<?php

declare(strict_types=1);

namespace Chargemap\OCPI\Versions\V2_1_1\Client\Emsp\Locations\Evses\Patch;

use Chargemap\OCPI\Common\Client\Modules\AbstractFeatures;

class PatchEvseService extends AbstractFeatures
{
    /**
     * @throws \Chargemap\OCPI\Common\Client\OcpiEndpointNotFoundException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function handle(PatchEvseRequest $request): PatchEvseResponse
    {
        $responseInterface = $this->sendRequest($request);
        return new PatchEvseResponse($responseInterface);
    }
}