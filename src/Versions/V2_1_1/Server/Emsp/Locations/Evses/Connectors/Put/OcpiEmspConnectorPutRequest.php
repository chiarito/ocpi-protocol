<?php

declare(strict_types=1);

namespace Chargemap\OCPI\Versions\V2_1_1\Server\Emsp\Locations\Evses\Connectors\Put;

use Chargemap\OCPI\Common\Utils\PayloadValidation;
use Chargemap\OCPI\Versions\V2_1_1\Common\Factories\ConnectorFactory;
use Chargemap\OCPI\Versions\V2_1_1\Common\Models\Connector;
use Chargemap\OCPI\Versions\V2_1_1\Server\Emsp\Locations\Evses\Connectors\BaseConnectorUpdateRequest;
use Chargemap\OCPI\Versions\V2_1_1\Server\Emsp\Locations\LocationRequestParams;
use Psr\Http\Message\ServerRequestInterface;
use UnexpectedValueException;

class OcpiEmspConnectorPutRequest extends BaseConnectorUpdateRequest
{
    private Connector $connector;

    public function __construct(ServerRequestInterface $request, LocationRequestParams $params)
    {
        parent::__construct($request, $params);
        PayloadValidation::coerce('V2_1_1/eMSP/Server/Locations/Evses/Connectors/connectorPutRequest.schema.json', $this->jsonBody);
        $connector = ConnectorFactory::fromJson($this->jsonBody);
        if ($connector === null) {
            throw new UnexpectedValueException('PartialConnector cannot be null');
        }
        $this->connector = $connector;
    }

    public function getConnector(): Connector
    {
        return $this->connector;
    }
}
