<?php

declare(strict_types=1);

namespace Chargemap\OCPI\Versions\V2_2_1\Client\Receiver\Commands\Post;

use Chargemap\OCPI\Common\Client\Modules\AbstractResponse;
use Chargemap\OCPI\Common\Server\Errors\OcpiInvalidPayloadClientError;
use Psr\Http\Message\ResponseInterface;

class PostCommandResultResponse extends AbstractResponse
{

    private function __construct()
    {
    }

    /**
     * @throws OcpiInvalidPayloadClientError
     */
    public static function from(ResponseInterface $response): self
    {
        self::checkStatusCode($response);
        return new self;
    }
}
