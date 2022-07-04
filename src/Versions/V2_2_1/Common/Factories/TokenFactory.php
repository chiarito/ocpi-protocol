<?php

declare(strict_types=1);

namespace Chargemap\OCPI\Versions\V2_2_1\Common\Factories;

use Chargemap\OCPI\Versions\V2_2_1\Common\Models\ProfileType;
use Chargemap\OCPI\Versions\V2_2_1\Common\Models\Token;
use Chargemap\OCPI\Versions\V2_2_1\Common\Models\TokenType;
use Chargemap\OCPI\Versions\V2_2_1\Common\Models\WhiteListType;
use DateTime;
use stdClass;

class TokenFactory
{
    public static function fromJson(?stdClass $json): ?Token
    {
        if ($json === null) {
            return null;
        }

        $token = new Token(
            $json->country_code,
            $json->party_id,
            $json->uid,
            new TokenType($json->type),
            $json->contract_id,
            $json->visual_number ?? null,
            $json->issuer,
            $json->group_id ?? null,
            $json->valid,
            new WhiteListType($json->whitelist),
            $json->language ?? null,
            isset($json->default_profile_type) ? new ProfileType($json->default_profile_type) : null,
            EnergyContractFactory::fromJson($json->energy_contract ?? null),
            new DateTime($json->last_updated)
        );

        return $token;
    }
}