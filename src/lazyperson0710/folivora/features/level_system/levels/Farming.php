<?php

declare(strict_types = 1);

namespace lazyperson0710\folivora\features\level_system\levels;

use lazyperson0710\folivora\features\level_system\util\LevelConfig;
use lazyperson0710\folivora\features\level_system\util\Levels;
use pocketmine\utils\SingletonTrait;

class Farming implements ILevel {

    use SingletonTrait;

    public const DEFAULT_LEVEL = 1;
    public const DEFAULT_EXP = 1;
    public const DEFAULT_LEVEL_UP_EXP = 250;

    public const LEVEL = Levels::FARMING;

    public const PATH = 'player/levels/farming_level.json';

    /**
     * @return LevelConfig
     */
    public function getConfig(): LevelConfig {
        return new LevelConfig(self::LEVEL, self::PATH);
    }

    /**
     * @return int
     */
    public function getDefaultExp(): int {
        return self::DEFAULT_EXP;
    }

    /**
     * @return int
     */
    public function getDefaultExpToNextLevel(): int {
        return self::DEFAULT_LEVEL_UP_EXP;
    }

    /**
     * @return int
     */
    public function getDefaultLevel(): int {
        return self::DEFAULT_LEVEL;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return self::LEVEL->value;
    }

}
