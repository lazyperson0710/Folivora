<?php

declare(strict_types = 1);

namespace lazyperson0710\folivora\features\settings\setting_type\miningTools;

use lazyperson0710\folivora\features\settings\setting_type\IPlayerSetting;

class GraniteToStoneSetting implements IPlayerSetting {

    public const NAME = 'GraniteToStoneSetting';

    public function getName() : string {
        return self::NAME;
    }

    public function getDefaultValue() : bool {
        return false;
    }

    public function normalValue() : array {
        return [
            true,
            false,
        ];
    }
}
