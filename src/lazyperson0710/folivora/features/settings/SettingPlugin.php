<?php

declare(strict_types = 1);

namespace lazyperson0710\folivora\features\settings;

use JsonException;
use lazyperson0710\folivora\util\config\exception\ConfigSaveException;
use lazyperson0710\folivora\util\plugin_base\IPluginBase;
use pocketmine\Server;

class SettingPlugin implements IPluginBase {

    public function onDisable(Server $server): void {
    }

    /**
     * @param Server $server
     * @return void
     * @throws JsonException
     */
    public function onEnable(Server $server): void {
        try {
            Setting::getInstance()->createConfigFile();
        } catch (ConfigSaveException $exception) {
            $server->getLogger()->error($exception->getMessage());
        }
        Setting::getInstance()->registerConfigClass();
    }

}