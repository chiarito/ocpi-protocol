<?php

declare(strict_types=1);

namespace Chargemap\OCPI\Common\Client\Modules\Tariffs\GetListing;

use Chargemap\OCPI\Common\Client\Modules\AbstractFeatures;
use Chargemap\OCPI\Common\Client\OcpiServiceNotFoundException;
use Chargemap\OCPI\Common\Client\ServiceFactory;
use Chargemap\OCPI\Versions\V2_2_1\Client\Sender\Tariffs\GetListing\GetTariffsListingResponse as V2_2_1GetTariffsListingResponse;

class GetTariffsListingService extends AbstractFeatures
{
    /**
     * @param GetTariffsListingRequest $request
     * @return GetTariffsListingResponse|V2_2_1GetTariffsListingResponse
     * @throws OcpiServiceNotFoundException
     */
    public function handle(GetTariffsListingRequest $request): GetTariffsListingResponse
    {
        $service = ServiceFactory::from($request, $this->ocpiConfiguration);

        switch (get_class($service)) {
            case \Chargemap\OCPI\Versions\V2_2_1\Client\Sender\Tariffs\GetListing\GetTariffsListingService::class:
                return $service->handle($request);
        }

        throw new OcpiServiceNotFoundException($request->getVersion(), get_class($request), sprintf('No service found for query %s', get_class($service)));
    }
}
