<?php

declare(strict_types = 1);

namespace lazyperson0710\folivora\features\settings\setting_type\miningTools;

use lazyperson0710\folivora\features\settings\setting_type\IPlayerSetting;

class GoldIngotSetting implements IPlayerSetting {

    public const NAME = 'GoldIngotSetting';

    public function getDefaultValue(): bool {
        return false;
    }

    public function getName(): string {
        return self::NAME;
    }

    public function normalValue(): array {
        return [
            true,
            false,
        ];
    }
}
